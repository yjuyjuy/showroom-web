<?php

namespace App\Observers;

use App\RetailPrice;

class RetailPriceLogger
{
    /**
     * Handle the retail price "created" event.
     *
     * @param  \App\RetailPrice  $retailPrice
     * @return void
     */
    public function created(RetailPrice $retailPrice)
    {
        //
    }

    /**
     * Handle the retail price "updated" event.
     *
     * @param  \App\RetailPrice  $retailPrice
     * @return void
     */
    public function updated(RetailPrice $retailPrice)
    {
        //
    }

    /**
     * Handle the retail price "deleted" event.
     *
     * @param  \App\RetailPrice  $retailPrice
     * @return void
     */
    public function deleted(RetailPrice $retailPrice)
    {
        //
    }

    /**
     * Handle the retail price "restored" event.
     *
     * @param  \App\RetailPrice  $retailPrice
     * @return void
     */
    public function restored(RetailPrice $retailPrice)
    {
        //
    }

    /**
     * Handle the retail price "force deleted" event.
     *
     * @param  \App\RetailPrice  $retailPrice
     * @return void
     */
    public function forceDeleted(RetailPrice $retailPrice)
    {
        //
    }
}
