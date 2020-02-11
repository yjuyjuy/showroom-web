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

	public const brand_id = 355854;

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

	public static function like(Product $product) {
		return self::where('id', $product->designer_style_id)->orWhere('product_id', $product->id)->get();
	}
}
