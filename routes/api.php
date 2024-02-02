<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\TagApi\Queries as QueryTagApi;
use App\Http\Controllers\Api\AuthApi\Commands as CommandAuthApi;
use App\Http\Controllers\Api\AuthApi\Queries as QueryAuthApi;
use App\Http\Controllers\Api\ContentApi\CommandTask as CommandTaskApi;
use App\Http\Controllers\Api\ContentApi\QueryTask as QueryTaskApi;
use App\Http\Controllers\Api\UserApi\Queries as QueryUserApi;
use App\Http\Controllers\Api\UserApi\Commands as CommandUserApi;
use App\Http\Controllers\Api\HelpApi\Queries as QueryHelpApi;
use App\Http\Controllers\Api\HelpApi\Commands as CommandHelpApi;
use App\Http\Controllers\Api\GroupApi\Queries as QueryGroupApi;
use App\Http\Controllers\Api\ArchiveApi\Commands as CommandArchiveApi;
use App\Http\Controllers\Api\ArchiveApi\Queries as QueryArchiveApi;
use App\Http\Controllers\Api\ContentApi\CommandContent as CommandContentApi;
use App\Http\Controllers\Api\ContentApi\QueryContent as QueryContentApi;
use App\Http\Controllers\Api\SystemApi\QueryDictionary as QueryDictionaryApi;
use App\Http\Controllers\Api\SystemApi\QueryAccess as QueryAccessApi;
use App\Http\Controllers\Api\SystemApi\QueryNotification as QueryNotificationApi;
use App\Http\Controllers\Api\TrashApi\Queries as QueryTrashApi;
use App\Http\Controllers\Api\QuestionApi\Commands as CommandQuestionApi;
use App\Http\Controllers\Api\QuestionApi\Queries as QueryQuestionApi;
use App\Http\Controllers\Api\FeedbackApi\Commands as CommandFeedbackApi;
use App\Http\Controllers\Api\SystemApi\QueryInfo as QueryInfoApi;
use App\Http\Controllers\Api\SystemApi\QueryHistory as QueryHistoryApi;
use App\Http\Controllers\Api\AttendanceApi\Queries as QueryAttendanceApi;
use App\Http\Controllers\Api\AttendanceApi\Commands as CommandAttendanceApi;

######################### Public Route #########################

Route::post('/v1/login/{env}', [CommandAuthApi::class, 'login']);
Route::post('/v1/register', [CommandUserApi::class, 'register']);

Route::prefix('/v1/dictionaries')->group(function() {
    Route::get('/type/{dct_type}', [QueryDictionaryApi::class, 'getAllDictionaryByType']);
});

Route::prefix('/v1/help')->group(function() {
    Route::get('/{type}', [QueryHelpApi::class, 'getHelpCategoryByType']);
});

Route::prefix('/v1/info')->group(function() {
    Route::get('/page/{page}/location/{location}', [QueryInfoApi::class, 'getInfoPageAndLocation']);
});

Route::prefix('/v1/feedback')->group(function() {
    Route::post('/create', [CommandFeedbackApi::class, 'insertFeedback']);
});

Route::prefix('/v1/faq')->group(function() {
    Route::get('/question/{limit}', [QueryQuestionApi::class, 'getQuestion']);
    Route::get('/question/active/{limit}', [QueryQuestionApi::class, 'getActiveQuestion']);
    Route::get('/answer/like/{answer}', [QueryQuestionApi::class, 'getAnswerSuggestion'])->middleware(['auth:sanctum']);
    Route::get('/question', [QueryQuestionApi::class, 'getMyQuestions'])->middleware(['auth:sanctum']);
    Route::post('/question', [CommandQuestionApi::class, 'createQuestion'])->middleware(['auth:sanctum']);
});

Route::prefix('/v1/check')->group(function() {
    Route::post('/user', [CommandUserApi::class, 'check_user']);
    Route::post('/pass/recover', [CommandUserApi::class, 'request_recover_pass']);
    Route::post('/pass/validate', [CommandUserApi::class, 'validate_recover_pass']);
    Route::put('/pass/edit', [CommandUserApi::class, 'recover_pass']);
});

######################### Private Route #########################

Route::get('/v1/logout/{env}', [QueryAuthApi::class, 'logout'])->middleware(['auth:sanctum']);

Route::prefix('/v1/help')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/', [QueryHelpApi::class, 'getHelpType']);
    Route::post('/type', [CommandHelpApi::class, 'addHelpType']);
});

