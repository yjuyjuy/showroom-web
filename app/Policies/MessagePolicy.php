<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function sentAs(User $user, $sender)
    {
        return $user->is($sender) || ($user->vendor && ($user->vendor->is($sender) || ($user->vendor->retailer && $user->vendor->retailer->is($sender))));
    }
}
