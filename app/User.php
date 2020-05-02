<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
	use Notifiable;
	use SoftDeletes;
	use HasApiTokens;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id', 'email', 'password','username','type'
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
  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */
  protected $appends = [
		'is_admin',
	];

	private const admin_user_ids = [1111111111, 4021500970, 8888888888];

	public function image()
	{
		return $this->belongsTo(Image::class);
	}
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
	public function getIsAdminAttribute()
	{
		return in_array($this->id, self::admin_user_ids);
	}
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
	public function getIsInvitedAttribute()
	{
		if (strpos($this->type, 'invited:') !== false && Vendor::find(explode(':', $this->type)[1])) {
			return true;
		}
		return false;
	}
	public function setIsInvitedAttribute(Vendor $vendor)
	{
		if ($vendor) $this->type = 'invited:'.$vendor->id;
	}
	public function getInvitedByAttribute()
	{
		if ($this->is_invited) {
			return Vendor::find(explode(':', $this->type)[1]);
		}
	}
	public function isSuperAdmin()
	{
		return in_array($this->id, self::admin_user_ids);
	}
	/**
	 * Send the email verification notification.
	 *
	 * @return void
	 */
	public function sendEmailVerificationNotification()
	{
		Jobs\VerifyEmail::dispatch($this);
	}
}
