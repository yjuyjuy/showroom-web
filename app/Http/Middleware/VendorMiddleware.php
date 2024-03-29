<?php

namespace App\Http\Middleware;

use Closure;

class VendorMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (auth()->user()->is_admin || auth()->user()->vendor) {
			return $next($request);
		} else {
			abort(403);
		}
	}
}
