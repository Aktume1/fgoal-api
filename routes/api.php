<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'as' => 'api.v1.', 'namespace' => 'Api'], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', ['as' => 'login', 'uses' => 'LoginController@login']);
        Route::post('loginwsm', ['as' => 'loginwsm', 'uses' => 'LoginController@loginWithWsm']);
        Route::post('refresh_token', ['as' => 'login', 'uses' => 'LoginController@refreshToken']);
    });
    Route::group(['middleware' => 'fapi'], function () {
        Route::resource('groups', 'GroupController');
        Route::resource('groups.objectives', 'ObjectiveController');
        Route::resource('quarters', 'QuarterController');
        Route::resource('units', 'UnitController');
        Route::get('groups/{id}/parent_of_group', 'GroupController@getParentByGroupId');
        Route::get('groups/{userId}/parents_of_user', 'GroupController@getParentByUserId');
        Route::post('groups/{groupId}/objectives/link_objective', 'ObjectiveController@linkObjective');
    });
});
