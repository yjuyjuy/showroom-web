<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FarfetchDesigner extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'farfetch';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'designers';

	public $timestamps = false;

	/**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName()
	{
			return 'url_token';
	}
	public function products()
	{
		return $this->hasMany(FarfetchProduct::class, 'designer_id');
	}
}
