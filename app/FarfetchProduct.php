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
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
			// 'raw',
		];
	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'category_ids' => 'array',
		'raw' => 'array',
	];

	public function getSizePriceAttribute()
	{
		$sizes = [];
		foreach ($this->raw['sizes']['available'] as $size_id => $size_detail) {
			if(array_key_exists($size_id,$this->raw['priceInfo'])){
				$sizes[$size_detail['description']] = $this->raw['priceInfo'][$size_id]['formattedFinalPrice'];
			} else {
				$sizes[$size_detail['description']] = $this->raw['priceInfo']['default']['formattedFinalPrice'];
			}
		}
		return $sizes;
	}
	public function displayName()
	{
		return $this->designer->name.' '.$this->shortDescription;
	}
	public function images()
	{
		return $this->hasMany(FarfetchImage::class, 'product_id');
	}
	public function designer()
	{
		return $this->belongsTo(FarfetchDesigner::class);
	}
	public function categories()
	{
		return $this->belongsToMany(FarfetchCategory::class,'category_product','product_id','category_id');
	}
}
