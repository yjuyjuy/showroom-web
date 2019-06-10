<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
	protected $guarded = [];
	protected $attributes = [];
	public function setCategoryAttribute($attribute)
	{
		$this->category_id = $attribute;
	}
	public function category()
	{
		return $this->belongsTo(Category::class);
	}
	public function setSeasonAttribute($attribute)
	{
		$this->season_id = $attribute;
	}
	public function season()
	{
		return $this->belongsTo(Season::class);
	}
	public function setColorAttribute($attribute)
	{
		$this->color_id = $attribute;
	}
	public function color()
	{
		return $this->belongsTo(Color::class);
	}

	public function setBrandAttribute($attribute)
	{
		$this->brand_id = $attribute;
	}
	public function brand()
	{
		return $this->belongsTo(Brand::class);
	}

	public function vendors()
	{
		return $this->belongsToMany(Vendor::class);
	}

	public function prices()
	{
		return $this->hasMany(Price::class);
	}
	public function images()
	{
		return $this->hasMany(Image::class);
	}
	public function minPrice($default = false)
	{
		return ($this->prices->isEmpty())? $default : (int)$this->prices->map(function ($item, $key) {
			return min(json_decode($item->data, true));
		})->min();
	}
	public function displayPrice()
	{
		return $this->minPrice()? "\u{00a5}".$this->minPrice() : 'not available';
	}
}
