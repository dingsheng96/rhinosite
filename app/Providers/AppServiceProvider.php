<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Settings\Country\Country;

class AppServiceProvider extends ServiceProvider
{
    protected $custom_facades = [
        'countryFacade' => \App\Support\Services\CountryService::class
    ];

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

        // bind custom facades
        $this->bindFacades();

        // observers
        $this->registerObservers();
    }

    private function bindFacades()
    {
        foreach ($this->custom_facades as $facade => $class) {
            $this->app->bind($facade, function () use ($class) {
                return new $class();
            });
        }
    }

    private function registerObservers()
    {
        \App\Models\Settings\Country\Country::observe(\App\Observers\CountryObserver::class);
        \App\Models\Settings\Country\CountryState::observe(\App\Observers\CountryStateObserver::class);
    }
}
