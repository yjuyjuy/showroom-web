<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FarfetchProduct extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'farfetch';
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
	protected $casts = [
		'size_price' => 'array',
	];
	protected $guarded = [];

	public function displayName()
	{
		return $this->designer->description.' '.$this->short_description;
	}

	public function image()
	{
		return $this->hasOne(FarfetchImage::class, 'product_id');
	}

	public function images()
	{
		return $this->hasMany(FarfetchImage::class, 'product_id');
	}
	public function designer()
	{
		return $this->belongsTo(FarfetchDesigner::class, 'designer_id');
	}
	public function category()
	{
		return $this->belongsTo(FarfetchCategory::class, 'category_id');
	}
	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id');
	}

	public static function like(Product $product) {
		return self::where(function($query) use ($product) {
			if (strlen($product->designer_style_id) > 11) {
				$query->where('designer_style_id', 'like', substr($product->designer_style_id, 0, -4).'%');
			} else {
				$query->where('designer_style_id', $product->designer_style_id);
			}
			$query->whereIn('designer_id',
				FarfetchDesigner::where('mapped_id', $product->brand_id)->pluck('id')->toArray()
			);
		})->orWhere('product_id', $product->id)->get();
	}
}
