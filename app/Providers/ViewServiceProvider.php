<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    protected $composers = [
        \App\Http\View\Composers\CountryComposer::class => [
            'admin.projects.*', 'admin.merchant.*', 'admin.member.*',
            'admin.verification.*', 'merchant.auth.register', 'merchant.account',
            'checkout.recurring', 'merchant.account', 'auth.*', 'member.*'
        ],
        \App\Http\View\Composers\UnitComposer::class => [
            'admin.projects.*', 'merchant.projects.*'
        ],
        \App\Http\View\Composers\MerchantComposer::class => [
            'admin.projects.*'
        ],
        \App\Http\View\Composers\VerificationComposer::class => [
            'admin.*',
        ],
        \App\Http\View\Composers\DefaultPreviewComposer::class => [
            '*'
        ],
        \App\Http\View\Composers\CurrencyComposer::class => [
            'admin.package.*', 'admin.product.attribute.*', 'admin.currency.*',
            'admin.projects.*', 'merchant.projects.*'
        ],
        \App\Http\View\Composers\CartComposer::class => [
            'cart.index', 'checkout.*'
        ],
        \App\Http\View\Composers\ServiceComposer::class => [
            'app.*', 'auth.login', 'auth.register'
        ],
    ];

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
        foreach ($this->composers as $composer => $views) {
            view()->composer($views, $composer);
        }
    }
}
