<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
	use SoftDeletes;

	protected $guarded = [];
	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'data' => 'array',
	];
	public function vendor()
	{
		return $this->belongsTo(Vendor::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
