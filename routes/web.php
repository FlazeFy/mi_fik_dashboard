<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomepageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\MultiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgetController;
use App\Http\Controllers\WaitingController;

use App\Http\Controllers\Event\TagController;
use App\Http\Controllers\Event\DetailController;
use App\Http\Controllers\Event\CalendarController;
use App\Http\Controllers\Event\LocationController;
use App\Http\Controllers\Event\EditController;

use App\Http\Controllers\System\NotificationController;
use App\Http\Controllers\System\InfoController;
use App\Http\Controllers\System\DictionaryController;
use App\Http\Controllers\System\AccessController;

use App\Http\Controllers\Social\FeedbackController;
use App\Http\Controllers\Social\FaqController;

use App\Http\Controllers\User\RequestController;
use App\Http\Controllers\User\AllController;
use App\Http\Controllers\User\GroupingController;

######################### Public Route #########################

Route::prefix('/')->group(function () {
    Route::get('/', [LandingController::class, 'index'])->name('landing');
    Route::post('/login', [LandingController::class, 'login_admin']);
    Route::post('/add_feedback', [LandingController::class, 'add_feedback']);
    Route::post('/v2/login', [LandingController::class, 'login_auth']);
});

Route::prefix('/register')->group(function () {
    Route::get('/', [RegisterController::class, 'index'])->name('register');
});

Route::prefix('/forget')->group(function () {
    Route::get('/', [ForgetController::class, 'index'])->name('forget');
});

######################### Private Route #########################

Route::post('/sign-out', [MultiController::class, 'sign_out'])->middleware(['auth_v2:sanctum']);

Route::prefix('/waiting')->middleware(['auth_v2:sanctum'])->group(function () {
    Route::get('/', [WaitingController::class, 'index'])->name('waiting');
});

Route::prefix('/homepage')->middleware(['auth_v2:sanctum'])->group(function () {
    Route::get('/', [HomepageController::class, 'index'])->name('homepage');

    Route::post('/add_event', [HomepageController::class, 'add_event']);
    Route::post('/ordered/{order}', [HomepageController::class, 'set_ordering_content']);
    Route::post('/date', [HomepageController::class, 'set_filter_date']);
    Route::post('/date/reset', [HomepageController::class, 'reset_filter_date']);
    Route::post('/open/{slug_name}', [HomepageController::class, 'add_content_view']);
});

Route::prefix('/statistic')->middleware(['auth_v2:sanctum'])->group(function () {
    Route::get('/', [StatisticController::class, 'index']);
    Route::post('/update_mot/{id}', [StatisticController::class, 'update_mot']);
    Route::post('/update_mol/{id}', [StatisticController::class, 'update_mol']);
    Route::post('/update_ce/{id}', [StatisticController::class, 'update_ce']);
    Route::post('/update_mve/{id}', [StatisticController::class, 'update_mve']);
    Route::post('/update_mve_view', [StatisticController::class, 'update_mve_view']);
});

