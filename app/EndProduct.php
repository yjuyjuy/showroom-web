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
}
