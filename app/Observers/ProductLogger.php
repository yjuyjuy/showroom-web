<?php

namespace App\Observers;

use App\Product;
use App\Log;

class ProductLogger
{
	/**
	 * Handle the product "created" event.
	 *
	 * @param  \App\Product  $product
	 * @return void
	 */
	public function created(Product $product)
	{
		$log = new Log;
		$log->product()->associate($product);
		$log->action = 'created';
		$log->save();
	}

	/**
	 * Handle the product "updated" event.
	 *
	 * @param  \App\Product  $product
	 * @return void
	 */
	public function updated(Product $product)
	{
		$log = new Log;
		$log->product()->associate($product);
		$log->action = 'updated';
		$log->save();
	}

	/**
	 * Handle the product "deleted" event.
	 *
	 * @param  \App\Product  $product
	 * @return void
	 */
	public function deleted(Product $product)
	{
		$log = new Log;
		$log->product()->associate($product);
		$log->action = 'deleted';
		$log->save();
	}

	/**
	 * Handle the product "restored" event.
	 *
	 * @param  \App\Product  $product
	 * @return void
	 */
	public function restored(Product $product)
	{
		//
	}

	/**
	 * Handle the product "force deleted" event.
	 *
	 * @param  \App\Product  $product
	 * @return void
	 */
	public function forceDeleted(Product $product)
	{
		//
	}
}