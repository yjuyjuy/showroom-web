<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function from()
    {
        return $this->morphTo();
    }
    public function to()
    {
        return $this->morphTo();
    }
}
