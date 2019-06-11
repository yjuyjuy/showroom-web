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
		return $this->belongsToMany(Product::class, 'prices', 'product_id', 'vendor_id');
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
			return min($item->data);
		})->min();
	}
	public function displayPrice()
	{
		return $this->minPrice()? "\u{00a5}".$this->minPrice() : 'not available';
	}

	public static function sort_and_get($name = 'default', $query)
	{
		if ($name === 'default') {
			return $query->orderBy('season_id', 'desc')->orderBy('id', 'asc')->get();
		}

		if ($name === 'price low to high') {
			return $query->get()->sortBy(
				function ($product, $key) {
					return $product->minPrice(INF);
				}
			);
		}

		if ($name === 'price high to low') {
			return $query->get()->sortByDesc(
				function ($product, $key) {
					return $product->minPrice(0);
				}
			);
		}

		if ($name === 'hottest') {
			return $query->orderBy('season_id', 'desc')->get();
		}

		if ($name === 'best selling') {
			return $query->orderBy('season_id', 'asc')->get();
		}

		if ($name === 'newest') {
			return $query->orderBy('season_id', 'desc')->orderBy('id', 'asc')->get();
		}

		if ($name === 'oldest') {
			return $query->orderBy('season_id', 'asc')->orderBy('id', 'asc')->get();
		}
		return $query->orderBy('season_id', 'desc')->orderBy('id', 'asc')->get();
	}
}
