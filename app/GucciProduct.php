<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GucciProduct extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'gucci';
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
		return $this->hasOne(GucciImage::class, 'product_id');
	}

	public function images()
	{
		return $this->hasMany(GucciImage::class, 'product_id');
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function getBrandIdAttribute()
	{
		return 421758;
	}

	public function getCategoryTranslationAttribute()
	{
		if ($this->category) {
			return implode('-' array_map( '__', explode('-', $this->category) ) )
		} else {
			return '';
		};
	}
}
