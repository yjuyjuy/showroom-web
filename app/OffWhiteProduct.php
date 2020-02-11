<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffWhiteProduct extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'offwhite';
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

	public const brand_id = 885468;

	public function image()
	{
		return $this->hasOne(\App\OffWhiteImage::class, 'product_id', 'id');
	}
	public function images()
	{
		return $this->hasMany(\App\OffWhiteImage::class, 'product_id', 'id');
	}

	public static function like(Product $product) {
		return self::where('id', $product->designer_style_id)->orWhere('product_id', $product->id)->get();
	}
}
