<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::factory([
            'name' => 'Painting',
            'course_id' => (Course::where('name', 'Painting')->first())->id
        ])->create();

        Group::factory([
            'name' => 'Programming',
            'course_id' => (Course::where('name', 'Programming')->first())->id
        ])->create();

        Group::factory([
            'name' => 'Without course attached'
        ])->create();
    }
}
