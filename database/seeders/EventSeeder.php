<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create one single and one recurring event
        Event::factory()->create([
            'recurring' => false,
        ]);

        Event::factory()->create([
            'recurring' => true,
        ]);
    }
}
