<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\TagApi\Commands as CommandTagApi;
use App\Http\Controllers\Api\TagApi\Queries as QueryTagApi;
use App\Http\Controllers\Api\AuthApi\Commands as CommandAuthApi;
use App\Http\Controllers\Api\AuthApi\Queries as QueryAuthApi;
use App\Http\Controllers\Api\ContentApi\CommandTask as CommandTaskApi;
use App\Http\Controllers\Api\ContentApi\QueryTask as QueryTaskApi;
use App\Http\Controllers\Api\UserApi\Queries as QueryUserApi;
use App\Http\Controllers\Api\UserApi\Commands as CommandUserApi;
use App\Http\Controllers\Api\HelpApi\Queries as QueryHelpApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GroupApi\Queries as QueryGroupApi;
use App\Http\Controllers\Api\ArchiveApi\Commands as CommandArchiveApi;
use App\Http\Controllers\Api\ArchiveApi\Queries as QueryArchiveApi;
use App\Http\Controllers\Api\ContentApi\CommandContent as CommandContentApi;
use App\Http\Controllers\Api\ContentApi\QueryContent as QueryContentApi;
use App\Http\Controllers\Api\SystemApi\QueryDictionary as QueryDictionaryApi;
use App\Http\Controllers\Api\SystemApi\QueryNotification as QueryNotificationApi;
use App\Http\Controllers\Api\TrashApi\Queries as QueryTrashApi;
use App\Http\Controllers\Api\QuestionApi\Commands as CommandQuestionApi;
use App\Http\Controllers\Api\QuestionApi\Queries as QueryQuestionApi;
use App\Http\Controllers\Api\FeedbackApi\Commands as CommandFeedbackApi;
use App\Http\Controllers\Api\FeedbackApi\Queries as QueryFeedbackApi;
use App\Http\Controllers\Api\SystemApi\QueryInfo as QueryInfoApi;

######################### Public Route #########################

Route::post('/v1/login', [CommandAuthApi::class, 'login']);

Route::prefix('/v1/dictionaries')->group(function() {
    Route::get('/', [QueryDictionaryApi::class, 'getAllDictionary']);
    Route::get('/type', [QueryDictionaryApi::class, 'getAllDictionaryType']);
    Route::get('/type/{dct_type}', [QueryDictionaryApi::class, 'getAllDictionaryByType']);
});

Route::prefix('/v1/help')->group(function() {
    Route::get('/', [QueryHelpApi::class, 'getHelpType']);
    Route::get('/{type}', [QueryHelpApi::class, 'getHelpCategoryByType']);
});

Route::prefix('/v1/info')->group(function() {
    Route::get('/', [QueryInfoApi::class, 'getAvailableInfoApi']);
});

Route::prefix('/v1/feedback')->group(function() {
    Route::post('/create', [CommandFeedbackApi::class, 'insertFeedback']);
    Route::get('/', [QueryFeedbackApi::class, 'getAllFeedbackSuggestionApi']);
});

Route::prefix('/v1/faq')->group(function() {
    Route::get('/question/{limit}', [QueryQuestionApi::class, 'getQuestion']);
    Route::get('/question/active/{limit}', [QueryQuestionApi::class, 'getActiveQuestion']);
    Route::get('/answer/{id}', [QueryQuestionApi::class, 'getAnswer']);
    Route::get('/answer/like/{answer}', [QueryQuestionApi::class, 'getAnswerSuggestion'])->middleware(['auth:sanctum']);
    Route::post('/question', [CommandQuestionApi::class, 'createQuestion'])->middleware(['auth:sanctum']);
});

######################### Private Route #########################

Route::get('/v1/logout', [QueryAuthApi::class, 'logout'])->middleware(['auth:sanctum']);

Route::prefix('/v1/task')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [QueryTaskApi::class, 'getMyTask']);
    Route::post('/create', [CommandTaskApi::class, 'addTask']);
    Route::put('/update/{id}', [CommandTaskApi::class, 'updateTask']);
    Route::delete('/delete/{id}', [CommandTaskApi::class, 'deleteTask']);
    Route::delete('/destroy/{id}', [CommandTaskApi::class, 'destroyTask']);
});

