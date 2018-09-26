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
        Route::get('groups/{groupId}/information', 'GroupController@getInfomationGroup');
        Route::get('groups/{groupId}/parents', 'GroupController@getParentByGroupId');
        Route::delete('groups/{groupId}/{userId}/delete_user', 'GroupController@deleteUserFromGroup');
        Route::get('groups/{code}/informationbycode', 'GroupController@getGroupByCode');
        Route::get('groups/{groupId}/process', 'GroupController@getProcessByGroupId');
        Route::get('groups/{groupId}/log', 'GroupController@getLogGroup');
        Route::post('groups/{groupId}/add_member', 'GroupController@addMemberGroup');
        Route::get('groups/{groupId}/logs', 'GroupController@getLogsGroup');
        Route::get('groups/{groupId}/check_admin/{userId}', 'GroupController@checkAdminGroup');
        Route::get('groups/{groupId}/get_request_link', 'GroupController@getLinkRequest');

        Route::resource('groups.objectives', 'ObjectiveController');
        Route::get('groups/{groupId}/objectives/{objectiveId}/detail', 'ObjectiveController@showObjective');
        Route::post('groups/{groupId}/objectives/link_objective', 'ObjectiveController@linkObjective');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/remove_link', 'ObjectiveController@removeLinkObjective');
        Route::get('groups/{groupId}/objectives/{objectiveId}/log', 'ObjectiveController@logObjective');
        Route::patch('groups/{groupId}/objectives/{id}/match_actual', 'ObjectiveController@matchActualWithEstimate');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/name', 'ObjectiveController@updateContent');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/verify_link', 'ObjectiveController@verifyLinkObjective');

        Route::resource('quarters', 'QuarterController');

        Route::resource('units', 'UnitController');

        Route::resource('objectives.comment', 'CommentController');
        Route::get('objectives/{objectiveId}/comments', 'CommentController@getCommentsObjective');

        Route::resource('users', 'UserController');
    });

    Route::post('webhook/wsm', 'WebhookController@handleWebhookWSM')->middleware('webhookWSM');
});
