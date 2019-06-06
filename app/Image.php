<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	public function product()
	{
		return $this->belongsTo(Product::class);
	}
	public function website()
	{
		return $this->belongsTo(Website::class);
	}
	public function type()
	{
		return $this->belongsTo(Type::class);
	}
}
