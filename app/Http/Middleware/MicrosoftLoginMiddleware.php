<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class MicrosoftLoginMiddleware
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
	if(!Session::has('user') || !Session::has('access_token')) {
            return redirect('/login');
        }

        return $next($request);
    }
}
