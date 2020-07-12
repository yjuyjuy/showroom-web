<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'token';

	/**
	 * The "type" of the primary key ID.
	 *
	 * @var string
	 */
	protected $keyType = 'string';

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;

	protected $guarded = [];
	
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function getIsIosAttribute()
	{
		return $this->os === 'ios';
	}

	public function getIsAndroidAttribute()
	{
		return $this->os === 'android';
	}
}
