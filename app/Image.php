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
	public function getSmallAttribute()
	{
		return asset('storage/'.$this->path.'_400.jpeg');
	}
	public function getMediumAttribute()
	{
		return asset('storage/'.$this->path.'_800.jpeg');
	}
	public function getLargeAttribute()
	{
		return asset('storage/'.$this->path);
	}
	public function getUrlAttribute()
	{
		return asset('storage/'.$this->path.'_800.jpeg');
	}
}
