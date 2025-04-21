<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $starting = Carbon::now()->addWeek(rand(1, 3))->setHours(0)->setMinutes(0)->setSeconds(0);

        return [
            'name' => $this->faker->sentence(),
            'starting_at' => $starting,
            'ending_at' => (clone $starting)->addMonths(6),
            'course_id' => 0, // We need to do this manually in the DatabaseSeeder
            'note' => $this->faker->paragraph(),
            'price_1' => $this->faker->randomFloat(2, 2000, 5000),
            'price_2' => $this->faker->randomFloat(2, 1500, 4000),
            'active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
