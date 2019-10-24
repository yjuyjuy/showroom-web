<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id', 'email', 'password','username'
	];
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];
	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];
	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [];
	public function vendor()
	{
		return $this->belongsTo(Vendor::class);
	}
	public function following_vendors()
	{
		return $this->belongsToMany(Vendor::class, 'reseller_vendor');
	}
	public function following_retailers()
	{
		return $this->belongsToMany(Retailer::class, 'user_retailer', 'user_id', 'retailer_id');
	}
	public function following_products()
	{
		return $this->belongsToMany(Product::class, 'user_product', 'user_id', 'product_id');
	}
	# accessors, mutators
	public function getIsResellerAttribute()
	{
		return $this->type == 'reseller';
	}
	public function setIsResellerAttribute($value)
	{
		if($value) $this->type = "reseller";
	}
	public function getIsPendingAttribute()
	{
		return $this->type == 'pending';
	}
	public function setIsPendingAttribute($value)
	{
		if($value) $this->type = "pending";
	}
	public function getIsRejectedAttribute()
	{
		return $this->type == 'rejected';
	}
	public function setIsRejectedAttribute($value)
	{
		if($value) $this->type = 'rejected';
	}
	public function isSuperAdmin()
	{
		return in_array($this->id, [1111111111, 4021500970, 4328089657, 8888888888]);
	}
}
