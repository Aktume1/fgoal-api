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
        Route::get('groups/{id}/user_with_per', 'GroupController@getUserWithPer');
        Route::get('groups/{groupId}/infomation', 'GroupController@getInfomationGroup');
        Route::get('groups/{groupId}/parents', 'GroupController@getParentByGroupId');
        Route::delete('groups/{groupId}/{userId}/delete_user', 'GroupController@deleteUserFromGroup');

        Route::resource('groups.objectives', 'ObjectiveController');
        Route::post('groups/{groupId}/objectives/link_objective', 'ObjectiveController@linkObjective');
        Route::get('objectives/{id}/log', 'ObjectiveController@logObjective');
        Route::patch('groups/{groupId}/objectives/{id}/match_actual', 'ObjectiveController@matchActualWithEstimate');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/name', 'ObjectiveController@updateContent');

        Route::resource('quarters', 'QuarterController');
        Route::resource('units', 'UnitController');
        
        Route::resource('objectives.comment', 'CommentController');
        Route::get('objectives/{objectiveId}/comments', 'CommentController@getCommentsObjective');

        Route::post('webhook/wsm', 'WebhookController@handleWebhookWSM')->middleware('webhookWSM');
    });
});
