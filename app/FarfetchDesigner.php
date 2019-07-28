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

	public function products()
	{
		return $this->belongsToMany(FarfetchProduct::class);
	}
}
