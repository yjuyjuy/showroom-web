<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
	public function users()
	{
		return $this->hasMany(User::class);
	}
	public function prices()
	{
		return $this->hasMany(VendorPrice::class);
	}
	public function offer()
	{
		return $this->hasMany(OfferPrice::class);
	}
	public function products()
	{
		return $this->belongsToMany(Product::class, 'offer_prices', 'vendor_id', 'product_id')->whereNull('offer_prices.deleted_at');
	}
	public function retailer()
	{
		return $this->belongsTo(Retailer::class);
	}
	public function partners()
	{
		return $this->belongsToMany(Retailer::class, 'partners');
	}
	public function resellers()
	{
		return $this->belongsToMany(User::class, 'reseller_vendor');
	}
}
