<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;

class LoginMiddleware
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
	if(!Auth::check()) {
            return redirect('/login');
        }

        return $next($request);
    }
}
