<?php

namespace App\Http\Middleware;

use Closure;

class RetailerMiddleware
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
			if (auth()->user()->vendor->retailer) {
				return $next($request);
			} else {
				abort(403);
			}
    }
}
