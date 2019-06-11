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
		return json_decode($attribute, true);
	}
	public function setDataAttribute($value)
	{
		$this->attributes['data'] = json_encode($value);
	}
}
