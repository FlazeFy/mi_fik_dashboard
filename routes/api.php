<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Mifik\DashboardController;
use App\Http\Controllers\DashboardController;

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

Route::prefix('/content')->group(function () {
    Route::get('/', [DashboardController::class, 'getAllContent']);
    Route::get('/{id}', [DashboardController::class, 'getContent']);
    Route::post('/create/{id_user}', [DashboardController::class, 'addContent']);
});

Route::prefix('/task')->group(function () {
    Route::get('/{id_user}', [DashboardController::class, 'getMyTask']);
    Route::post('/create/{id_user}', [DashboardController::class, 'addTask']);
    Route::put('/update/{id}', [DashboardController::class, 'updateTask']);
});

Route::prefix('/schedule')->group(function () {
    Route::get('/{date}', [DashboardController::class, 'getAllSchedule']);
});

Route::prefix('/tag')->group(function () {
    Route::get('/', [DashboardController::class, 'getAllTag']);
});

Route::prefix('/archieve')->group(function () {
    Route::get('/{id_user}', [DashboardController::class, 'getMyArchieve']);
    Route::post('/create/{id_user}', [DashboardController::class, 'addArchive']);
});
