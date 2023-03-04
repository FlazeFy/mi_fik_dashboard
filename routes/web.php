<?php

use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\Mifik\HomepageController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\StatisticController;

use App\Http\Controllers\Event\AllEventController;
use App\Http\Controllers\Event\TagController;
use App\Http\Controllers\Event\DetailController;
use App\Http\Controllers\Event\CalendarController;

use App\Http\Controllers\System\NotificationController;

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

// Route::get('/homepage', [HomepageController::class, 'index'])->name('dashboard');

Route::prefix('/')->group(function () {
    Route::get('/', [LandingController::class, 'index']);
    Route::post('/login', [LandingController::class, 'login_admin']);
});

Route::prefix('/homepage')->group(function () {
    Route::get('/', [HomepageController::class, 'index'])->name('homepage');

    Route::post('/add_event', [HomepageController::class, 'add_event']);
    Route::post('/add_task', [HomepageController::class, 'add_task']);
});

Route::prefix('/statistic')->group(function () {
    Route::get('/', [StatisticController::class, 'index']);
    Route::post('/update_mot/{id}', [StatisticController::class, 'update_mot']);
    Route::post('/update_mol/{id}', [StatisticController::class, 'update_mol']);
    Route::post('/update_ce/{id}', [StatisticController::class, 'update_ce']);
});

Route::prefix('/event')->group(function () {
    Route::get('/page/{page}', [AllEventController::class, 'index']);
    Route::post('/navigate/{page}', [AllEventController::class, 'navigate_page']);

    Route::get('/tag', [TagController::class, 'index']);
    Route::post('/tag/add', [TagController::class, 'add_tag']);
    Route::post('/tag/update/{id}', [TagController::class, 'update_tag']);
    Route::post('/tag/delete/{id}', [TagController::class, 'delete_tag']);

    Route::get('/detail/{slug_name}', [DetailController::class, 'index']);
    Route::post('/detail/add_archive/{slug_name}', [DetailController::class, 'add_content_task_relation']);

    Route::get('/calendar', [CalendarController::class, 'index']);
    Route::post('/calendar/set_filter_tag/{all}', [CalendarController::class, 'set_filter_tag']);
});

Route::prefix('/system')->group(function () {
    Route::get('/notification', [NotificationController::class, 'index']);
    Route::post('/notification/update/{id}', [NotificationController::class, 'update_notif']);
});