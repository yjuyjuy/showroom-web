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
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
		'confirmed_at',
		'paid_at',
		'shipped_at',
		'delivered_at',
		'completed_at',
		'closed_at',
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
