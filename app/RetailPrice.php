<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RetailPrice extends Model
{
	protected $guarded = [];
	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'prices' => 'array',
	];
	public function retailer()
	{
		return $this->belongsTo(Retailer::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
	public function logs()
	{
		 return $this->morphMany(Log::class, 'price');
	}
	public function merge($size_price)
	{
		if (empty($size_price)) {
			return;
		}
		$prices = $this->prices ?? [];
		foreach($size_price as $size => $price) {
			if (!array_key_exists($size, $prices) || $prices[$size] > $price) {
				$prices[$size] = $price;
			}
		}
		$this->prices = $prices;
	}
}
