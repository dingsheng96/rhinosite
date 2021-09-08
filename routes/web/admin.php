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
    return redirect()->route('admin.login');
});

Auth::routes(['verify' => false, 'reset' => false]);

Route::group(['middleware' => ['auth:' . User::TYPE_ADMIN]], function () {

    Route::resource('verifications', 'UserVerificationController');

    Route::post('subscriptions/{subscription}/terminate', 'SubscriptionController@terminate')->name('subscriptions.terminate');
    Route::resource('subscriptions', 'SubscriptionController');

    Route::get('dashboard', 'HomeController@index')->name('dashboard');

    Route::resource('account', 'AccountController');

    Route::resource('ads-boosters', 'AdsBoosterController');

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
});
