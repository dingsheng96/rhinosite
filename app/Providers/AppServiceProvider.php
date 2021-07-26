<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $custom_facades = [
        'countryFacade' => \App\Support\Services\CountryService::class,
        'userDetailFacade' => \App\Support\Services\UserDetailService::class,
        'projectFacade' => \App\Support\Services\ProjectService::class,
        'merchantFacade' => \App\Support\Services\MerchantService::class,
        'ratingFacade' => \App\Support\Services\RatingService::class,
        'priceFacade' => \App\Support\Services\PriceService::class,
        'productFacade' => \App\Support\Services\ProductService::class,
        'packageFacade' => \App\Support\Services\PackageService::class,
        'productAttributeFacade' => \App\Support\Services\ProductAttributeService::class,
        'currencyFacade' => \App\Support\Services\CurrencyService::class,
        'orderFacade' => \App\Support\Services\OrderService::class,
        'transactionFacade' => \App\Support\Services\TransactionService::class,
        'cartFacade' => \App\Support\Services\CartService::class,
        'accountFacade' => \App\Support\Services\AccountService::class,
        'verificationFacade' => \App\Support\Services\VerificationService::class,
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
        \App\Models\Project::observe(\App\Observers\ProjectObserver::class);
        \App\Models\Country::observe(\App\Observers\CountryObserver::class);
        \App\Models\CountryState::observe(\App\Observers\CountryStateObserver::class);
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
    }
}
