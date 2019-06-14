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

	public function getMinPrice($type = 'retail', $default = false)
	{
		return ($this->prices->isEmpty())? $default : (int)$this->prices->map(function ($item, $key) use ($type) {
			return $item->data->pluck($type)->min();
		})->min();
	}

	public function getPriceAttribute($attribute)
	{
		return $this->getMinPrice() ?? 0;
	}

	public function getCostPriceAttribute($attribute)
	{
		return $this->getMinPrice('cost') ?? 0;
	}

	public function getResellPriceAttribute($attribute)
	{
		return $this->getMinPrice('resell') ?? 0;
	}

	public function displayPrice()
	{
		return ($this->price) ? "\u{00a5}".$this->price : 'not available';
	}

	public function displayCostPrice()
	{
		return ($this->cost_price) ? "\u{00a5}".$this->cost_price : 'not available';
	}

	public function displayResellPrice()
	{
		return ($this->resell_price) ? "\u{00a5}".$this->resell_price : 'not available';
	}


	public static function sort_and_get($sortBy = 'default', $query)
	{
		switch ($sortBy) {
			case 'price low to high':
				return $query->get()->sortBy(
					function ($product, $key) {
						return $product->getMinPrice('retail', INF);
					}
				);

			case 'price high to low':
				return $query->get()->sortByDesc(
					function ($product, $key) {
						return $product->getMinPrice('retail', 0);
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
		return $this->getSizePrice('retail');
	}

	public function getSizePrice($type = 'retail')
	{
		$sizes = [];
		foreach ($this->prices->pluck('data') as $data) {
			foreach ($data as $row) {
				$sizes[$row['size']][] = $row[$type];
			}
		}
		foreach ($sizes as $size => $prices) {
			$sizes[$size] = min($prices);
		}
		return $sizes;
	}

	public function displayName($level = 1)
	{
		switch ($level) {
			case 1:
				return $this->season->name.' '.$this->name_cn;
				break;

			case 2:
				// code...
				return $this->brand->name.' '.$this->season->name.' '.$this->name_cn;
				break;

			default:
				return $this->season->name.' '.$this->name_cn;
				break;
		}
	}
}
