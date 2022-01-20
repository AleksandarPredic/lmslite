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
            'note' => $this->faker->paragraph()
        ];
    }
}
