<?php

namespace App\Providers;

use Laravel\Telescope\Telescope;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		Telescope::night();

		$this->hideSensitiveRequestDetails();

		Telescope::tag(function (IncomingEntry $entry) {
			$tags = [];
			if ($entry->type === 'request') {
				$tags[] = 'status:'.$entry->content['response_status'];
				if (in_array($entry->content['uri'], ['/register', '/login', '/logout'])) {
					$tags[] = 'auth';
				}
				$tags[] = 'method:'.$entry->content['method'];
				if ($user = auth()->user()) {
					if ($user->is_admin) {
						$tags[] = 'user:admin';
					} elseif ($user->vendor) {
						$tags[] = 'user:vendor';
					} elseif ($user->is_reseller) {
						$tags[] = 'user:reseller';
					} else {
						$tags[] = 'user:customer';
					}
				} else {
					$tags[] = 'user:guest';
				}
			}
			return $tags;
		});

		Telescope::filter(function (IncomingEntry $entry) {
			if ($entry->type === 'request' && strpos($entry->content['uri'], '/storage') !== false) {
				return false;
			}
			return true;
		});
	}

	/**
	 * Prevent sensitive request details from being logged by Telescope.
	 *
	 * @return void
	 */
	protected function hideSensitiveRequestDetails()
	{
		if ($this->app->isLocal()) {
			return;
		}

		Telescope::hideRequestParameters([
		  '_token',
		  'old_password',
		  'new_password',
		  'new_password_confirmation',
		]);

		Telescope::hideRequestHeaders([
			'cookie',
			'x-csrf-token',
			'x-xsrf-token',
		]);
	}

	/**
	 * Register the Telescope gate.
	 *
	 * This gate determines who can access Telescope in non-local environments.
	 *
	 * @return void
	 */
	protected function gate()
	{
		Gate::define('viewTelescope', function ($user) {
			return in_array($user->email, [
			  'yjuyjuy@gmail.com',
			]);
		});
	}
}
