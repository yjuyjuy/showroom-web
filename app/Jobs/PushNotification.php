<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class PushNotification implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $token;
	protected $title;
	protected $body;
	protected $data;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($token, $title = null, $body = null, $data = [])
	{
		$this->token = $token;
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
		$response = Http::withHeaders([
			'Content-Type' => 'application/json',
			'Authorization' => 'key=' . env('FCM_SERVER_KEY'),
		])->post('https://fcm.googleapis.com/fcm/send', [
			'to' => $this->token,
			'notification' => [
				'title' => $this->title,
				'body' => $this->body,
			],
			'data' => $this->data,
			'priority' => 'high',
		]);
		$response->throw();
	}
}
