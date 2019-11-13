<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiorProduct extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'dior';
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
		return $this->hasOne(DiorImage::class, 'product_id');
	}

	public function images()
	{
		return $this->hasMany(DiorImage::class, 'product_id');
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function getBrandIdAttribute()
	{
		return 355854;
	}
}
