<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SsenseImage extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'ssense';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'images';

	public function product()
	{
		return $this->belongsTo(SsenseProduct::class, 'product_id');
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
