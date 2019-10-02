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
		return $this->hasMany(FarfetchProduct::class, 'category_id');
	}
	public function getDescriptionAttribute()
	{
		$description = $this->attributes['description'];
		if (strpos($this->url_token, '-1')) {
			return '女装-'.$description;
		} elseif (strpos($this->url_token, '-2')) {
			return '男装-'.$description;
		} else {
			return $description;
		}
	}
}
