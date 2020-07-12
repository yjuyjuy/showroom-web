<?php

namespace App\Jobs;

use Firebase\JWT\JWT;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PushNotification implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $device;
	protected $title;
	protected $body;
	protected $data;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($device, $title = null, $body = null, $data = [])
	{
		$this->device = $device;
		$this->title = $title;
		$this->body = $body;
		$this->data = array_merge(['click_action' => 'FLUTTER_NOTIFICATION_CLICK'], $data);
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$device = $this->device;
		if ($device->is_android) {
			$response = Http::withHeaders([
				'Content-Type' => 'application/json',
				'Authorization' => 'key=' . config('services.fcm.key'),
			])->post(config('services.fcm.url'), [
				'to' => $device->token,
				'notification' => [
					'title' => $this->title,
					'body' => $this->body,
				],
				'data' => $this->data,
				'priority' => 'high',
			]);
			$response->throw();
		} else if ($device->is_ios) {
			$jwt = Cache::remember('apns-jwt', 60 * 30, function () {
				$payload = [
					'alg' => 'ES256',
					'kid' => config('services.apns.key_id'),
					'iss' => config('services.apns.team_id'),
					'iat' => now()->timestamp,
				];
				$key = config('services.apns.key');
				return JWT::encode($payload, $key);
			});
			$response = Http::withOptions(
				[
					'headers' => [
						':method' => 'POST',
						':path' => '/3/device/' . $device->token,
						'authorization' => 'bearer ' . $jwt,
						'apns-push-type' => 'alert',
						'apns-topic' => $device->app,
					],
					'version' => 2.0,
					'curl' => [
						'CURLOPT_SSLVERSION' => 'CURL_SSLVERSION_TLSv1_2'
					],
				]
			)->post(config('services.apns.url'), [
				'aps' => [
					'alert' => [
						'title' => $this->title,
						'body' => $this->body,
					],
				],
				'data' => $this->data,
			]);
			$response->throw();
		}
	}
}
