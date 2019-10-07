<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EndBrand extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'end';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'brands';

	public function products()
	{
		return $this->hasMany(EndProduct::class, 'brand_name', 'name');
	}
}
