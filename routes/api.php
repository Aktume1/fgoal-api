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
        Route::get('groups/search/', 'GroupController@getGroupBySearchName');
        Route::get('groups/get_waiting_approve', 'GroupController@getWaitingApproveRequestByGroups');
        Route::resource('groups', 'GroupController');
        Route::get('groups/{id}/user_with_per', 'GroupController@getUserWithPer');
        Route::get('groups/{groupId}/information', 'GroupController@getInfomationGroup');
        Route::get('groups/{groupId}/child_groups', 'GroupController@getChildGroupsInfor');
        Route::get('groups/{groupId}/parents', 'GroupController@getParentByGroupId');
        Route::delete('groups/{groupId}/delete_user/{userId}', 'GroupController@deleteUserFromGroup');
        Route::get('groups/{code}/informationbycode', 'GroupController@getGroupByCode');
        Route::get('groups/{groupId}/process/quarter/{quarterId}', 'GroupController@getProcessByGroupId');
        Route::get('groups/{groupId}/log', 'GroupController@getLogGroup');
        Route::post('groups/{groupId}/add_member', 'GroupController@addMemberGroup');
        Route::get('groups/{groupId}/logs', 'GroupController@getLogsGroup');
        Route::get('groups/{groupId}/check_admin/{userId}', 'GroupController@checkAdminGroup');
        Route::get('groups/{groupId}/get_request_link', 'GroupController@getLinkRequest');
        Route::get('groups/{groupId}/quarter/{quarterId}/tracking', 'GroupController@getTracking');

        Route::resource('groups.objectives', 'ObjectiveController');
        Route::get('groups/{groupId}/objectives/{objectiveId}/detail', 'ObjectiveController@showObjective');
        Route::post('groups/{groupId}/objectives/link_objective', 'ObjectiveController@linkObjective');
        Route::get('groups/{groupId}/objectives/{objectiveId}/log', 'ObjectiveController@logObjective');
        Route::patch('groups/{groupId}/objectives/{id}/match_actual', 'ObjectiveController@matchActualWithEstimate');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/name', 'ObjectiveController@updateContent');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/verify_link', 'ObjectiveController@verifyLinkObjective');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/verify_all', 'ObjectiveController@verifyAllLinkRequest');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/remove_link', 'ObjectiveController@removeLinkObjective');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/remove_link_accepted', 'ObjectiveController@removeLinkObjectiveAccepted');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/remove_all', 'ObjectiveController@removeAllLinkRequest');

        Route::resource('quarters', 'QuarterController');

        Route::resource('units', 'UnitController');

        Route::resource('objectives.comments', 'CommentController');

        Route::resource('users', 'UserController');
    });

    Route::post('webhook/wsm', 'WebhookController@handleWebhookWSM')->middleware('webhookWSM');
});
