<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    protected $composers = [
        \App\Http\View\Composers\CountryComposer::class => [
            'projects.create', 'projects.edit', 'merchant.edit', 'account.merchant'
        ],
        \App\Http\View\Composers\UnitComposer::class => [
            'projects.create', 'projects.edit'
        ],
        \App\Http\View\Composers\MerchantComposer::class => [
            'projects.create', 'projects.edit'
        ],
        \App\Http\View\Composers\VerificationComposer::class => [
            '*'
        ],
        \App\Http\View\Composers\DefaultPreviewComposer::class => [
            '*'
        ],
        \App\Http\View\Composers\CurrencyComposer::class => [
            '*'
        ],
        \App\Http\View\Composers\CartComposer::class => [
            'cart.index', 'checkout.index'
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
