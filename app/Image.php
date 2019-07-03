<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	protected $guarded = [];

	public function scopeFront($query)
	{
		return $query->whereIn('type_id', [1,2,9,10,11])->orderBy('website_id', 'asc')->orderBy('type_id', 'asc');
	}
	public function scopeBack($query)
	{
		return $query->whereIn('type_id', [3,4,12])->orderBy('website_id', 'asc')->orderBy('type_id', 'asc');
	}
	public function product()
	{
		return $this->belongsTo(Product::class);
	}
	public function website()
	{
		return $this->belongsTo(Website::class);
	}
	public function type()
	{
		return $this->belongsTo(Type::class);
	}
	public function getUrlAttribute()
	{
		return asset('storage/'.$this->path);
	}
}
