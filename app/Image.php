<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	protected $guarded = [];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
	public function website()
	{
		return $this->belongsTo(Website::class);
	}
	public function getUrlAttribute()
	{
		return asset('storage/'.$this->path);
	}
}
