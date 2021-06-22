<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    protected $composers = [
        \App\Http\View\Composers\CountryComposer::class => [
            'projects.admin.create', 'projects.admin.edit', 'projects.merchant.edit', 'projects.merchant.create'
        ],
        \App\Http\View\Composers\UnitComposer::class => [
            'projects.admin.create', 'projects.admin.edit', 'projects.merchant.edit', 'projects.merchant.create'
        ]
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
