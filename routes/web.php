<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:web'])->group(function () {

    Route::get('dashboard', 'HomeController@index')->name('dashboard');

    Route::group(['prefix' => 'users', 'as' => 'users.', 'namespace' => 'Users'], function () {
        Route::resource('admins', 'AdminController');
        Route::resource('merchants', 'MerchantController');
        Route::resource('members', 'MemberController');
        Route::resource('registrations', 'RegistrationController');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.', 'namespace' => 'Settings'], function () {

        Route::group(['namespace' => 'Country'], function () {
            Route::resource('countries', 'CountryController');
            Route::resource('countries.country-states', 'CountryStateController');
            Route::resource('countries.country-states.cities', 'CityController');
        });

        Route::resource('currencies', 'CurrencyController');
        Route::resource('roles', 'RoleController');
    });
});
