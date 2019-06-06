<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function season()
	{
		return $this->belongsTo(Season::class);
	}

	public function color()
	{
		return $this->belongsTo(Color::class);
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
	public function minPrice()
	{
		return ($this->prices->isEmpty())? INF : $this->prices->map(function ($item, $key) {
			return min(json_decode($item->data, true));
		})->min();
	}
}
