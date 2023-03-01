<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Mifik\HomepageController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\Api\ContentApi;
use App\Http\Controllers\Api\ArchiveApi;

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

Route::prefix('/v1/content')->group(function () {
    Route::get('/', [HomepageController::class, 'getContentHeader']);
    Route::get('/id/{id}', [HomepageController::class, 'getContent']);
    Route::post('/create/{id_user}', [HomepageController::class, 'addContent']);
});

Route::prefix('/v1/task')->group(function () {
    Route::get('/{id_user}', [HomepageController::class, 'getMyTask']);
    Route::post('/create/{id_user}', [HomepageController::class, 'addTask']);
    Route::put('/update/{id}', [HomepageController::class, 'updateTask']);
});

Route::prefix('/v1/schedule')->group(function () {
    Route::get('/{date}', [HomepageController::class, 'getAllSchedule']);
    Route::get('/my/{id}', [HomepageController::class, 'getMySchedule']);
});

Route::prefix('/v1/tag')->group(function () {
    Route::get('/', [HomepageController::class, 'getAllTag']);
});

Route::prefix('/v1/archieve')->group(function () {
    Route::get('/{id_user}', [HomepageController::class, 'getMyArchieve']);
    Route::post('/create/{id_user}', [HomepageController::class, 'addArchive']);
    Route::put('/edit/{id}', [HomepageController::class, 'editArchive']);
    Route::delete('/delete/{id}', [HomepageController::class, 'deleteArchive']);
});

Route::prefix('/v1/notification')->group(function () {
    Route::get('/', [HomepageController::class, 'getAllNotification']);
});

Route::prefix('/v2/content')->group(function() {
    Route::get('/', [ContentApi::class, 'getContentHeader']);
    Route::get('/{slug}', [ContentApi::class, 'getContentBySlug']);
});

Route::prefix('/v3/content')->group(function() {
    Route::get('/{slug}', [ContentApi::class, 'getContentBySlugLike']);
});

Route::prefix('/v2/archive')->group(function() {
    Route::post('/create', [ArchiveApi::class, 'createArchive']);
    Route::get('/{slug}/my', [ArchiveApi::class, 'getArchive']);
});
