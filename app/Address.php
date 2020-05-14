<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [
		'name' => '',
		'phone' => '',
		'address1' => '',
		'address2' => '',
		'city' => '',
		'state' => '',
		'country' => '',
		'zip' => '',
	];
	protected $guarded = [];
	
	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
