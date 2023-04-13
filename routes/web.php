<?php

use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\Mifik\HomepageController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\MultiController;

use App\Http\Controllers\Event\AllEventController;
use App\Http\Controllers\Event\TagController;
use App\Http\Controllers\Event\DetailController;
use App\Http\Controllers\Event\CalendarController;
use App\Http\Controllers\Event\LocationController;
use App\Http\Controllers\Event\EditController;

use App\Http\Controllers\System\NotificationController;
use App\Http\Controllers\System\InfoController;
use App\Http\Controllers\System\MaintenanceController;
use App\Http\Controllers\System\DictionaryController;

use App\Http\Controllers\Social\FeedbackController;
use App\Http\Controllers\Social\FaqController;

use App\Http\Controllers\User\RequestController;
use App\Http\Controllers\User\AllController;
use App\Http\Controllers\User\GroupingController;

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
    Route::get('/', [LandingController::class, 'index'])->name('landing');
    Route::post('/login', [LandingController::class, 'login_admin']);
});

Route::prefix('/homepage')->group(function () {
    Route::get('/', [HomepageController::class, 'index'])->name('homepage');

    Route::post('/add_event', [HomepageController::class, 'add_event']);
    Route::post('/add_task', [HomepageController::class, 'add_task']);
    Route::post('/ordered/{order}', [HomepageController::class, 'set_ordering_content']);
    Route::post('/date', [HomepageController::class, 'set_filter_date']);
    Route::post('/date/reset', [HomepageController::class, 'reset_filter_date']);
    Route::post('/open/{slug_name}', [HomepageController::class, 'add_content_view']);
});

Route::prefix('/statistic')->group(function () {
    Route::get('/', [StatisticController::class, 'index']);
    Route::post('/update_mot/{id}', [StatisticController::class, 'update_mot']);
    Route::post('/update_mol/{id}', [StatisticController::class, 'update_mol']);
    Route::post('/update_ce/{id}', [StatisticController::class, 'update_ce']);
    Route::post('/update_mve/{id}', [StatisticController::class, 'update_mve']);
    Route::post('/update_mve_view', [StatisticController::class, 'update_mve_view']);
});

Route::prefix('/event')->group(function () {
    Route::get('/tag', [TagController::class, 'index']);
    Route::post('/tag/add', [TagController::class, 'add_tag']);
    Route::post('/tag/update/{id}', [TagController::class, 'update_tag']);
    Route::post('/tag/delete/{id}', [TagController::class, 'delete_tag']);

    Route::get('/detail/{slug_name}', [DetailController::class, 'index']);
    Route::post('/detail/add_relation/{slug_name}', [DetailController::class, 'add_relation']);
    Route::post('/detail/delete_relation/{id}', [DetailController::class, 'delete_relation']);
    Route::post('/detail/add_archive', [DetailController::class, 'add_archive']);
    Route::post('/detail/delete/{slug_name}', [DetailController::class, 'delete_event']);

    Route::get('/edit/{slug_name}', [EditController::class, 'index']);
    Route::post('/edit/update/info/{slug_name}', [EditController::class, 'update_event_info']);
    Route::post('/edit/update/draft/{slug_name}', [EditController::class, 'update_event_draft']);
    Route::post('/edit/update/attach/add/{slug_name}', [EditController::class, 'update_event_add_attach']);
    Route::post('/edit/update/attach/remove/{slug_name}', [EditController::class, 'update_event_remove_attach']);
    Route::post('/edit/update/tag/remove/{slug_name}', [EditController::class, 'update_event_remove_tag']);
    Route::post('/edit/update/tag/add/{slug_name}', [EditController::class, 'update_event_add_tag']);
    Route::post('/edit/update/loc/add/{slug_name}', [EditController::class, 'update_event_add_loc']);
    Route::post('/edit/update/loc/remove/{slug_name}', [EditController::class, 'update_event_remove_loc']);

    Route::get('/calendar', [CalendarController::class, 'index']);
    Route::post('/calendar/set_filter_tag/{all}', [CalendarController::class, 'set_filter_tag']);
    Route::post('/calendar/sortsection/{menu}/{navigation}', [MultiController::class, 'sort_section']);

    Route::get('/location', [LocationController::class, 'index']);
});

Route::prefix('/system')->group(function () {
    Route::get('/notification', [NotificationController::class, 'index']);
    Route::post('/notification/update/{id}', [NotificationController::class, 'update_notif']);
    Route::post('/notification/delete/{id}', [NotificationController::class, 'delete_notif']);

    Route::get('/info', [InfoController::class, 'index']);
    Route::post('/info/update/{id}', [InfoController::class, 'update_type']);

    Route::get('/dictionary', [DictionaryController::class, 'index']);

    Route::get('/maintenance', [MaintenanceController::class, 'index']);
});

Route::prefix('/user')->group(function () {
    Route::get('/request', [RequestController::class, 'index']);
    Route::post('/request/manage_role_acc', [RequestController::class, 'add_role_acc']);
    Route::post('/request/manage_acc', [RequestController::class, 'add_acc']);
    Route::post('/request/manage_suspend', [RequestController::class, 'add_suspend']);
    Route::post('/request/manage_recover', [RequestController::class, 'add_recover']);

    Route::get('/all', [AllController::class, 'index']);
    Route::post('/all/set_filter_name/{all}/{type}', [AllController::class, 'set_filter_name']);
    Route::post('/all/ordered/{order}/{type}', [AllController::class, 'set_ordering_content']);

    Route::get('/group', [GroupingController::class, 'index']);
    Route::post('/group/ordered/{order}/{type}', [GroupingController::class, 'set_ordering_content']);
    Route::post('/group/add', [GroupingController::class, 'add_group']);
});

Route::prefix('/setting')->group(function () {
    Route::get('/', [SettingController::class, 'index']);
    Route::post('/update_chart', [SettingController::class, 'update_chart']);
    Route::post('/update_jobs/{id}', [SettingController::class, 'update_jobs']);
});

Route::prefix('/trash')->group(function () {
    Route::get('/', [TrashController::class, 'index']);
    Route::post('/ordered/{order}', [TrashController::class, 'set_ordering_content']);
    Route::post('/recover/{slug}/{type}', [TrashController::class, 'recover_content']);
    Route::post('/destroy/{slug}/{type}', [TrashController::class, 'destroy_content']);
});

Route::prefix('/about')->group(function () {
    Route::get('/', [AboutController::class, 'index']);
    Route::post('/edit/app', [AboutController::class, 'edit_about_app']);
    Route::post('/help/add/type', [AboutController::class, 'add_help_type']);
    Route::post('/help/add/cat', [AboutController::class, 'add_help_cat']);
    Route::post('/help/edit/body/{id}', [AboutController::class, 'edit_help_body']);
    Route::post('/sortsection/{menu}/{navigation}', [MultiController::class, 'sort_section']);
});

Route::prefix('/history')->group(function () {
    Route::get('/', [HistoryController::class, 'index']);
});

Route::prefix('/social')->group(function () {
    Route::get('/feedback', [FeedbackController::class, 'index']);
    Route::post('/feedback/sortsection/{menu}/{navigation}', [MultiController::class, 'sort_section']);

    Route::get('/faq', [FaqController::class, 'index']);
});