<?php

namespace App\Providers;

use App\Models\Settings\Role\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
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
        Blade::if('admin', function () {
            return Auth::user()->role_name == Role::ROLE_SUPER_ADMIN;
        });

        Blade::if('merchant', function () {
            return Auth::user()->role_name == Role::ROLE_MERCHANT;
        });
    }
}
