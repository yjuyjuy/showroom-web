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

	public const brand_id = 421758;

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

	public function getCategoryTranslationAttribute()
	{
		if ($this->category) {
			return implode('-', array_map( '__', explode('-', $this->category) ) );
		} else {
			return '';
		};
	}

	public static function like(Product $product) {
		$query = self::query();
		if (strlen($product->designer_style_id) > 11) {
			$query->where('id', $product->designer_style_id);
		} else {
			$query->where('id', 'like', $product->designer_style_id.'%');
		}
		return $query->orWhere('product_id', $product->id)->get();
	}
}
