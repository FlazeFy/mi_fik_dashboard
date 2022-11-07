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
});

Route::prefix('/tag')->group(function () {
    Route::get('/', [DashboardController::class, 'getAllTag']);
});

Route::prefix('/archieve')->group(function () {
    Route::get('/{id_user}', [DashboardController::class, 'getMyArchieve']);
});
