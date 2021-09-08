<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use App\Helpers\Domain;
use App\Models\Product;
use App\Models\ProductCategory;
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
        ['web' => $web, 'prefix' => $prefix] = (new Domain())->getConfig();

        if ($prefix) {
            $this->prefixRoutes($web);
        } else {
            $this->domainRoutes($web);
        }

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

    protected function prefixRoutes(array $web)
    {
        foreach ($web as $value) {

            Route::prefix($value['prefix'])
                ->middleware('web')
                ->namespace($this->namespace . '\\' . $value['namespace'])
                ->name($value['route']['name'] . '.')
                ->group(base_path('routes/web/' . $value['route']['file']));
        }
    }

    protected function domainRoutes(array $web)
    {
        foreach ($web as $value) {

            Route::domain($value['url'])
                ->middleware('web')
                ->namespace($this->namespace . '\\' . $value['namespace'])
                ->name($value['route']['name'] . '.')
                ->group(base_path('routes/web/' . $value['route']['file']));
        }
    }

    protected function routeBindings()
    {
        Route::bind('admin', function ($value) {
            return User::where('id', $value)->admin()->firstOrFail();
        });

        Route::bind('merchant', function ($value) {
            return User::where('id', $value)->merchant()->firstOrFail();
        });

        Route::bind('member', function ($value) {
            return User::where('id', $value)->member()->firstOrFail();
        });

        Route::bind('ads', function ($value) {
            return Product::where('id', $value)
                ->whereHas('productCategory', function ($query) {
                    $query->where('name', ProductCategory::TYPE_ADS);
                })
                ->firstOrFail();
        });
    }
}
