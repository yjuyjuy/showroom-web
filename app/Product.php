<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class Product extends Model
{
	protected $guarded = [];
	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [];

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function setCategoryAttribute($value)
	{
		$this->category_id = $value;
	}

	public function season()
	{
		return $this->belongsTo(Season::class);
	}

	public function setSeasonAttribute($value)
	{
		$this->season_id = $value;
	}
	public function color()
	{
		return $this->belongsTo(Color::class);
	}
	public function setColorAttribute($value)
	{
		$this->color_id = $value;
	}

	public function brand()
	{
		return $this->belongsTo(Brand::class);
	}

	public function setBrandAttribute($value)
	{
		$this->brand_id = $value;
	}
	public function vendors()
	{
		return $this->belongsToMany(Product::class, 'prices', 'product_id', 'vendor_id');
	}

	public function prices()
	{
		return $this->hasMany(Price::class);
	}
	public function getImageAttribute()
	{
		return $this->images->sortBy('website_id')->sortBy('type_id')->first();
	}
	public function images()
	{
		return $this->hasMany(Image::class);
	}

	public function getMinPrice($type = 'retail', $default = false)
	{
		return ($this->prices->isEmpty())? $default : (int)$this->prices->map(function ($item, $key) use ($type) {
			return min(Arr::pluck($item->data, $type));
		})->min();
	}

	public function getPriceAttribute($attribute)
	{
		return $this->getMinPrice();
	}

	public function getCostPriceAttribute($attribute)
	{
		return $this->getMinPrice('cost');
	}

	public function getResellPriceAttribute($attribute)
	{
		return $this->getMinPrice('resell');
	}

	public function displayPrice()
	{
		return ($this->price) ? "\u{00a5}".$this->price : 'not available';
	}

	public function getSizePriceAttribute()
	{
		return $this->getSizePrice('retail');
	}

	public function getSizePrice($type = 'retail')
	{
		$sizes = [];
		foreach ($this->prices as $price) {
			foreach ($price->data as $row) {
				$sizes[$row['size']][] = $row[$type];
			}
		}
		foreach ($sizes as $size => $prices) {
			$sizes[$size] = min($prices);
		}
		$sizes = Arr::sort($sizes, function ($value, $key) {
			return array_search($key, ['XXS','XS','S','M','L','XL','XXL']);
		});
		return $sizes;
	}


	public function getSizeAllPrice()
	{
		$prices = [];
		foreach ($this->prices->pluck('data') as $data) {
			foreach ($data as $row) {
				$prices[$row['size']]['cost'][] = $row['cost'];
				$prices[$row['size']]['resell'][] = $row['resell'];
				$prices[$row['size']]['retail'][] = $row['retail'];
			}
		}
		foreach ($prices as $size => $values) {
			$prices[$size]['cost'] = min($values['cost']);
			$prices[$size]['resell'] = min($values['resell']);
			$prices[$size]['retail'] = min($values['retail']);
		}

		uksort($prices, function ($a, $b) {
			return ($a === $b)? 0 : ((array_search($a, ['XXS','XS','S','M','L','XL','XXL']) < array_search($b, ['XXS','XS','S','M','L','XL','XXL'])) ? -1 : 1);
		});

		return $prices;
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
