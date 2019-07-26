<?php

namespace App\Observers;

use App\Price;
use App\Log;

class PriceObserver
{
    /**
     * Handle the price "created" event.
     *
     * @param  \App\Price  $price
     * @return void
     */
    public function created(Price $price)
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
     * @param  \App\Price  $price
     * @return void
     */
    public function updated(Price $price)
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
     * @param  \App\Price  $price
     * @return void
     */
    public function deleted(Price $price)
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
     * @param  \App\Price  $price
     * @return void
     */
    public function restored(Price $price)
    {
        //
    }

    /**
     * Handle the price "force deleted" event.
     *
     * @param  \App\Price  $price
     * @return void
     */
    public function forceDeleted(Price $price)
    {
        //
    }
}
