<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	public function scopeFront($query)
	{
		return $query->whereIn('type_id', [1,2,9,10,11]);
	}
	public function scopeBack($query)
	{
		return $query->whereIn('type_id', [3,4,12]);
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
}
