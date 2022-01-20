<?php

namespace Database\Seeders;

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
            'name' => 'Painting'
        ])->create();

        Group::factory([
            'name' => 'Programming'
        ])->create();
    }
}
