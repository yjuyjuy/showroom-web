<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
	public function product()
	{
		return $this->belongsTo(Product::class);
	}
	public function price()
	{
		return $this->morphTo();
	}
	public function toString()
	{
		if($this->price){
			if($this->action === 'created'){
				return __('change_log.price_created',[ 'description' => $this->product->displayName(), 'vendor' => $this->price->vendor->name]);
			}
			if($this->action === 'updated'){
				return __('change_log.price_updated',[ 'description' => $this->product->displayName(), 'vendor' => $this->price->vendor->name]);
			}
			if($this->action === 'deleted'){
				return __('change_log.price_deleted',[ 'description' => $this->product->displayName(), 'vendor' => $this->price->vendor->name]);
			}
		} else {
			if($this->action === 'created'){
				return __('change_log.product_created',['description' => $this->product->displayName()]);
			}
			if($this->action === 'updated'){
				return __('change_log.product_updated',['description' => $this->product->displayName()]);
			}
			if($this->action === 'deleted'){
				return __('change_log.product_deleted',['description' => $this->product->displayName()]);
			}
		}
	}
}
