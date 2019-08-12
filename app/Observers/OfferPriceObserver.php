<?php

namespace App\Observers;

use App\OfferPrice;

class OfferPriceObserver
{
	/**
	 * Handle the offer price "created" event.
	 *
	 * @param  \App\OfferPrice  $offerPrice
	 * @return void
	 */
	public function created(OfferPrice $offerPrice)
	{
		// $vendor = $offerPrice->vendor;
		// foreach($vendor->partner_retailers as $retailer) {
		// 	$profit = $retailer->pivot->profit_rate;
		// 	$retail->prices = Arr::collapse(array_map(function($price, $size) use ($profit) { return [ $size => $price * ($profit / 100.0 + 1),]; }, $offerPrice->prices));
		// }
	}

	/**
	 * Handle the offer price "updated" event.
	 *
	 * @param  \App\OfferPrice  $offerPrice
	 * @return void
	 */
	public function updated(OfferPrice $offerPrice)
	{
		//
	}

	/**
	 * Handle the offer price "deleted" event.
	 *
	 * @param  \App\OfferPrice  $offerPrice
	 * @return void
	 */
	public function deleted(OfferPrice $offerPrice)
	{
		//
	}

	/**
	 * Handle the offer price "restored" event.
	 *
	 * @param  \App\OfferPrice  $offerPrice
	 * @return void
	 */
	public function restored(OfferPrice $offerPrice)
	{
		//
	}

	/**
	 * Handle the offer price "force deleted" event.
	 *
	 * @param  \App\OfferPrice  $offerPrice
	 * @return void
	 */
	public function forceDeleted(OfferPrice $offerPrice)
	{
		//
	}
}
