<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use App\Models\Transaction;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        $this->routeBindings();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware(['web'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function routeBindings()
    {
        Route::bind('admin', function ($value) {
            return User::where('id', $value)
                ->whereHas('roles', function ($query) {
                    $query->where('name', Role::ROLE_SUPER_ADMIN);
                })
                ->firstOrFail();
        });

        Route::bind('merchant', function ($value) {
            return User::where('id', $value)
                ->whereHas('roles', function ($query) {
                    $query->where('name', Role::ROLE_MERCHANT);
                })
                ->firstOrFail();
        });

        Route::bind('member', function ($value) {
            return User::where('id', $value)
                ->whereHas('roles', function ($query) {
                    $query->where('name', Role::ROLE_MEMBER);
                })
                ->firstOrFail();
        });

        Route::bind('ads', function ($value) {
            return ProductAttribute::where('id', $value)
                ->whereHas('productCategory', function ($query) {
                    $query->where(app(ProductCategory::class)->getTable() . '.name', ProductCategory::TYPE_ADS);
                })
                ->firstOrFail();
        });
    }
}
