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
        Route::get('logs', 'ActivityLogController@getAllLog');

        Route::get('groups/search/', 'GroupController@getGroupBySearchName');
        Route::get('groups/get_waiting_approve', 'GroupController@getWaitingApproveRequestByGroups');
        Route::get('groups/get_all_group_by_level', 'GroupController@getAllGroupByLevel');
        Route::resource('groups', 'GroupController');
        Route::get('groups/{id}/user_with_per', 'GroupController@getUserWithPer');
        Route::get('groups/{groupId}/information', 'GroupController@getInfomationGroup');
        Route::get('groups/{groupId}/child_groups', 'GroupController@getChildGroupsInfor');
        Route::get('groups/{groupId}/parents', 'GroupController@getParentByGroupId');
        Route::delete('groups/{groupId}/delete_user/{userId}', 'GroupController@deleteUserFromGroup');
        Route::get('groups/{code}/informationbycode', 'GroupController@getGroupByCode');
        Route::get('groups/{groupId}/process/quarter/{quarterId}', 'GroupController@getProcessByGroupId');
        Route::get('groups/{groupId}/logs', 'ActivityLogController@getLogsByGroupId');
        Route::post('groups/{groupId}/add_member', 'GroupController@addMemberGroup');
        Route::get('groups/{groupId}/check_admin/{userId}', 'GroupController@checkAdminGroup');
        Route::get('groups/{groupId}/get_request_link', 'GroupController@getLinkRequest');
        Route::get('groups/{groupId}/quarter/{quarterId}/tracking', 'GroupController@getTracking');

        Route::resource('groups.objectives', 'ObjectiveController');
        Route::get('groups/{groupId}/objectives/{objectiveId}/detail_objective', 'ObjectiveController@showObjective');
        Route::get('groups/{groupId}/objectives/{keyResultId}/detail_key_result', 'ObjectiveController@showKeyResult');
        Route::post('groups/{groupId}/objectives/link_objective', 'ObjectiveController@linkObjective');
        Route::get('groups/{groupId}/objectives/{objectiveId}/log', 'ObjectiveController@logObjective');
        Route::patch('groups/{groupId}/objectives/{id}/match_actual', 'ObjectiveController@matchActualWithEstimate');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/name', 'ObjectiveController@updateName');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/weight', 'ObjectiveController@updateWeight');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/target', 'ObjectiveController@updateTargetVsUnit');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/verify_link', 'ObjectiveController@verifyLinkObjective');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/verify_all', 'ObjectiveController@verifyAllLinkRequest');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/remove_link', 'ObjectiveController@removeLinkObjective');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/remove_link_accepted', 'ObjectiveController@removeLinkObjectiveAccepted');
        Route::patch('groups/{groupId}/objectives/{objectiveId}/remove_all', 'ObjectiveController@removeAllLinkRequest');

        Route::resource('quarters', 'QuarterController');

        Route::resource('units', 'UnitController');

        Route::resource('objectives.comments', 'CommentController');

        Route::resource('users', 'UserController');

        Route::get('firebase/send/{userId}', 'FirebaseController@send');
        Route::resource('firebase', 'FirebaseController');
    });

    Route::post('webhook/wsm', 'WebhookController@handleWebhookWSM')->middleware('webhookWSM');
});
