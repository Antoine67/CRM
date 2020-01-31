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
    public function handle($request, Closure $next, $user_level)
    {
	if(!Auth::check() || Auth::user()->permission_level < $user_level) {
         abort(404);
		 return redirect('/');
	}
           
        return $next($request);
    }
}
