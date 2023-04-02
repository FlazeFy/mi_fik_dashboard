<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\TagApi\Commands as CommandTagApi;
use App\Http\Controllers\Api\TagApi\Queries as QueryTagApi;
use App\Http\Controllers\Api\AuthApi\Commands as CommandAuthApi;
use App\Http\Controllers\Api\AuthApi\Queries as QueryAuthApi;
use App\Http\Controllers\Api\ContentApi\CommandTask as CommandTaskApi;
use App\Http\Controllers\Api\ContentApi\QueryTask as QueryTaskApi;
use App\Http\Controllers\Api\UserApi\Queries as QueryUserApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GroupApi\Queries as QueryGroupApi;
use App\Http\Controllers\Api\ArchiveApi\Commands as CommandArchiveApi;
use App\Http\Controllers\Api\ArchiveApi\Queries as QueryArchiveApi;
use App\Http\Controllers\Api\ContentApi\CommandContent as CommandContentApi;
use App\Http\Controllers\Api\ContentApi\QueryContent as QueryContentApi;
use App\Http\Controllers\Api\SystemApi\QueryDictionary as QueryDictionaryApi;
use App\Http\Controllers\Api\SystemApi\QueryNotification as QueryNotificationApi;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/v1/task')->group(function () {
    Route::get('/{id_user}', [TaskApi::class, 'getMyTask']);
    Route::post('/create/{id_user}', [TaskApi::class, 'addTask']);
    Route::put('/update/{id}', [TaskApi::class, 'updateTask']);
    Route::delete('/delete/{id}', [TaskApi::class, 'deleteTask']);
    Route::delete('/destroy/{id}', [TaskApi::class, 'destroyTask']);
});

Route::prefix('/v1/tag')->group(function () {
    Route::get('/{limit}', [TagApi::class, 'getAllTag']);
    Route::post('/create', [TagApi::class, 'addTag']);
    Route::put('/update/{id}', [TagApi::class, 'updateTag']);
    Route::delete('/delete/{id}', [TagApi::class, 'deleteTag']);
    Route::delete('/destroy/{id}', [TagApi::class, 'destroyTag']);
});

Route::prefix('/v1/notification')->group(function () {
    Route::get('/', [QueryNotificationApi::class, 'getAllNotification']);
    Route::get('/{user_id}', [QueryNotificationApi::class, 'getMyNotification']);
});

Route::prefix('/v1/content')->group(function() {
    Route::get('/', [QueryContentApi::class, 'getContentHeader']);
    Route::get('/slug/{slug}', [QueryContentApi::class, 'getContentBySlug']);
    Route::get('/date/{date}', [QueryContentApi::class, 'getAllContentSchedule']);

    Route::delete('/delete/{id}', [CommandContentApi::class, 'deleteContent']);
    Route::delete('/destroy/{id}', [CommandContentApi::class, 'deleteContent']);
    Route::post('/create', [CommandContentApi::class, 'addContent']);
    Route::post('/open/{slug_name}/user/{user_slug}/role/{user_role}', [CommandContentApi::class, 'addView']);
    // Route::post('/open/{slug_name}/role/{user_role}', [ContentApi::class, 'addView']);
});

Route::prefix('/v2/content')->group(function() {
    Route::get('/slug/{slug}/order/{order}/date/{date}', [QueryContentApi::class, 'getContentBySlugLike']); //*Tag slug
});

Route::prefix('/v1/archive')->group(function() {
    Route::get('/{user_id}', [QueryArchiveApi::class, 'getArchive']);

    Route::post('/create', [CommandArchiveApi::class, 'createArchive']);
    Route::post('/createRelation', [CommandArchiveApi::class, 'addToArchive']);
    Route::put('/edit/{id}', [CommandArchiveApi::class, 'editArchive']);
    Route::delete('/delete/{id}', [CommandArchiveApi::class, 'deleteArchive']);
});

Route::prefix('/v1/dictionaries')->group(function() {
    Route::get('/', [QueryDictionaryApi::class, 'getAllDictionary']);
    Route::get('/type', [QueryDictionaryApi::class, 'getAllDictionaryType']);
});

Route::prefix('/v1/user')->group(function() {
    Route::get('/{filter_name}/limit/{limit}/order/{order}', [QueryUserApi::class, 'getUser']);
    Route::get('/{slug_name}', [QueryUserApi::class, 'getUserDetail']);
    Route::get('/request/new', [QueryUserApi::class, 'getNewUserRequest']);
    Route::get('/request/old', [QueryUserApi::class, 'getOldUserRequest']);
    Route::get('/request/dump', [QueryUserApi::class, 'getUserRejectedRequest']);
});

Route::prefix('/v1/stats')->group(function() {
    Route::get('/mostviewed', [QueryContentApi::class, 'getStatsMostViewedEvent']);
});

Route::prefix('/v1/group')->group(function() {
    Route::get('/limit/{limit}/order/{order}', [QueryGroupApi::class, 'getAllGroup']);
});

Route::post('/v1/login', [CommandAuthApi::class, 'login']);
Route::get('/v1/logout', [QueryAuthApi::class, 'logout'])->middleware(['auth:sanctum']);
