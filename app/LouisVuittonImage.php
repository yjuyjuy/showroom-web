<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LouisVuittonImage extends Model
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
	protected $table = 'images';

	public function product()
	{
		return $this->belongsTo(LouisVuittonProduct::class, 'product_id');
	}

	public function getUrlAttribute()
	{
		if ($this->path) {
			return secure_asset('storage/'.$this->path);
		} else {
			return '';
		}
	}
}
