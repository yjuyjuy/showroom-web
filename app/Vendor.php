<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
	public function getRouteKeyName()
	{
		return 'name';
	}
	public function image()
	{
		return $this->belongsTo(Image::class);
	}
	public function users()
	{
		return $this->hasMany(User::class);
	}
	public function prices()
	{
		return $this->hasMany(VendorPrice::class);
	}
	public function offers()
	{
		return $this->hasMany(OfferPrice::class);
	}
	public function products()
	{
		return $this->belongsToMany(Product::class, 'vendor_prices', 'vendor_id', 'product_id');
	}
	public function retailer()
	{
		return $this->belongsTo(Retailer::class);
	}
	public function partner_retailers()
	{
		return $this->belongsToMany(Retailer::class, 'vendor_retailer')->withPivot('profit_rate');
	}
	public function resellers()
	{
		return $this->belongsToMany(User::class, 'reseller_vendor');
	}
	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}
