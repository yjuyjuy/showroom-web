<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function sender()
    {
        return $this->morphTo();
    }
    public function recipient()
    {
        return $this->morphTo();
    }
    public function fromUser(User $user)
    {
        $accounts = $user->accounts;
        foreach ($accounts as $account) {
            if (
                $this->sender_id == $account->id
                && $this->sender_type == $account::class
            ) {
                return true;
            }
        }
        return false;
    }
}