Route::prefix('/event')->middleware(['auth_v2:sanctum'])->group(function () {
    Route::get('/tag', [TagController::class, 'index']);
    Route::post('/tag/add', [TagController::class, 'add_tag']);
    Route::post('/tag/filter_category', [TagController::class, 'filter_category']);
    Route::post('/tag/add_category', [TagController::class, 'add_tag_category']);
    Route::post('/tag/update/{type}/{id}', [TagController::class, 'update_tag']);
    Route::post('/tag/delete/{id}', [TagController::class, 'delete_tag']);
    Route::post('/tag/delete/cat/{id}', [TagController::class, 'delete_cat_tag']);

    Route::get('/detail/{slug_name}', [DetailController::class, 'index'])->name('detail');
    Route::post('/detail/add_relation/{slug_name}', [DetailController::class, 'add_relation']);
    Route::post('/detail/delete_relation/{id}', [DetailController::class, 'delete_relation']);
    Route::post('/detail/add_archive', [DetailController::class, 'add_archive']);
    Route::post('/detail/delete/{slug_name}', [DetailController::class, 'delete_event']);

    Route::get('/edit/{slug_name}', [EditController::class, 'index']);
    Route::post('/edit/update/info/{slug_name}', [EditController::class, 'update_event_info']);
    Route::post('/edit/update/date/{slug_name}', [EditController::class, 'update_event_date']);
    Route::post('/edit/update/draft/{slug_name}', [EditController::class, 'update_event_draft']);
    Route::post('/edit/update/attach/add/{slug_name}', [EditController::class, 'update_event_add_attach']);
    Route::post('/edit/update/attach/remove/{slug_name}', [EditController::class, 'update_event_remove_attach']);
    Route::post('/edit/update/tag/remove/{slug_name}', [EditController::class, 'update_event_remove_tag']);
    Route::post('/edit/update/tag/add/{slug_name}', [EditController::class, 'update_event_add_tag']);
    Route::post('/edit/update/loc/add/{slug_name}', [EditController::class, 'update_event_add_loc']);
    Route::post('/edit/update/loc/remove/{slug_name}', [EditController::class, 'update_event_remove_loc']);

    Route::get('/calendar', [CalendarController::class, 'index']);
    Route::post('/calendar/set_filter_tag/{all}/{from}', [CalendarController::class, 'set_filter_tag']);
    Route::post('/calendar/ordered/{order}', [CalendarController::class, 'set_ordering_content']);
    Route::post('/calendar/sortsection/{menu}/{navigation}', [MultiController::class, 'sort_section']);

    Route::get('/location', [LocationController::class, 'index']);
});

Route::prefix('/system')->middleware(['auth_v2:sanctum'])->group(function () {
    Route::get('/notification', [NotificationController::class, 'index']);
    Route::post('/notification/add', [NotificationController::class, 'add_notif']);
    Route::post('/notification/update/{id}', [NotificationController::class, 'update_notif']);
    Route::post('/notification/delete/{id}', [NotificationController::class, 'delete_notif']);

    Route::get('/info', [InfoController::class, 'index']);
    Route::post('/info/update/type/{id}', [InfoController::class, 'update_type']);
    Route::post('/info/update/body/{id}', [InfoController::class, 'update_body']);
    Route::post('/info/update/pagloc/{id}', [InfoController::class, 'update_pagloc']);
    Route::post('/info/update/active/{id}/{active}', [InfoController::class, 'update_active']);
    Route::post('/info/delete/{id}', [InfoController::class, 'delete']);
    Route::post('/info/create', [InfoController::class, 'create']);
    Route::post('/info/filter_type', [InfoController::class, 'filter_type']);

    Route::get('/dictionary', [DictionaryController::class, 'index']);
    Route::post('/dictionary/create', [DictionaryController::class, 'create']);
    Route::post('/dictionary/update/type/{id}', [DictionaryController::class, 'update_type']);
    Route::post('/dictionary/update/info/{id}', [DictionaryController::class, 'update_info']);
    Route::post('/dictionary/delete/{id}', [DictionaryController::class, 'delete']);

    Route::get('/access', [AccessController::class, 'index']);
});

