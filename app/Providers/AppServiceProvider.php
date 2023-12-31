<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $custom_facades = [
        'countryFacade' => \App\Support\Services\CountryService::class,
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
        'userSubscriptionFacade' => \App\Support\Services\UserSubscriptionService::class,
        'memberFacade' => \App\Support\Services\MemberService::class,
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
        // force http to https when not in local
        if ($this->app->environment() != 'local') {
            URL::forceScheme('https');
        }

        // set default string length
        Schema::defaultStringLength(255);

        // bind custom facades
        $this->bindFacades();
    }

    private function bindFacades()
    {
        foreach ($this->custom_facades as $facade => $class) {
            $this->app->bind($facade, function () use ($class) {
                return new $class();
            });
        }
    }
}
