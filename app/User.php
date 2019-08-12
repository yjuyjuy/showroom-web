<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'email', 'password','username'
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
		'is_reseller' => 'boolean',
	];
	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [
		'is_reseller' => false,
	];
	public function vendor()
	{
		return $this->belongsTo(Vendor::class);
	}
	public function vendors()
	{
		return $this->belongsToMany(Vendor::class, 'reseller_vendor');
	}
	public function following()
	{
		return $this->belongsToMany(Retailer::class);
	}
	public function isReseller()
	{
		return $this->is_reseller;
	}
	public function isSuperAdmin()
	{
		return in_array($this->id, [1,27,]);
	}
}
