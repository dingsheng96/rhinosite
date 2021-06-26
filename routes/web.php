<?php

use App\Models\UserDetails;
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

    Route::delete('projects/{project}/media/{media}', 'ProjectController@deleteMedia')->name('projects.media.destroy');
    Route::resource('projects', 'ProjectController');

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

        Route::resource('products', 'ProductController');
    });
});

Route::group(['prefix' => 'data', 'as' => 'data.'], function () {

    Route::group(['prefix' => 'countries/{country}', 'as' => 'countries.'], function () {
        Route::get('country-states', 'DataController@getCountryStateFromCountry')->name('country-states');
        Route::get('country-states/{country_state}/cities', 'DataController@getCityFromCountryState')->name('country-states.cities');
    });

    Route::get('ads-boosters/{ads}/available-date', 'DateController@getAdsBoosterAvailableDate')->name('ads-boosters.available-date');
});
