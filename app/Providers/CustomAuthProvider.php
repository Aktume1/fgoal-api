<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Auth\CustomUser;

class CustomAuthProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->extend('custom', function () {
            return new CustomUser();
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
