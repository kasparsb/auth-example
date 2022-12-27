<?php

namespace App\Auth;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;

use Accounts;

class AccountAuthProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Check if account is approved and type=tablet
        Auth::viaRequest('account_tablet', function ($request) {

            return \App\Auth\AccountGuard::getAccountTablet();

        });

        Auth::viaRequest('account_admin', function ($request) {

            return \App\Auth\AccountGuard::getAccountAdmin();

        });

        Auth::provider('users_text_driver', function ($app, array $config) {
            return new \App\Auth\TextUserProvider();
        });

        // add custom guard
        Auth::extend('account_guard_driver', function ($app, $name, array $config) {
            return new \App\Auth\AccountGuard(
                Auth::createUserProvider($config['provider']),
                $app->make('request')
            );
        });
    }
}
