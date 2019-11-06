<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaobaoShop extends Model
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
	protected $table = 'shops';
	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'is_partner' => 'bool',
	];
	/**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName()
	{
	    return 'name';
	}
	public function products()
	{
		return $this->belongsToMany(Product::class, 'taobao.prices', 'shop_id', 'product_id');
	}
	public function taobao_products()
	{
		return $this->hasMany(TaobaoProduct::class, 'shop_id')->where('is_shipping', false);
	}
	public function prices()
	{
		return $this->hasMany(TaobaoPrice::class, 'shop_id');
	}
	public function retailer()
	{
		return $this->belongsTo(Retailer::class, 'retailer_id');
	}
}
