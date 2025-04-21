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
            'course_id' => (Course::where('name', 'Painting')->first())->id,
            'price_1' => 3500,
            'price_2' => 2500,
            'active' => true,
        ])->create();

        Group::factory([
            'name' => 'Programming',
            'course_id' => (Course::where('name', 'Programming')->first())->id,
            'price_1' => 3000,
            'price_2' => 2600,
            'active' => true,
        ])->create();

        Group::factory([
            'name' => 'Empty data',
            'price_2' => null,
        ])->create();
    }
}
