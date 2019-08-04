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
		\App\Product::observe(\App\Observers\ProductObserver::class);
		\App\OfferPrice::observe(\App\Observers\PriceObserver::class);
	}
}
