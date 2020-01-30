<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;

class UserLevelMiddleware
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
	if(!Auth::check() || Auth::user()->permission_level < 2) {
		 return redirect('/');
	}
           
        return $next($request);
    }
}
