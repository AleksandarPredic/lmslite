<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => config('app.users.admin.email'),
            'password' => Hash::make(config('app.users.admin.password'))
        ]);

        UserRole::create([
            'role_id' => 1,
            'user_id' => $user->id
        ]);
    }
}
