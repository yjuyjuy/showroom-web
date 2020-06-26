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
}
