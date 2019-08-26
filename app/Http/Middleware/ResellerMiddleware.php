<?php

namespace App\Http\Middleware;

use Closure;

class ResellerMiddleware
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
			if (auth()->user()->is_reseller) {
				return $next($request);
			} else {
				abort(403);
			}
    }
}
