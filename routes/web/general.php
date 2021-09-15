<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'data', 'as' => 'data.'], function () {

    Route::post('products/{product}/sku', 'DataController@getSkuFromProduct')->name('products.sku');
    Route::post('boosters/{ads}/unavailable-date', 'DataController@getAdsUnavailableDate')->name('ads.unavailable-date');
    Route::post('merchants/{merchant}/boosters-quota', 'DataController@getMerchantAdsQuota')->name('merchants.ads-quota');
    Route::post('merchants/{merchant}/projects', 'DataController@getMerchantProjects')->name('merchants.projects');

    Route::group(['prefix' => 'countries/{country}', 'as' => 'countries.'], function () {
        Route::post('country-states', 'DataController@getCountryStateFromCountry')->name('country-states');
        Route::post('country-states/{country_state}/cities', 'DataController@getCityFromCountryState')->name('country-states.cities');
    });
});
