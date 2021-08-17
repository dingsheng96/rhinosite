<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    protected $composers = [
        \App\Http\View\Composers\CountryComposer::class => [
            'projects.create', 'projects.edit', 'merchant.edit', 'merchant.create',
            'account.merchant', 'verification.create', 'auth.register', 'account.member',
            'checkout.recurring', 'member.edit', 'member.create'
        ],
        \App\Http\View\Composers\UnitComposer::class => [
            'projects.create', 'projects.edit'
        ],
        \App\Http\View\Composers\MerchantComposer::class => [
            'projects.create', 'projects.edit'
        ],
        \App\Http\View\Composers\VerificationComposer::class => [
            'account.*', 'activity_log.*', 'admin.*', 'ads.*', 'cart.*',
            'country.*', 'currency.*', 'dashboard.admin', 'member.*', 'merchant.*',
            'order.*', 'package.*', 'role.*', 'service.*', 'product.*', 'projects.*',
            'transaction.*', 'verification.*', 'wishlist.*', 'subscription.create'
        ],
        \App\Http\View\Composers\DefaultPreviewComposer::class => [
            '*'
        ],
        \App\Http\View\Composers\CurrencyComposer::class => [
            'projects.create', 'projects.edit', 'package.create', 'package.edit',
            'product.attribute.create', 'product.attribute.edit'
        ],
        \App\Http\View\Composers\CartComposer::class => [
            'cart.index', 'checkout.index', 'checkout.recurring'
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
