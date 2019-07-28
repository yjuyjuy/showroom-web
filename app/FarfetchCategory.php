<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FarfetchCategory extends Model
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
	protected $table = 'categories';

	public function products()
	{
		return $this->belongsToMany(FarfetchProduct::class,'category_product','category_id','product_id');
	}
}
