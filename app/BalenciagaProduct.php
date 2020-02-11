<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BalenciagaProduct extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'balenciaga';
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

	public const brand_id = 181957;

	public function image()
	{
		return $this->hasOne(BalenciagaImage::class, 'product_id');
	}

	public function images()
	{
		return $this->hasMany(BalenciagaImage::class, 'product_id');
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public static function like(Product $product) {
		$query = self::query();
		if (strlen($product->designer_style_id) > 11) {
			$query->where('designer_style_id', $product->designer_style_id);
		} else {
			$query->where('designer_style_id', 'like', $product->designer_style_id.'%');
		}
		return $query->orWhere('product_id', $product->id)->get();
	}
}
