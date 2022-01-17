<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $starting = Carbon::now()->addWeek(rand(1, 3))->addHours(3)->addMinutes(45);

        return [
            'name' => $this->faker->sentence(),
            'recurring' => rand(0, 1),
            'days' => [1, 3],
            'starting_at' => $starting,
            'ending_at' => (clone $starting)->addHours(1)->setSecond(0),
            'recurring_until' => (clone $starting)->addMonths(2)->setSecond(0),
            'note' => $this->faker->paragraph()
        ];
    }
}
