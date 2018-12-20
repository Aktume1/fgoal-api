<?php

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

Route::group(['namespace' => 'Cms'], function () {
    Auth::routes();

    Route::group(['namespace' => 'Auth'], function () {
        Route::get('login/framgia', 'LoginController@redirectToProvider')->name('framgia.login');
        Route::get('login/framgia/callback', 'LoginController@handleProviderCallback');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'HomeController@index')->name('dashboard');
        Route::resource('units', 'UnitController');
        Route::resource('users', 'UserController');
        Route::resource('quarters', 'QuarterController');
    });
});