Route::prefix('/v1/task')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [QueryTaskApi::class, 'getMyTask']);
    Route::post('/create', [CommandTaskApi::class, 'addTask']);
    Route::put('/update/{id}', [CommandTaskApi::class, 'updateTask']);
    Route::delete('/delete/{id}', [CommandTaskApi::class, 'deleteTask']);
    Route::delete('/destroy/{id}', [CommandTaskApi::class, 'destroyTask']);
});

Route::prefix('/v1/tag')->group(function () {
    Route::get('/cat/{cat}/{limit}', [QueryTagApi::class, 'getAllTagByCat']);
    Route::get('/{find}/{limit}', [QueryTagApi::class, 'getAllTag']);
    Route::get('/{slug}', [QueryTagApi::class, 'getTotalTagUsed'])->middleware(['auth:sanctum']);
});

Route::prefix('/v1/notification')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/my', [QueryNotificationApi::class, 'getMyNotification']);
});

Route::prefix('/v1/content')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/my/order/{order}/find/{search}', [QueryContentApi::class, 'getMyContent']);
    Route::get('/slug/{slug}', [QueryContentApi::class, 'getContentBySlug']);
    Route::get('/date/{date}/{utc}', [QueryContentApi::class, 'getAllContentSchedule']);
    Route::get('/slug/{slug}/order/{order}/date/{date}/{utc}/find/{search}', [QueryContentApi::class, 'getContentHeader']); //*Tag slug
    Route::get('/order/{order}/find/{search}', [QueryContentApi::class, 'getFinishedContent']);
    Route::put('/edit/image/{slug}', [CommandContentApi::class, 'editContentImage']);
    Route::post('/create', [CommandContentApi::class, 'addContent']);
    Route::post('/open/{slug_name}', [CommandContentApi::class, 'addView']);
});

Route::prefix('/v1/archive')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/{slug}/type/{type}', [QueryArchiveApi::class, 'getArchive']);
    Route::get('/by/{slug}', [QueryArchiveApi::class, 'getContentByArchive']);
    Route::post('/create', [CommandArchiveApi::class, 'createArchive']);
    Route::post('/multirel/{slug}/{type}', [CommandArchiveApi::class, 'multiActionArchiveRelation']);
    Route::put('/edit/{slug}', [CommandArchiveApi::class, 'editArchive']);
    Route::delete('/delete/{slug}', [CommandArchiveApi::class, 'deleteArchive']);
});

Route::prefix('/v1/user')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/', [QueryUserApi::class, 'getMyProfile']);
    Route::get('/{filter_name}/limit/{limit}/order/{order}/slug/{slug}', [QueryUserApi::class, 'getUser']);
    Route::get('/{username}', [QueryUserApi::class, 'getUserDetail']);
    Route::get('/{username}/role', [QueryUserApi::class, 'getMyRole']);
    Route::get('/request/new/{fullname}', [QueryUserApi::class, 'getNewUserRequest']);
    Route::get('/request/old/{fullname}', [QueryUserApi::class, 'getOldUserRequest']);
    Route::get('/request/my', [QueryUserApi::class, 'getMyRequest']);
    Route::get('/request/dump', [QueryUserApi::class, 'getUserRejectedRequest']);
    Route::get('/access/history/{limit}', [QueryAccessApi::class, 'getAllPersonalAccessToken']);
    Route::put('/update/data', [CommandUserApi::class, 'editUserData']);
    Route::put('/update/image', [CommandUserApi::class, 'editUserImage']);
    Route::put('/update/token/{token}', [CommandUserApi::class, 'updateFirebaseToken']);
    Route::post('/update/role/add', [CommandUserApi::class, 'add_role']);
    Route::post('/update/role/remove', [CommandUserApi::class, 'remove_role']);
    Route::post('/request/role', [CommandUserApi::class, 'request_role_api']);
});

Route::prefix('/v1/history')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/my', [QueryHistoryApi::class, 'getMyHistory']);
});

Route::prefix('/v1/attendance')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/my', [QueryAttendanceApi::class, 'getAllAttendanceHeaders']);
    Route::delete('/destroy/{id}', [CommandAttendanceApi::class, 'destroyAttendance']);
    Route::post('/create', [CommandAttendanceApi::class, 'postAttendance']);
});

Route::prefix('/v1/group')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/limit/{limit}/order/{order}/find/{find}', [QueryGroupApi::class, 'getAllGroup']);
    Route::get('/member/{slug}/{filter_name}/limit/{limit}/order/{order}', [QueryGroupApi::class, 'getAvailableUserBySlug']);
    Route::get('/member/{slug}/{limit}', [QueryGroupApi::class, 'getGroupRelationBySlug']);
});

Route::prefix('/v1/trash')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/order/{order}/cat/{category}/find/{search}', [QueryTrashApi::class, 'getAllContentTrash']);
});

