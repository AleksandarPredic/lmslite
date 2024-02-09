<?php

use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::name('admin.')->middleware('can:admin')->group(function () {
    // Course
    Route::resource('/admin/courses', CourseController::class);

    // Event
    Route::resource('/admin/events', EventController::class);

    // Calendar event
    Route::resource('/admin/calendar-events', CalendarEventController::class)
         ->except(['index', 'store', 'create']);
    Route::post('/admin/calendar-events/{calendarEvent}/users/{group?}', [CalendarEventController::class, 'addUser'])
         ->name('calendar-events.users.store');
    Route::delete('/admin/calendar-events/{calendarEvent}/users/{user}', [CalendarEventController::class, 'removeUser'])
         ->name('calendar-events.users.destroy');
    Route::patch('/admin/calendar-events/{calendarEvent}/users/status/{user}', [CalendarEventController::class, 'updateUserStatus'])
         ->name('calendar-events.users.status.update');

    // Group
    Route::resource('/admin/groups', GroupController::class);
    Route::post('/admin/groups/{group}/users', [GroupController::class, 'addUser'])->name('groups.users.store');
    Route::delete('/admin/groups/{group}/users/{user}', [GroupController::class, 'removeUser'])->name('groups.users.destroy');

    // User
    Route::resource('/admin/users', UserController::class);
    Route::get('/admin/users/{user}/next-calendar-events', [UserController::class, 'nextCalendarEvents'])->name('users.nextcalendarevents');
    Route::post('/admin/users/find', [UserController::class, 'findUsers'])->name('users.find');

    // Statistics
    Route::get('/admin/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
});

require __DIR__.'/auth.php';
