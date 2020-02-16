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
	/**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName()
	{
	    return 'name';
	}

	public const public_retailer_ids = [
		// 2471873538, // Dopebxtch
		1467053076, // Farfetch
		3548857028, // EndClothing
		8373593846, // Dior
		2294995609, // Gucci
		4354186782, // LouisVuitton
		3978029761, // Balenciaga
	];

  /**
   * Scope a query to only include public retailers.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopePublic($query)
  {
    return $query->whereIn('id', self::public_retailer_ids);
  }

	public function getIsPublicAttribute()
	{
		return in_array($this->id, self::public_retailer_ids);
	}

	# relationships
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
		return $this->belongsToMany(Vendor::class, 'vendor_retailer')->withPivot('profit_rate');
	}
	public function vendors()
	{
		return $this->hasMany(Vendor::class);
	}
	public function followers()
	{
		return $this->belongsToMany(User::class, 'user_retailer', 'retailer_id', 'user_id');
	}
  public function taobao_shop()
  {
    return $this->hasOne(TaobaoShop::class, 'retailer_id', 'id');
  }
}
