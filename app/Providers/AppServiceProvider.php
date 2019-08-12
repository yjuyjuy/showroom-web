<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		\App\User::observe(\App\Observers\UserObserver::class);
		\App\Product::observe(\App\Observers\ProductLogger::class);
		// \App\OfferPrice::observe(\App\Observers\OfferPriceObserver::class);
		\App\OfferPrice::observe(\App\Observers\OfferPriceLogger::class);
		// \App\RetailPrice::observe(\App\Observers\RetailPriceObserver::class);
		// \App\RetailPrice::observe(\App\Observers\RetailPriceLogger::class);
		\App\VendorPrice::observe(\App\Observers\PriceObserver::class);
	}
}
