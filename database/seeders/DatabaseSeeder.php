<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\UserGroup;
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

        // Create one admin user with multiple roles
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

        UserRole::create([
            'role_id' => 2,
            'user_id' => $user->id
        ]);

        // Create guest user
        $user = User::factory()->create([
            'name' => 'Guest student',
            'email' => 'guest@guest.local',
            'password' => $password
        ]);

        UserRole::create([
            'role_id' => 3,
            'user_id' => $user->id
        ]);

        // Create multi groups user
        $userMultiGroup = User::factory()->create([
            'name' => 'Multi group user',
            'email' => 'multi@group.local',
            'password' => $password
        ]);

        UserRole::create([
            'role_id' => 2,
            'user_id' => $userMultiGroup->id
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
        $courses = ['Painting', 'Programming', 'Other'];
        foreach ($courses as $course) {
            Course::factory()->create(['name' => $course]);
        }

        $this->call([
            GroupSeeder::class,
            EventSeeder::class,
        ]);

        // Add users to groups
        UserGroup::create([
            'group_id' => 1,
            'user_id' => $userMultiGroup->id
        ]);

        UserGroup::create([
            'group_id' => 2,
            'user_id' => $userMultiGroup->id
        ]);

        foreach ($users as $user) {
            UserGroup::create([
                'group_id' => rand(1, 2),
                'user_id' => $user->id
            ]);
        }
    }
}
