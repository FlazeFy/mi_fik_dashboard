<?php

use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\Mifik\DashboardController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('/dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::post('/update_mot/{id}', [DashboardController::class, 'update_mot']);
});
