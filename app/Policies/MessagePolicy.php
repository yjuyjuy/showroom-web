<?php

namespace App\Policies;

use App\Message;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function send(User $user, Message $message)
    {
        $sender = $message->sender;
        return $user->is($sender) || ($user->vendor && ($user->vendor->is($sender) || ($user->vendor->retailer && $user->vendor->retailer->is($sender))));
    }
}
