<?php

namespace App\Events;

use App\Message;
use App\User;
use App\Vendor;
use App\Retailer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $message = $this->message;
        if ($message->sender_type == User::class) {
            return new PrivateChannel('user.' . $message->sender_id);
        } else if ($message->sender_type == Vendor::class) {
            return new PrivateChannel('vendor.' . $message->sender_id);
        } else if ($message->sender_type == Retailer::class) {
            return new PrivateChannel('retailer.' . $message->sender_id);
        }
        return [];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }
}
