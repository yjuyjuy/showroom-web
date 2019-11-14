<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LouisVuittonCategory extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'louisvuitton';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'categories';

	public function products()
	{
		return $this->hasMany(LouisVuittonProduct::class, 'subcategory', 'subcategory')->where('category', $this->category)->where('gender', $this->gender);
	}
}
