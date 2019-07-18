<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
	public function images()
	{
		return $this->hasMany(Image::class);
	}
}
