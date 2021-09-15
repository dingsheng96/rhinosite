<?php

use App\Models\User;
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
    return redirect()->route('merchant.login');
});

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth:' . User::TYPE_MERCHANT, 'verified:merchant.verification.notice']], function () {

    Route::get('verifications/notify', 'UserVerificationController@notify')->name('verifications.notify');
    Route::get('verifications/resubmit', 'UserVerificationController@resubmit')->name('verifications.resubmit');
    Route::resource('verifications', 'UserVerificationController')->only(['create', 'store']);

    Route::post('subscriptions/{subscription}/terminate', 'SubscriptionController@terminate')->name('subscriptions.terminate');
    Route::resource('subscriptions', 'SubscriptionController');

    Route::resource('carts', 'CartController');

    Route::group(['prefix' => 'checkout', 'as' => 'checkout.'], function () {
        Route::get('/', 'CheckOutController@index')->name('index');
        Route::post('/', 'CheckOutController@store')->name('store');
        Route::post('/recurring', 'CheckOutController@recurring')->name('recurring');
    });

    Route::group(['middleware' => ['verified_merchant']], function () {

        Route::get('dashboard', 'HomeController@index')->name('dashboard');

        Route::resource('account', 'AccountController')->only(['index', 'store']);

        Route::resource('ads-boosters', 'AdsBoosterController');

        Route::resource('projects', 'ProjectController');
        Route::resource('projects.media', 'ProjectMediaController')->only(['destroy']);

        Route::resource('products', 'ProductController');
        Route::resource('products.attributes', 'ProductAttributeController');

        Route::resource('orders', 'OrderController')->only(['index', 'store', 'show']);

        Route::resource('transactions', 'TransactionController');
    });
});

Route::get('payment.status', 'PaymentController@paymentStatus')->name('payment.status');

require 'general.php';
