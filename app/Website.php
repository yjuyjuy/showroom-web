<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
	public $timestamps = false;
	public function images()
	{
		return $this->hasMany(Image::class)->orderBy('order');
	}
}
