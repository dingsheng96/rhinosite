<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth:web', 'verified']], function () {

    Route::get('verifications/notify', 'VerificationController@notify')->name('verifications.notify');

    Route::resource('verifications', 'VerificationController');

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

    Route::group(['prefix' => 'data', 'as' => 'data.'], function () {

        Route::post('products/{product}/sku', 'DataController@getSkuFromProduct')->name('products.sku');
        Route::post('ads/{ads}/date', 'DataController@getAdsAvailableDate')->name('ads.date');
        Route::group(['prefix' => 'countries/{country}', 'as' => 'countries.'], function () {
            Route::post('country-states', 'DataController@getCountryStateFromCountry')->name('country-states');
            Route::post('country-states/{country_state}/cities', 'DataController@getCityFromCountryState')->name('country-states.cities');
        });
    });

    Route::group(['prefix' => 'payment/{trans_no}', 'as' => 'payment.'], function () {

        Route::get('redirect', 'PaymentController@redirect')->name('redirect');
        Route::post('response', 'PaymentController@response')->name('response');
        Route::post('backend', 'PaymentController@backendResponse')->name('backend');
        Route::get('status', 'PaymentController@paymentStatus')->name('status');
    });
});
