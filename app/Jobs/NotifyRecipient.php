<?php

namespace App\Jobs;

use App\Message;
use App\User;
use App\Vendor;
use App\Retailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyRecipient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = $this->message;
        $sender = $message->sender;
        $title = $sender->username ?? $sender->name;
        $body = substr($message->content, 0, 128);
        $data = ['link' => 'https://www.notdopebxtch.com/messages'];
        $recipient = $message->recipient;

        if ($recipient instanceof User) {
            foreach ($recipient->devices as $device) {
                PushNotification::dispatch($device, $title, $body, $data);
            }
        } else if ($recipient instanceof Vendor) {
            foreach ($recipient->users as $user) {
                foreach ($user->devices as $device) {
                    PushNotification::dispatch($device, $title, $body, $data);
                }
            }
        }
        else if ($recipient instanceof Retailer) {
            foreach ($recipient->vendors as $vendor) {
                foreach ($vendor->users as $user) {
                    foreach ($user->devices as $device) {
                        PushNotification::dispatch($device, $title, $body, $data);
                    }
                }
            }
        }
    }
}
