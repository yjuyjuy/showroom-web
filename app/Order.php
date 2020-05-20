<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $guarded = [];
	public $incrementing = false;
	protected $keyType = 'string';

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function vendor()
	{
		return $this->belongsTo(Vendor::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
