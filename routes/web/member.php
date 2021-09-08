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

Route::group(['middleware' => ['auth:' . User::TYPE_MEMBER, 'verified']], function () {

    Route::get('dashboard', 'HomeController@index')->name('dashboard');

    Route::resource('account', 'AccountController');

    Route::get('comparisons', 'AppController@compareList')->name('comparisons.index');
    Route::post('comparisons', 'AppController@addToCompareList')->name('comparisons.store');

    Route::post('rating', 'AppController@rateUser')->name('ratings.store');

    Route::resource('wishlist', 'WishlistController')->only(['index', 'store']);
});
