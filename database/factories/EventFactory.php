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
        $starting = Carbon::now()->addWeek()->addHours(3)->addMinutes(45);
        $ending = clone $starting;

        return [
            'name' => $this->faker->sentence(),
            'recurring' => rand(0, 1),
            'days' => [1, 3],
            'occurrence' => 'weekly',
            'starting_at' => $starting,
            'ending_at' => $ending->addMonths(2),
            'note' => $this->faker->paragraph()
        ];
    }
}
