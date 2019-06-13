<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
	protected $guarded = [];
	protected $attributes = [];

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

	public function getMinPrice($default = false)
	{
		return ($this->prices->isEmpty())? $default : (int)$this->prices->map(function ($item, $key) {
			return min($item->data);
		})->min();
	}

	public function getMinCostPrice($default = false)
	{
		return ($this->prices->isEmpty())? $default : (int)$this->prices->map(function ($item, $key) {
			return min($item->data);
		})->min();
	}

	public function getPriceAttribute($attribute)
	{
		return $this->getMinPrice()? (int)($this->getMinPrice() * 1.2) : 0;
	}

	public function getCostPriceAttribute($attribute)
	{
		return $this->getMinCostPrice()? $this->getMinPrice() : 0;
	}

	public function displayPrice()
	{
		return ($this->price) ? "\u{00a5}".$this->price : 'not available';
	}

	public function displayCostPrice()
	{
		return ($this->cost_price) ? "\u{00a5}".$this->cost_price : 'not available';
	}

	public static function sort_and_get($name = 'default', $query)
	{
		switch ($name) {
			case 'price low to high':
				return $query->get()->sortBy(
					function ($product, $key) {
						return $product->minPrice(INF);
					}
				);

			case 'price high to low':
				return $query->get()->sortByDesc(
					function ($product, $key) {
						return $product->minPrice(0);
					}
				);

			case 'hottest':
				return $query->orderBy('season_id', 'desc')->get();

			case 'best selling':
				return $query->orderBy('season_id', 'desc')->orderBy('id', 'asc')->get();

			case 'newest':
				return $query->orderBy('season_id', 'desc')->orderBy('id', 'asc')->get();

			case 'oldest':
				return $query->orderBy('season_id', 'asc')->orderBy('id', 'asc')->get();

			default:
				return $query->orderBy('season_id', 'desc')->orderBy('id', 'asc')->get();
		}
	}

	public function getSizePriceAttribute()
	{
		$sizes = [];
		foreach ($this->prices->pluck('data') as $data) {
			foreach ($data as $size => $price) {
				$sizes[$size][] = $price;
			}
		}
		foreach ($sizes as $size => $prices) {
			$sizes[$size] = (int)(min($prices) * 1.2);
		}
		return $sizes;
	}

	public function getSizeCostPriceAttribute()
	{
		$sizes = [];
		foreach ($this->prices->pluck('data') as $data) {
			foreach ($data as $size => $price) {
				$sizes[$size][] = $price;
			}
		}
		foreach ($sizes as $size => $prices) {
			$sizes[$size] = (int)min($prices);
		}
		return $sizes;
	}
}
