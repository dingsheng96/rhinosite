<?php

use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Notifications\AccountVerified;
use App\Support\Facades\UserDetailFacade;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth:web', 'verified'])->group(function () {

    Route::get('dashboard', 'HomeController@index')->name('dashboard');

    Route::delete('projects/{project}/prices/{price}', 'ProjectController@deletePrice')->name('projects.price.destroy');
    Route::delete('projects/{project}/media/{media}', 'ProjectController@deleteMedia')->name('projects.media.destroy');
    Route::resource('projects', 'ProjectController');

    Route::resource('ads', 'AdsController');

    Route::resource('verifications', 'VerificationController');

    Route::group(['prefix' => 'users', 'as' => 'users.', 'namespace' => 'Users'], function () {
        Route::resource('admins', 'AdminController');
        Route::resource('members', 'MemberController');
        Route::resource('merchants', 'MerchantController');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.', 'namespace' => 'Settings'], function () {

        Route::resource('roles', 'RoleController');
        Route::resource('categories', 'CategoryController');
        Route::resource('currencies', 'CurrencyController');
        Route::resource('countries', 'CountryController');
        Route::resource('countries.country-states', 'CountryStateController');
        Route::resource('countries.country-states.cities', 'CityController');
        Route::resource('activity-logs', 'ActivityLogController');
    });

    Route::group(['prefix' => 'ecommerce', 'as' => 'ecommerce.', 'namespace' => 'Ecommerce'], function () {

        Route::delete('products/{product}/media/{media}', 'ProductController@deleteMedia')->name('products.media.destroy');
        Route::post('products/{product}/attributes/{attribute}/prices', 'ProductAttributeController@storePrice')->name('products.attributes.prices.store');
        Route::put('products/{product}/attributes/{attribute}/prices/{price}', 'ProductAttributeController@updatePrice')->name('products.attributes.prices.update');
        Route::delete('products/{product}/attributes/{attribute}/prices/{price}', 'ProductAttributeController@deletePrice')->name('products.attributes.prices.destroy');

        Route::resource('products', 'ProductController');
        Route::resource('products.attributes', 'ProductAttributeController');

        Route::delete('packages/{package}/products/{product}', 'PackageController@deletePackageProduct')->name('packages.products.destroy');
        Route::resource('packages', 'PackageController');

        Route::resource('orders', 'OrderController');
    });
});

Route::group(['prefix' => 'data', 'as' => 'data.'], function () {

    Route::group(['prefix' => 'countries/{country}', 'as' => 'countries.'], function () {
        Route::get('country-states', 'DataController@getCountryStateFromCountry')->name('country-states');
        Route::get('country-states/{country_state}/cities', 'DataController@getCityFromCountryState')->name('country-states.cities');
    });

    Route::get('products/{product}/sku', 'DataController@getSkuFromProduct')->name('products.sku');

    Route::get('ads-boosters/{ads}/available-date', 'DataController@getAdsBoosterAvailableDate')->name('ads-boosters.available-date');
});
