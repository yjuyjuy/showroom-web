<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $guarded = [];
	public $incrementing = false;
	protected $keyType = 'string';
	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'is_direct' => 'boolean',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function seller()
	{
		return $this->morphTo();
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
