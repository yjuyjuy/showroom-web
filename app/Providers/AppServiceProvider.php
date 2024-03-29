<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

Paginator::useBootstrap();

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
		\App\VendorPrice::observe(\App\Observers\PriceObserver::class);
		\Illuminate\Support\Facades\URL::forceScheme('https');
	}
}
