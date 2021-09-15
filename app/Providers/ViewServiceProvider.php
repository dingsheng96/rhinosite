<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    protected $composers = [
        \App\Http\View\Composers\CountryComposer::class => [
            'admin.project.*', 'admin.merchant.*', 'admin.member.*',
            'merchant.auth.register', 'merchant.account', 'merchant.verification.*', 'merchant.project.*',
            'merchant.checkout.recurring', 'auth.*', 'member.account'
        ],
        \App\Http\View\Composers\UnitComposer::class => [
            'admin.project.*', 'merchant.project.*'
        ],
        \App\Http\View\Composers\MerchantComposer::class => [
            'admin.project.*'
        ],
        \App\Http\View\Composers\VerificationComposer::class => [
            'admin.*',
        ],
        \App\Http\View\Composers\DefaultPreviewComposer::class => [
            '*'
        ],
        \App\Http\View\Composers\CurrencyComposer::class => [
            'admin.package.*', 'admin.product.attribute.*', 'admin.currency.*',
            'admin.project.*', 'merchant.project.*'
        ],
        \App\Http\View\Composers\CartComposer::class => [
            'merchant.cart.index', 'merchant.checkout.*'
        ],
        \App\Http\View\Composers\ServiceComposer::class => [
            'app.*', 'auth.*', 'admin.auth.*', 'merchant.auth.*'
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
