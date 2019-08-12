<?php

namespace App\Observers;

use App\OfferPrice;
use App\Log;

class OfferPriceLogger
{
	/**
	 * Handle the price "created" event.
	 *
	 * @param  \App\OfferPrice  $price
	 * @return void
	 */
	public function created(OfferPrice $price)
	{
		$log = new Log;
		$log->product()->associate($price->product);
		$log->price()->associate($price);
		$log->action = 'created';
		$log->save();
	}

	/**
	 * Handle the price "updated" event.
	 *
	 * @param  \App\OfferPrice  $price
	 * @return void
	 */
	public function updated(OfferPrice $price)
	{
		$log = new Log;
		$log->product()->associate($price->product);
		$log->price()->associate($price);
		$log->action = 'updated';
		$log->save();
	}

	/**
	 * Handle the price "deleted" event.
	 *
	 * @param  \App\OfferPrice  $price
	 * @return void
	 */
	public function deleted(OfferPrice $price)
	{
		$log = new Log;
		$log->product()->associate($price->product);
		$log->price()->associate($price);
		$log->action = 'deleted';
		$log->save();
	}

	/**
	 * Handle the price "restored" event.
	 *
	 * @param  \App\OfferPrice  $price
	 * @return void
	 */
	public function restored(OfferPrice $price)
	{
		//
	}

	/**
	 * Handle the price "force deleted" event.
	 *
	 * @param  \App\OfferPrice  $price
	 * @return void
	 */
	public function forceDeleted(OfferPrice $price)
	{
		//
	}
}
