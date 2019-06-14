<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
	public function vendor()
	{
		return $this->belongsTo(Vendor::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function getDataAttribute($attribute)
	{
		return collect(json_decode($attribute, true));
	}
}
