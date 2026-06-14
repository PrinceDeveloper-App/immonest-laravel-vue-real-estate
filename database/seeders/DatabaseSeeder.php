<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => 'password', 'is_admin' => true]
        );

        $user = User::firstOrCreate(
            ['email' => 'test2@example.com'],
            ['name' => 'Test User 2', 'password' => 'password']
        );

        Listing::factory(10)->create(['by_user_id' => $admin->id]);
        Listing::factory(10)->create(['by_user_id' => $user->id]);
    }
}