Route::prefix('/v1/tag')->group(function () {
    Route::get('/cat/{cat}/{limit}', [QueryTagApi::class, 'getAllTagByCat']);
    Route::get('/{limit}', [QueryTagApi::class, 'getAllTag'])->middleware(['auth:sanctum']);
    Route::post('/create', [CommandTagApi::class, 'addTag'])->middleware(['auth:sanctum']);
    Route::put('/update/{id}', [CommandTagApi::class, 'updateTag'])->middleware(['auth:sanctum']);
    Route::delete('/delete/{id}', [CommandTagApi::class, 'deleteTag'])->middleware(['auth:sanctum']);
    Route::delete('/destroy/{id}', [CommandTagApi::class, 'destroyTag'])->middleware(['auth:sanctum']);
});

Route::prefix('/v1/notification')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [QueryNotificationApi::class, 'getAllNotification']);
    Route::get('/my', [QueryNotificationApi::class, 'getMyNotification']);
});

Route::prefix('/v1/content')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/', [QueryContentApi::class, 'getContentHeader']);
    Route::get('/slug/{slug}', [QueryContentApi::class, 'getContentBySlug']);
    Route::get('/date/{date}', [QueryContentApi::class, 'getAllContentSchedule']);

    Route::delete('/delete/{id}', [CommandContentApi::class, 'deleteContent']);
    Route::delete('/destroy/{id}', [CommandContentApi::class, 'deleteContent']);
    Route::post('/create', [CommandContentApi::class, 'addContent']);
    Route::post('/open/{slug_name}/user/{user_slug}/role/{user_role}', [CommandContentApi::class, 'addView']);
    // Route::post('/open/{slug_name}/role/{user_role}', [ContentApi::class, 'addView']);
});

Route::prefix('/v2/content')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/slug/{slug}/order/{order}/date/{date}/find/{search}', [QueryContentApi::class, 'getContentBySlugLike']); //*Tag slug
    Route::get('/order/{order}/find/{search}', [QueryContentApi::class, 'getFinishedContent']);
});

Route::prefix('/v1/archive')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/', [QueryArchiveApi::class, 'getArchive']);
    Route::get('/{slug}', [QueryArchiveApi::class, 'getContentByArchive']);
    Route::post('/create', [CommandArchiveApi::class, 'createArchive']);
    Route::post('/createRelation', [CommandArchiveApi::class, 'addToArchive']);
    Route::put('/edit/{slug}', [CommandArchiveApi::class, 'editArchive']);
    Route::delete('/delete/{slug}', [CommandArchiveApi::class, 'deleteArchive']);
});

Route::prefix('/v1/user')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/', [QueryUserApi::class, 'getMyProfile']);
    Route::get('/{filter_name}/limit/{limit}/order/{order}', [QueryUserApi::class, 'getUser']);
    Route::get('/{username}', [QueryUserApi::class, 'getUserDetail']);
    Route::get('/request/new', [QueryUserApi::class, 'getNewUserRequest']);
    Route::get('/request/old', [QueryUserApi::class, 'getOldUserRequest']);
    Route::get('/request/dump', [QueryUserApi::class, 'getUserRejectedRequest']);
    Route::put('/update/data', [CommandUserApi::class, 'editUserData']);
    Route::put('/update/image', [CommandUserApi::class, 'editUserImage']);
    Route::post('/request/role', [CommandUserApi::class, 'request_role_api']);
});

Route::prefix('/v1/stats')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/mostviewed', [QueryContentApi::class, 'getStatsMostViewedEvent']);
});

Route::prefix('/v1/group')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/limit/{limit}/order/{order}/find/{find}', [QueryGroupApi::class, 'getAllGroup']);
    Route::get('/member/{slug}/{filter_name}/limit/{limit}/order/{order}', [QueryGroupApi::class, 'getAvailableUserBySlug']);
    Route::get('/member/{slug}/{limit}', [QueryGroupApi::class, 'getGroupRelationBySlug']);
});

Route::prefix('/v1/trash')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/order/{order}/cat/{category}/find/{search}', [QueryTrashApi::class, 'getAllContentTrash']);
});

