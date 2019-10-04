<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffWhiteImage extends Model
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
	protected $table = 'images';

	public function product()
	{
		return $this->belongsTo(OffWhiteProduct::class, 'product_id', 'id');
	}
}
