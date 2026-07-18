<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        Fortify::loginView(function () {
            return view('auth.login'); 
        });

       
        Fortify::registerView(function () {
            return view('auth.register'); 
        });

        RedirectIfAuthenticated::redirectUsing(function ($request) {
            if (Auth::guard('travel_agent')->check()) {
                return route('travel-agents.dashboard');
            }

            return route('dashboard');
        });
    }
}