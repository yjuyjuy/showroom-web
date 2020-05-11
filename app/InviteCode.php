<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InviteCode extends Model
{
	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;
	/**
		* The "type" of the auto-incrementing ID.
		*
		* @var string
		*/
	protected $keyType = 'string';
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	protected $guarded = [];

	public function vendor()
	{
		return $this->belongsTo(\App\Vendor::class);
	}
}
