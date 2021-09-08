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
    return redirect()->route('app.home');
});

Auth::routes();

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
});

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

Route::group(['prefix' => 'payment', 'as' => 'payment.'], function () {

    Route::get('redirect', 'PaymentController@redirect')->name('redirect');
    Route::post('response', 'PaymentController@response')->name('response');
    Route::post('backend', 'PaymentController@backend')->name('backend');

    Route::post('recurring/response', 'PaymentController@recurringResponse')->name('recurring.response');
    Route::post('recurring/backend', 'PaymentController@recurringBackend')->name('recurring.backend');

    Route::get('status', 'PaymentController@paymentStatus')->name('status');
});

Route::get('set_user_type', function () {

    $users = User::with('roles')->get();

    foreach ($users as $user) {

        $role_name = $user->roles->first()->name;

        if ($role_name == 'Super Admin') {
            $type = User::TYPE_ADMIN;
        } elseif ($role_name == 'Merchant') {
            $type = User::TYPE_MERCHANT;
        } elseif ($role_name == 'Member') {
            $type = User::TYPE_MEMBER;
        }

        $user->type = $type;

        if ($user->isDirty()) {
            $user->save();
        }
    }

    return 'Done migrate';
});
