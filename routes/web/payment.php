<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'payment', 'as' => 'payment.'], function () {

    Route::get('redirect', 'PaymentController@redirect')->name('redirect');
    Route::post('response', 'PaymentController@response')->name('response');
    Route::post('backend', 'PaymentController@backend')->name('backend');

    Route::post('recurring/response', 'PaymentController@recurringResponse')->name('recurring.response');
    Route::post('recurring/backend', 'PaymentController@recurringBackend')->name('recurring.backend');

    Route::get('status', 'PaymentController@paymentStatus')->name('status');
});
