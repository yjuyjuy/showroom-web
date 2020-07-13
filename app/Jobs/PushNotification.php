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
					'iss' => config('services.apns.team_id'),
					'iat' => now()->timestamp,
				];
				$key = config('services.apns.key');
				$alg = 'ES256';
				$keyId = config('services.apns.key_id');
				return JWT::encode($payload, $key, $alg, $keyId);
			});
			$data = [
				'aps' => [
					'alert' => [
						'title' => $this->title,
						'body' => $this->body,
					],
				],
				'data' => $this->data,
			];
			$data = json_encode($data);
			$url = config('services.apns.url') . '/3/device/' . $device->token;
			$response = `curl -X POST -H "authorization: bearer $jwt" -H "apns-push-type: alert" -H "apns-topic: {$device->app}" -H "content-type: application/json" -d '$data' $url`;
			if (!is_null($response)) {
				throw Exception("Unexpected response: '$response'");
			}
		}
	}
}
