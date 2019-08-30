<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class brand extends Model
{
	public $timestamps = false;
	public function products()
	{
		return $this->hasMany(Product::class);
	}
}
