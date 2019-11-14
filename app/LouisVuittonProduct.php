<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LouisVuittonProduct extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'louisvuitton';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'products';

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;
	/**
		* The "type" of the auto-incrementing ID.
		*
		* @var string
		*/
	protected $keyType = 'string';

	public function image()
	{
		return $this->hasOne(LouisVuittonImage::class, 'product_id');
	}

	public function images()
	{
		return $this->hasMany(LouisVuittonImage::class, 'product_id');
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class, 'subcategory', 'subcategory')->where('category', $this->category)->where('gender', $this->gender);
	}

	public function getBrandIdAttribute()
	{
		return 700854;
	}
}
