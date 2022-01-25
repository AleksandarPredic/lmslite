<?php

use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GroupController;
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
    Route::resource('/admin/courses', CourseController::class)->except(['show']);

    // Event
    Route::resource('/admin/events', EventController::class);

    // Calendar event
    Route::resource('/admin/calendar-events', CalendarEventController::class);
    Route::post('/admin/calendar-events/users/{calendarEvent}/{group?}', [CalendarEventController::class, 'addUser'])
         ->name('calendar-events.users.store');
    Route::delete('/admin/calendar-events/users/{user}/{calendarEventUser}', [CalendarEventController::class, 'removeUser'])
         ->name('calendar-events.users.destroy');

    // Group
    Route::resource('/admin/groups', GroupController::class);
    Route::post('/admin/groups/users/{group}', [GroupController::class, 'addUser'])->name('groups.users.store');
    Route::delete('/admin/groups/users/{userGroup}/{user}', [GroupController::class, 'removeUser'])->name('groups.users.destroy');

    // User
    Route::post('/admin/users/find', [UserController::class, 'findUsers'])->name('users.find');
});

require __DIR__.'/auth.php';
