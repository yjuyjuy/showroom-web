<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaobaoProduct extends Model
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
	protected $table = 'products';
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [];
	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'propertyMap' => 'array',
		'skuMap' => 'array',
		'properties' => 'array',
	];
	public function shop()
	{
		return $this->belongsTo(TaobaoShop::class, 'shop_id');
	}
	public function prices()
	{
		return $this->hasMany(TaobaoPrice::class, 'taobao_id');
	}
	public function getUrlAttribute()
	{
		return "https://item.taobao.com/item.htm?id=".$this->id;
	}
	public function getPriceAttribute()
	{
		$min = null;
		foreach($this->prices->where('prices', true) as $taobao_price){
			if(!$min || min($taobao_price->prices) < $min){
				$min = min($taobao_price->prices);
			}
		}
		return $min ?? null;
	}
}
