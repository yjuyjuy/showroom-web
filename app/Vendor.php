<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function prices()
	{
		return $this->hasMany(Price::class);
	}
}
