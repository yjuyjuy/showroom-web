<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EndImage extends Model
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
	protected $table = 'images';

	public function product()
	{
		return $this->belongsTo(EndProduct::class, 'product_id');
	}

	public function getUrlAttribute()
	{
		if ($this->path) {
			return asset('storage/'.$this->path);
		} else {
			return $this->attributes['url'];
		}
	}
}
