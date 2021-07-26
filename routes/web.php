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
    return redirect()->route('app.home');
});

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::get('verifications/notify', 'UserVerificationController@notify')->name('verifications.notify');
    Route::get('verifications/resubmit', 'UserVerificationController@resubmit')->name('verifications.resubmit');
    Route::resource('verifications', 'UserVerificationController');

    Route::group(['middleware' => ['verified_merchant']], function () {

        Route::get('dashboard', 'HomeController@index')->name('dashboard');

        Route::resource('account', 'AccountController');

        Route::resource('carts', 'CartController');

        Route::resource('subscriptions', 'SubscriptionController')->only(['index', 'show']);

        Route::resource('checkout', 'CheckOutController')->only(['index', 'store']);

        Route::resource('ads', 'AdsController');

        Route::resource('projects', 'ProjectController');
        Route::resource('projects.media', 'ProjectMediaController')->only(['destroy']);

        Route::resource('products', 'ProductController');
        Route::resource('products.media', 'ProductMediaController');
        Route::resource('products.attributes', 'ProductAttributeController');

        Route::resource('packages', 'PackageController');
        Route::resource('packages.products', 'PackageProductController')->only(['destroy']);

        Route::resource('orders', 'OrderController');

        Route::resource('transactions', 'TransactionController');

        Route::resource('admins', 'AdminController');

        Route::resource('members', 'MemberController');

        Route::resource('merchants', 'MerchantController');

        Route::resource('roles', 'RoleController');

        Route::resource('services', 'ServiceController');

        Route::resource('currencies', 'CurrencyController');

        Route::resource('countries', 'CountryController');
        Route::resource('countries.country-states', 'CountryStateController');
        Route::resource('countries.country-states.cities', 'CityController');

        Route::resource('activity-logs', 'ActivityLogController');
    });

    Route::group(['prefix' => 'payment/{trans_no}', 'as' => 'payment.'], function () {

        Route::get('redirect', 'PaymentController@redirect')->name('redirect');
        Route::post('response', 'PaymentController@response')->name('response');
        Route::post('backend', 'PaymentController@backendResponse')->name('backend');
        Route::get('status', 'PaymentController@paymentStatus')->name('status');
    });
});

Route::group(['as' => 'app.'], function () {

    Route::get('/', 'AppController@home')->name('home');
    Route::get('about', 'AppController@about')->name('about');
    Route::get('partner', 'AppController@partner')->name('partner');
    Route::get('contact', 'AppController@contact')->name('contact');
    Route::get('terms', 'AppController@termsPolicies')->name('term');
    Route::get('privacy', 'AppController@privacyPolicies')->name('privacy');

    Route::get('project', 'AppController@project')->name('project.index');
    Route::get('project/{project}/details', 'AppController@showProject')->name('project.show');
    Route::get('project/{merchant}/profile', 'AppController@showMerchant')->name('merchant.show');

    Route::group(['middleware' => ['auth:web', 'verified']], function () {
        Route::get('comparisons', 'AppController@compareList')->name('comparisons.index');
        Route::post('comparisons', 'AppController@addToCompareList')->name('comparisons.store');
        Route::post('rating', 'AppController@rateUser')->name('ratings.store');
    });
});

Route::group(['prefix' => 'data', 'as' => 'data.'], function () {

    Route::group(['middleware' => ['auth:web', 'verified']], function () {
        Route::post('products/{product}/sku', 'DataController@getSkuFromProduct')->name('products.sku');
        Route::post('ads/{ads}/date', 'DataController@getAdsAvailableDate')->name('ads.date');
    });

    Route::group(['prefix' => 'countries/{country}', 'as' => 'countries.'], function () {
        Route::post('country-states', 'DataController@getCountryStateFromCountry')->name('country-states');
        Route::post('country-states/{country_state}/cities', 'DataController@getCityFromCountryState')->name('country-states.cities');
    });
});
