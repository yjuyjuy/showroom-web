<?php

namespace App\Observers;

use App\Price;

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
        //
    }

    /**
     * Handle the price "updated" event.
     *
     * @param  \App\Price  $price
     * @return void
     */
    public function updated(Price $price)
    {
        //
    }

    /**
     * Handle the price "deleted" event.
     *
     * @param  \App\Price  $price
     * @return void
     */
    public function deleted(Price $price)
    {
        //
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
