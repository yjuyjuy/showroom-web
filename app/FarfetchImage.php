<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FarfetchImage extends Model
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
	protected $table = 'images';

	public function product()
	{
		return $this->belongsTo(FarfetchProduct::class,'product_id');
	}
}
