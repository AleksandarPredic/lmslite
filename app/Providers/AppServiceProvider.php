<?php

namespace App\Providers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Create role gates
        \Gate::define('admin', fn (User $user) => $user->role()->find(1));
        \Gate::define('student', fn (User $user) => $user->role()->find(2));
        \Gate::define('student-guest', fn (User $user) => $user->role()->find(3));
    }
}
