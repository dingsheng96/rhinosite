<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        // set default string length
        Schema::defaultStringLength(255);

        // force http to https when not in local
        $this->app['request']->server
            ->set('HTTPS', $this->app->environment() != 'local');
    }
}
