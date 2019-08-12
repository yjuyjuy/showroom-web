<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    public function retails()
		{
			return $this->hasMany(RetailPrice::class);
		}
		public function products()
		{
			return $this->belongsToMany(Product::class, 'retail_prices', 'retailer_id', 'product_id')->whereNull('retail_prices.deleted_at');
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
}
