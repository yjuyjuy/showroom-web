<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class season extends Model
{
	public function products()
	{
		return $this->hasMany(Product::class);
	}
}
