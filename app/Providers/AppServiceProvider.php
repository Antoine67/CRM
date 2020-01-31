<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Carbon\Carbon::setLocale(config('app.locale'));

	    Blade::if('editor', function () {
                // check if the user is authenticated, has editor rights and is currently willing to edit
                return (
                        Auth::check() &&
                        Auth::user()->permission_level >= env('EDITOR_LEVEL', 2) &&
                        Auth::user()->editor_mode
                );
        });

        Blade::if('admin', function () {
                // check if the user is authenticated and is administrator
                return (
                        Auth::check() &&
                        Auth::user()->permission_level >= env('ADMIN_LEVEL', 3)
                );
        });

    }
}
