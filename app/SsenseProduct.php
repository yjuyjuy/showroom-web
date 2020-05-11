<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SsenseProduct extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'ssense';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'products';
	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'product_measurements' => 'array',
	];
	public function image()
	{
		return $this->hasOne(SsenseImage::class, 'product_id');
	}

	public function images()
	{
		return $this->hasMany(SsenseImage::class, 'product_id');
	}

	public function brand()
	{
		return $this->belongsTo(SsenseBrand::class, 'brand_name', 'name');
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function displayName()
	{
		return $this->brand_name.' '.$this->name;
	}

	public function getSizePriceAttribute()
	{
		$size_price = [];
		if ($this->sizes) {
			foreach (explode(',', $this->sizes) as $size) {
				if ($size == 'UNI') {
					$size_price['OS'] = $this->price;
				} else {
					$size_price[$size] = $this->price;
				}
			}
		}
		return $size_price;
	}

	public static function like(Product $product)
	{
		return self::where('product_id', $product->id)->get();
	}
}
