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
	public $brand_id = self::brand_id;

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
			return implode('-', array_map('__', explode('-', $this->category)));
		} else {
			return '';
		};
	}

	public static function like(Product $product)
	{
		$query = self::where('product_id', $product->id);
		if ($product->designer_style_id) {
			foreach ($product->designer_style_ids as $id) {
				if (strlen($id) > 11) {
					$query->orWhere('id', $id);
				} else {
					$query->orWhere('id', 'like', $id . '%');
				}
			}
		}
		return $query->get();
	}
}
