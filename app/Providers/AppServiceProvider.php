<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
		\App\VendorPrice::observe(\App\Observers\PriceObserver::class);
		\Illuminate\Support\Facades\URL::forceScheme('https');

		Gate::define('viewWebSocketsDashboard', function ($user = null) {
			return $user && $user->is_admin;
		});
	}
}
