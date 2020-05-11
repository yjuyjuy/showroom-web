<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
	protected $guarded = [];

	public function getDataAttribute($value)
	{
		return json_decode($value, true);
	}

	public function setDataAttribute($value)
	{
		$this->attributes['data'] = json_encode($value, JSON_FORCE_OBJECT);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
