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

	public function data()
	{
		return json_decode($this->data, true);
	}
}
