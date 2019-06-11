<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
	protected $guarded = [];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function prices()
	{
		return $this->hasMany(Price::class);
	}

	public function products()
	{
		return $this->belongsToMany(Product::class, 'prices', 'vendor_id', 'product_id');
	}
}
