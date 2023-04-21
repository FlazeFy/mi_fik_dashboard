<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\TagApi\Commands as CommandTagApi;
use App\Http\Controllers\Api\TagApi\Queries as QueryTagApi;
use App\Http\Controllers\Api\AuthApi\Commands as CommandAuthApi;
use App\Http\Controllers\Api\AuthApi\Queries as QueryAuthApi;
use App\Http\Controllers\Api\ContentApi\CommandTask as CommandTaskApi;
use App\Http\Controllers\Api\ContentApi\QueryTask as QueryTaskApi;
use App\Http\Controllers\Api\UserApi\Queries as QueryUserApi;
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

######################### Public Route #########################

Route::post('/v1/login', [CommandAuthApi::class, 'login']);

Route::prefix('/v1/dictionaries')->group(function() {
    Route::get('/', [QueryDictionaryApi::class, 'getAllDictionary']);
    Route::get('/type', [QueryDictionaryApi::class, 'getAllDictionaryType']);
});

######################### Private Route #########################

Route::get('/v1/logout', [QueryAuthApi::class, 'logout'])->middleware(['auth:sanctum']);

Route::prefix('/v1/help')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/{type}', [QueryHelpApi::class, 'getHelpCategoryByType']);
});

Route::prefix('/v1/task')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/{id_user}', [QueryTaskApi::class, 'getMyTask']);
    Route::post('/create/{id_user}', [CommandTaskApi::class, 'addTask']);
    Route::put('/update/{id}', [CommandTaskApi::class, 'updateTask']);
    Route::delete('/delete/{id}', [CommandTaskApi::class, 'deleteTask']);
    Route::delete('/destroy/{id}', [CommandTaskApi::class, 'destroyTask']);
});

Route::prefix('/v1/tag')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/{limit}', [QueryTagApi::class, 'getAllTag']);
    Route::post('/create', [CommandTagApi::class, 'addTag']);
    Route::put('/update/{id}', [CommandTagApi::class, 'updateTag']);
    Route::delete('/delete/{id}', [CommandTagApi::class, 'deleteTag']);
    Route::delete('/destroy/{id}', [CommandTagApi::class, 'destroyTag']);
});

Route::prefix('/v1/notification')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [QueryNotificationApi::class, 'getAllNotification']);
    Route::get('/{user_id}', [QueryNotificationApi::class, 'getMyNotification']);
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
});

Route::prefix('/v1/archive')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/{user_id}', [QueryArchiveApi::class, 'getArchive']);

    Route::post('/create', [CommandArchiveApi::class, 'createArchive']);
    Route::post('/createRelation', [CommandArchiveApi::class, 'addToArchive']);
    Route::put('/edit/{id}', [CommandArchiveApi::class, 'editArchive']);
    Route::delete('/delete/{id}', [CommandArchiveApi::class, 'deleteArchive']);
});

Route::prefix('/v1/user')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/{filter_name}/limit/{limit}/order/{order}', [QueryUserApi::class, 'getUser']);
    Route::get('/{slug_name}', [QueryUserApi::class, 'getUserDetail']);
    Route::get('/request/new', [QueryUserApi::class, 'getNewUserRequest']);
    Route::get('/request/old', [QueryUserApi::class, 'getOldUserRequest']);
    Route::get('/request/dump', [QueryUserApi::class, 'getUserRejectedRequest']);
});

Route::prefix('/v1/stats')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/mostviewed', [QueryContentApi::class, 'getStatsMostViewedEvent']);
});

Route::prefix('/v1/group')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/limit/{limit}/order/{order}', [QueryGroupApi::class, 'getAllGroup']);
});

Route::prefix('/v1/trash')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/order/{order}/cat/{category}/find/{search}', [QueryTrashApi::class, 'getAllContentTrash']);
});
