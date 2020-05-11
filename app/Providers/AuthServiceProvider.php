<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		'App\TaobaoShop' => 'App\Policies\TaobaoPolicy',
				'App\VendorPrice' => 'App\Policies\PricePolicy',
		'App\Vendor' => 'App\Policies\VendorPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		Passport::routes();
		Passport::tokensExpireIn(now()->addDays(30));
		Passport::refreshTokensExpireIn(now()->addDays(60));
	}
}
