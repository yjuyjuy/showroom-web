<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;

	protected $guarded = [];
	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [];

	// Relationships
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
	public function image()
	{
		return $this->hasOne(Image::class)->orderBy('website_id')->orderBy('type_id');
	}
	public function logs()
	{
		return $this->hasMany(Log::class);
	}
	// Mutators
	public function setCategoryAttribute($value)
	{
		$this->category_id = $value;
	}
	public function setSeasonAttribute($value)
	{
		$this->season_id = $value;
	}
	public function setColorAttribute($value)
	{
		$this->color_id = $value;
	}
	public function setBrandAttribute($value)
	{
		$this->brand_id = $value;
	}
	// Helpers
	public function getMinPrice($default = false)
	{
		return ($this->prices->isEmpty())? $default : (int)$this->prices->map(function ($item, $key) use ($type) {
			return min(Arr::pluck($item->prices, $type));
		})->min();
	}
	public function getPriceAttribute($attribute)
	{
		return $this->getMinPrice();
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
	public function getAllPrices()
	{
		$prices = collect();
		foreach ($this->prices as $price) {
			foreach ($price->data as $row) {
				$prices->push([
					'vendor' => $price->vendor->id,
					'size' => $row['size'],
					'cost' => $row['cost'],
					'resell' => $row['resell'],
					'retail' => $row['retail'],
				]);
			}
		}
		return $prices;
	}
	public function displayName()
	{
		return $this->brand->name.' '.$this->season->name.' '.$this->name_cn;
	}
}
