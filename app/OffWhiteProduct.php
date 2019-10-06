<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffWhiteProduct extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'offwhite';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'products';

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

	public function image()
	{
		return $this->hasOne(\App\OffWhiteImage::class, 'product_id', 'id');
	}
	public function images()
	{
		return $this->hasMany(\App\OffWhiteImage::class, 'product_id', 'id');
	}
	public function getMappedBrandIdAttribute()
	{
		return 885468;
	}
}
