<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'mysql';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'retailers';
  
	public function retails()
	{
		return $this->hasMany(RetailPrice::class);
	}
	public function products()
	{
		return $this->belongsToMany(Product::class, 'retail_prices', 'retailer_id', 'product_id');
	}
	public function partner_vendors()
	{
		return $this->belongsToMany(Vendor::class, 'partners')->withPivot('profit_rate');
	}
	public function vendors()
	{
		return $this->hasMany(Vendor::class);
	}
	public function followers()
	{
		return $this->belongsToMany(User::class);
	}
  public function taobao_shop()
  {
    return $this->hasOne(TaobaoShop::class, 'retailer_id', 'id');
  }
}
