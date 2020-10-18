<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FarfetchImage extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'farfetch';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'images';

	public function product()
	{
		return $this->belongsTo(FarfetchProduct::class, 'product_id');
	}

	public function getSmallAttribute()
	{
		if ($this->path) {
			return asset(str_replace('images/', 'images/w200/', 'storage/' . $this->path));
		} else {
			return $this->attributes['url'];
		}
	}

	public function getMediumAttribute()
	{
		if ($this->path) {
			return asset(str_replace('images/', 'images/w400/', 'storage/' . $this->path));
		} else {
			return $this->attributes['url'];
		}
	}

	public function getLargeAttribute()
	{
		if ($this->path) {
			return asset(str_replace('images/', 'images/w800/', 'storage/' . $this->path));
		} else {
			return $this->attributes['url'];
		}
	}

	public function getUrlAttribute()
	{
		if ($this->path) {
			return asset('storage/'.$this->path);
		} else {
			return $this->attributes['url'];
		}
	}
}
