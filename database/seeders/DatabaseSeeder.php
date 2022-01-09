<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Create one admin and one guest student user
        $password = Hash::make('password');
        $user = User::factory()->create([
           'name' => 'Admin',
           'email' => 'info@acapredic.com',
           'password' => $password
        ]);

        UserRole::create([
            'role_id' => 1,
            'user_id' => $user->id
        ]);

        $user = User::factory()->create([
            'name' => 'Guest student',
            'email' => 'guest@guest.local',
            'password' => $password
        ]);

        UserRole::create([
            'role_id' => 3,
            'user_id' => $user->id
        ]);

        // Create some student users
        $users = User::factory(30)->create();
        foreach ($users as $user) {
            UserRole::create([
                'role_id' => 2,
                'user_id' => $user->id
            ]);
        }

        // Create a few courses
        $courses = ['Slikanje', 'Programiranje', 'Tehnicko'];
        foreach ($courses as $course) {
            Course::factory()->create(['name' => $course]);
        }

        $this->call([
            EventSeeder::class,
        ]);
    }
}
