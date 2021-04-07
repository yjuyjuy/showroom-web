<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('language')) {
            if ($request->routeIs('language.*') || $request->is('storage/*')) {
                return $next($request);
            } else {
                return redirect(route('language.edit'));
            }
        }
        App::setLocale(session('language'));
        return $next($request);
    }
}
