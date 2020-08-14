<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
	public $timestamps = false;
	public function product()
	{
		return $this->hasOne(Product::class)->orderBy('updated_at', 'desc');
	}
	public function products()
	{
		return $this->hasMany(Product::class);
	}
}
