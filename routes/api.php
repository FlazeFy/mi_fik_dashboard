<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContentApi;
use App\Http\Controllers\Api\ArchiveApi;
use App\Http\Controllers\Api\TagApi;
use App\Http\Controllers\Api\TaskApi;
use App\Http\Controllers\Api\NotificationApi;

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
    Route::get('/', [TagApi::class, 'getAllTag']);
    Route::post('/create', [TagApi::class, 'addTag']);
    Route::put('/update/{id}', [TagApi::class, 'updateTag']);
    Route::delete('/delete/{id}', [TagApi::class, 'deleteTag']);
    Route::delete('/destroy/{id}', [TagApi::class, 'destroyTag']);
});

Route::prefix('/v1/notification')->group(function () {
    Route::get('/', [NotificationApi::class, 'getAllNotification']);
    Route::get('/{user_id}', [NotificationApi::class, 'getMyNotification']);
});

Route::prefix('/v1/content')->group(function() {
    Route::get('/', [ContentApi::class, 'getContentHeader']);
    Route::get('/slug/{slug}', [ContentApi::class, 'getContentBySlug']);
    Route::get('/date/{date}', [ContentApi::class, 'getAllContentSchedule']);
    Route::delete('/delete/{id}', [ContentApi::class, 'deleteContent']);
    Route::delete('/destroy/{id}', [ContentApi::class, 'deleteContent']);
});

Route::prefix('/v2/content')->group(function() {
    Route::get('/slug/{slug}/order/{order}', [ContentApi::class, 'getContentBySlugLike']);
});

Route::prefix('/v1/archive')->group(function() {
    Route::get('/{user_id}', [ArchiveApi::class, 'getArchive']);
    Route::post('/create', [ArchiveApi::class, 'createArchive']);
    Route::post('/createRelation', [ArchiveApi::class, 'addToArchive']);
    Route::put('/edit/{id}', [ArchiveApi::class, 'editArchive']);
    Route::delete('/delete/{id}', [ArchiveApi::class, 'deleteArchive']);
});
