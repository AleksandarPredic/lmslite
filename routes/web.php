<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserGroupController;
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
    Route::resource('/admin/courses', CourseController::class)->except(['show']);
    Route::resource('/admin/events', EventController::class);
    Route::resource('/admin/groups', GroupController::class);
    Route::delete('/admin/user-groups/{userGroup}/{user}', [UserGroupController::class, 'destroy'])
         ->name('user-group.destroy');
});

require __DIR__.'/auth.php';
