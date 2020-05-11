<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferPrice extends Model
{

	// protected $touches = ['product'];
	protected $guarded = [];
	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'prices' => 'array',
	];
	public function vendor()
	{
		return $this->belongsTo(Vendor::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
	public function logs()
	{
		return $this->morphMany(Log::class, 'price');
	}
}
