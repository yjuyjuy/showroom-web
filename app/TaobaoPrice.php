<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaobaoPrice extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'taobao';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'prices';
	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'ignore' => 'bool',
		'prices' => 'array',
	];
	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id');
	}
	public function taobao_product()
	{
		return $this->belongsTo(TaobaoProduct::class, 'taobao_id')->where('is_shipping', false);
	}
	public function shop()
	{
		return $this->belongsTo(TaobaoShop::class, 'shop_id');
	}
	public function getDescriptionAttribute()
	{
		return $this->taobao_product->title.'-'.$this->taobao_product->properties[$this->property_id]['name'];
	}
	public function getUrlAttribute()
	{
		return "https://item.taobao.com/item.htm?id=".$this->taobao_id;
	}
}
