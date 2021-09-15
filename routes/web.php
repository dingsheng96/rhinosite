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

Auth::routes(['verify' => true]);

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

        Route::get('dashboard', 'HomeController@index')->name('dashboard');

        Route::resource('account', 'AccountController')->only(['index', 'store']);

        Route::resource('comparisons', 'CompareController')->only(['index', 'store']);

        Route::resource('ratings', 'RatingController')->only(['store']);

        Route::resource('wishlist', 'WishlistController')->only(['index', 'store']);
    });
});

require 'web/payment.php';
require 'web/general.php';

Route::get('user_type', function () {

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
