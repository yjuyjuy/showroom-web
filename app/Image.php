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
	public function getSmallAttribute()
	{
		return secure_asset('storage/'.$this->path.'_400.jpeg');
	}
	public function getMediumAttribute()
	{
		return secure_asset('storage/'.$this->path.'_800.jpeg');
	}
	public function getLargeAttribute()
	{
		return secure_asset('storage/'.$this->path);
	}
	public function getUrlAttribute()
	{
		return secure_asset('storage/'.$this->path.'_800.jpeg');
	}
}
