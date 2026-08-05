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

        $this->app->singleton(\Laravel\Fortify\Contracts\LoginResponse::class, function ($app) {
            return new class implements \Laravel\Fortify\Contracts\LoginResponse {
                public function toResponse($request)
                {
                    $user = $request->user('web');

                    if ($user && $this->isVisaOfficer($user)) {
                        return redirect()->route('visa-office.dashboard');
                    }

                    if ($user && $this->isCustomer($user)) {
                        return redirect()->route('customer.dashboard');
                    }

                    return redirect()->route('admin.dashboard');
                }

                private function isVisaOfficer($user): bool
                {
                    return $user->hasRole('Visa Officer')
                        || in_array(strtolower((string) ($user->role ?? '')), ['visa_officer', 'visa office', 'visa officer'], true)
                        || in_array(strtolower((string) ($user->designation ?? '')), ['visa_officer', 'visa officer'], true);
                }

                private function isCustomer($user): bool
                {
                    return $user->hasRole('Customer')
                        || in_array(strtolower((string) ($user->role ?? '')), ['customer'], true)
                        || in_array(strtolower((string) ($user->designation ?? '')), ['customer'], true);
                }
            };
        });

        RedirectIfAuthenticated::redirectUsing(function ($request) {
            $user = $request->user('web');

            if ($user && (
                $user->hasRole('Visa Officer')
                || in_array(strtolower((string) ($user->role ?? '')), ['visa_officer', 'visa office', 'visa officer'], true)
                || in_array(strtolower((string) ($user->designation ?? '')), ['visa_officer', 'visa officer'], true)
            )) {
                return route('visa-office.dashboard');
            }

            if ($user && (
                $user->hasRole('Customer')
                || in_array(strtolower((string) ($user->role ?? '')), ['customer'], true)
                || in_array(strtolower((string) ($user->designation ?? '')), ['customer'], true)
            )) {
                return route('customer.dashboard');
            }

            return route('admin.dashboard');
        });
    }
}