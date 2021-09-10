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

Auth::routes(['verify' => false, 'reset' => false, 'register' => false]);

Route::group(['middleware' => ['auth:' . User::TYPE_ADMIN]], function () {

    Route::get('dashboard', 'HomeController@index')->name('dashboard');

    Route::resource('account', 'AccountController')->only(['index', 'store']);

    Route::resource('ads-boosters', 'AdsBoosterController');

    Route::resource('verifications', 'UserVerificationController')->except(['create', 'store']);

    Route::post('subscriptions/{subscription}/terminate', 'SubscriptionController@terminate')->name('subscriptions.terminate');
    Route::resource('subscriptions', 'SubscriptionController')->only(['create', 'store']);

    Route::resource('projects', 'ProjectController');
    Route::resource('projects.media', 'ProjectMediaController')->only(['destroy']);

    Route::resource('products', 'ProductController');
    Route::resource('products.media', 'ProductMediaController');
    Route::resource('products.attributes', 'ProductAttributeController')->except(['index']);

    Route::resource('packages', 'PackageController');
    Route::resource('packages.products', 'PackageProductController')->only(['destroy']);

    Route::resource('orders', 'OrderController')->only(['index', 'store', 'show']);

    Route::resource('transactions', 'TransactionController')->only(['index', 'show']);

    Route::resource('admins', 'AdminController');

    Route::resource('members', 'MemberController');

    Route::resource('merchants', 'MerchantController');

    Route::resource('roles', 'RoleController');

    Route::resource('services', 'ServiceController')->except(['create', 'edit']);

    Route::resource('currencies', 'CurrencyController');

    Route::resource('countries', 'CountryController')->except(['create']);
    Route::resource('countries.country-states', 'CountryStateController')->except(['index', 'create']);
    Route::resource('countries.country-states.cities', 'CityController')->only(['store', 'destroy']);
});