Route::prefix('/user')->middleware(['auth_v2:sanctum'])->group(function () {
    Route::get('/request', [RequestController::class, 'index']);
    Route::post('/request/manage_role_acc', [RequestController::class, 'add_role_acc']);
    Route::post('/request/manage_acc', [RequestController::class, 'add_acc']);
    Route::post('/request/manage_suspend', [RequestController::class, 'add_suspend']);
    Route::post('/request/manage_recover', [RequestController::class, 'add_recover']);
    Route::post('/request/reject_request/multi', [RequestController::class, 'reject_request_multi']);
    Route::post('/request/accept_request/multi', [RequestController::class, 'accept_request_multi']);
    Route::post('/request/reject_join', [RequestController::class, 'reject_join']);
    Route::post('/request/accept_join/{isrole}', [RequestController::class, 'accept_join']);

    Route::get('/all', [AllController::class, 'index']);
    Route::post('/all/set_filter_name/{all}/{type}', [AllController::class, 'set_filter_name']);
    Route::post('/all/set_filter_role/{all}', [AllController::class, 'set_filter_role']);
    Route::post('/all/ordered/{order}/{type}', [AllController::class, 'set_ordering_content']);

    Route::get('/group', [GroupingController::class, 'index']);
    Route::post('/group/ordered/{order}/{type}', [GroupingController::class, 'set_ordering_content']);
    Route::post('/group/add', [GroupingController::class, 'add_group']);
    Route::post('/group/delete/{id}', [GroupingController::class, 'delete_group']);
    Route::post('/group/edit/{id}', [GroupingController::class, 'edit_group']);
    Route::post('/group/member/add/{id}', [GroupingController::class, 'add_member']);
    Route::post('/group/member/remove/{id}', [GroupingController::class, 'remove_member']);
});

Route::prefix('/setting')->middleware(['auth_v2:sanctum'])->group(function () {
    Route::get('/', [SettingController::class, 'index']);
    Route::post('/update_chart', [SettingController::class, 'update_chart']);
    Route::post('/update_jobs/{id}', [SettingController::class, 'update_jobs']);
    Route::post('/update_landing/{id}', [SettingController::class, 'update_landing']);
});

Route::prefix('/trash')->middleware(['auth_v2:sanctum'])->group(function () {
    Route::get('/', [TrashController::class, 'index']);
    Route::post('/ordered/{order}', [TrashController::class, 'set_ordering_content']);
    Route::post('/recover/{slug}/{type}', [TrashController::class, 'recover_content']);
    Route::post('/destroy/{slug}/{type}', [TrashController::class, 'destroy_content']);
});

Route::prefix('/about')->middleware(['auth_v2:sanctum'])->group(function () {
    Route::get('/', [AboutController::class, 'index']);
    Route::post('/edit/app', [AboutController::class, 'edit_about_app']);
    Route::post('/edit/contact', [AboutController::class, 'edit_about_contact']);
    Route::post('/help/add/type', [AboutController::class, 'add_help_type']);
    Route::post('/help/add/cat', [AboutController::class, 'add_help_cat']);
    Route::post('/help/delete/cat/{id}', [AboutController::class, 'delete_help_cat']);
    Route::post('/help/edit/body/{id}', [AboutController::class, 'edit_help_body']);
    Route::post('/sortsection/{menu}/{navigation}', [MultiController::class, 'sort_section']);
    Route::post('/toogle/{ctx}/{switch}', [AboutController::class, 'toogle_edit_app']);
});

Route::prefix('/social')->middleware(['auth_v2:sanctum'])->group(function () {
    Route::get('/feedback', [FeedbackController::class, 'index']);
    Route::post('/feedback/sortsection/{menu}/{navigation}', [MultiController::class, 'sort_section']);
    Route::post('/feedback/delete/{id}', [FeedbackController::class, 'delete_feedback']);
    Route::post('/feedback/filter_suggest', [FeedbackController::class, 'filter_suggest']);

    Route::get('/faq', [FaqController::class, 'index']);
    Route::post('/faq/sortsection/{menu}/{navigation}', [MultiController::class, 'sort_section']);
    Route::post('/faq/answer', [FaqController::class, 'set_answer']);
    Route::post('/faq/remove/{id}', [FaqController::class, 'delete']);
});

Route::prefix('/profile')->middleware(['auth_v2:sanctum'])->group(function () {
    Route::get('/', [ProfileController::class, 'index']);
    Route::post('/edit/profile', [ProfileController::class, 'edit_profile']);
    Route::post('/request', [ProfileController::class, 'request_role']);
    Route::post('/faq', [ProfileController::class, 'add_faq']);
    Route::post('/sortsection/{menu}/{navigation}', [MultiController::class, 'sort_section']); // Not finished
});