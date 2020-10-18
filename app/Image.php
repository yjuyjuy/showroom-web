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
	public function uploadedBy()
	{
		return $this->belongsTo(User::class);
	}
	public function users()
	{
		return $this->hasMany(User::class);
	}
	public function vendors()
	{
		return $this->hasMany(Vendor::class);
	}
	public function retailers()
	{
		return $this->hasMany(Retailer::class);
	}
	public function getSmallAttribute()
	{
		return asset(str_replace('images/', 'images/w200/', 'storage/' . $this->path));
	}
	public function getMediumAttribute()
	{
		return asset(str_replace('images/', 'images/w400/', 'storage/' . $this->path));
	}
	public function getLargeAttribute()
	{
		return asset(str_replace('images/', 'images/w800/', 'storage/' . $this->path));
	}
	public function getUrlAttribute()
	{
		return asset(str_replace('images/', 'images/w400/', 'storage/' . $this->path));
	}
}
