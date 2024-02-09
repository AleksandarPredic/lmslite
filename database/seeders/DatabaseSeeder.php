<?php

namespace Database\Seeders;

use App\Models\CalendarEventUser;
use App\Models\CalendarEventUserStatus;
use App\Models\Course;
use App\Models\CourseDiscount;
use App\Models\CourseMembership;
use App\Models\Event;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

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

        $faker = Faker::create();

        // Create one admin user with multiple roles
        if ('local' === config('app.env')) {
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
        }

        // Create multi groups user - used below for calendar overrides
        $userMultiGroup = User::factory()->create([
            'name' => 'Multi group user',
            'email' => 'multi@group.local',
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
            $course = Course::factory()->create(['name' => $course]);

            $coursePrices = [];
            for ($i = 0; $i <= 2; $i++) {
                $coursePrices[] = $faker->numberBetween(2000, 5000);
            }

            // Make a couple of prices per couser, so we can test price changes
            foreach ($coursePrices as $subMonths => $coursePrice) {
                // Simulate that the price changes before today date so we can compare membership to pay per month price
                $createdTime = Carbon::now()->subMonths($subMonths);

                CourseMembership::create([
                    'course_id' => $course->id,
                    'price' => $coursePrice,
                    'created_at' => $createdTime,
                    'updated_at' => $createdTime,
                ]);
            }

            if ($course->id > 2) {
                continue;
            }

            // Add two discount prices for courses with ID 1 and 2
            $courseDiscountPrices = [500, 1000];

            foreach ($courseDiscountPrices as $courseDiscount) {
                CourseDiscount::create([
                    'course_id' => $course->id,
                    'name' => 'discount ' . $faker->word,
                    'price' => $courseDiscount,
                ]);
            }
        }

        $this->call([
            GroupSeeder::class,
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

        // Create one single and one recurring event and assign groups
        Event::factory()->create([
            'group_id' => 1,
            'recurring' => false,
            'days' => null,
            'recurring_until' => null,
        ]);

        Event::factory()->create([
            'recurring' => false,
            'days' => null,
            'recurring_until' => null,
        ]);

        $event = Event::factory()->create([
            'group_id' => 2,
            'recurring' => true,
        ]);

        // Add some seeds for calendar event users for the first recurring event calendar event
        $firstCalendarEvent = $event->calendarEvents()->first();

        $addedCalendarEventUsers = User::factory(3)->create();
        $calendarUserStatus = function($key) {
            switch ($key) {
                case 0:
                    return "canceled";
                case 1:
                    return "no-show";
                default:
                    return "attended";
            }
        };
        $calendarUserStatusInfo = function($key) {
            switch ($key) {
                case 0:
                    return "trial";
                case 1:
                    return "compensation";
                default:
                    return null;
            }
        };
        foreach ($addedCalendarEventUsers as $key => $addedCalendarEventUser) {
            UserRole::create([
                'role_id' => 2,
                'user_id' => $addedCalendarEventUser->id
            ]);

            CalendarEventUser::create([
                'calendar_event_id' => $firstCalendarEvent->id,
                'user_id' => $addedCalendarEventUser->id,
            ]);

            CalendarEventUserStatus::create([
                'calendar_event_id' => $firstCalendarEvent->id,
                'user_id' => $addedCalendarEventUser->id,
                'status' => $calendarUserStatus($key),
                'info' => $calendarUserStatusInfo($key)
            ]);
        }
    }
}
