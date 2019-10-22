<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EndProduct extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'end';
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
	protected $casts = [];

	public function image()
	{
		return $this->hasOne(EndImage::class, 'product_id');
	}

	public function images()
	{
		return $this->hasMany(EndImage::class, 'product_id');
	}

	public function brand()
	{
		return $this->belongsTo(EndBrand::class, 'brand_name', 'name');
	}

	public function department()
	{
		return $this->belongsTo(EndDepartment::class, 'department_name', 'name');
	}

	public function getSizePriceAttribute()
	{
		$size_map = [
			'XX-Small' => 'XXS',
			'X-Small' => 'XS',
			'Small' => 'S',
			'Medium' => 'M',
			'Large' => 'L',
			'X-Large' => 'XL',
			'XX-Large' => 'XXL',
			'One Size' => 'OS',
		];
		$size_price = [];
		foreach(explode(',',$this->sizes) as $size) {
			if(array_key_exists($size, $size_map)) {
				$size = $size_map[$size];
			}
			if (!array_key_exists($size, $size_price) || $size_price[$size] > $this->price) {
				$size_price[$size] = $this->price;
			}
		}
		return $size_price;
	}
}
