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

Route::group(['as' => 'app.'], function () {
    Route::get('/', 'AppController@home')->name('home');
    Route::get('about', 'AppController@about')->name('about');
    Route::get('management', 'AppController@management')->name('management');
    Route::get('partner', 'AppController@partner')->name('partner');
    Route::get('contact', 'AppController@contact')->name('contact');
    Route::get('terms', 'AppController@termsPolicies')->name('term');
    Route::get('privacy', 'AppController@privacyPolicies')->name('privacy');

    Route::get('project', 'AppController@project')->name('project.index');
    Route::get('project/{project}/details', 'AppController@showProject')->name('project.show');
    Route::get('project/{merchant}/profile', 'AppController@showMerchant')->name('merchant.show');

    Route::group(['middleware' => ['auth:web', 'verified']], function () {

        Route::get('dashboard', 'HomeController@index')->name('dashboard');

        Route::resource('account', 'AccountController')->only(['index', 'store']);

        Route::resource('comparisons', 'CompareController')->only(['index', 'store']);

        Route::resource('ratings', 'RatingController')->only(['store']);

        Route::resource('wishlist', 'WishlistController')->only(['index', 'store']);
    });
    // manual update expired freetrial account to freetier (Please comment after use)
    // Route::get('/freetier', 'AppController@updateFreeTrialAccount');

    // require 'custom.php';
});

require 'web/general.php';
require 'web/payment.php';
