<?php

namespace App\Listeners;

use App\Events\NewMessageReceivedEvent;
use App\Jobs\NotifyRecipient;
use App\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewMessageNotification
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewMessageReceivedEvent $event)
    {
        NotifyRecipient::dispatch($event->message);
    }
}
