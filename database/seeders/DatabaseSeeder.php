<?php

namespace Database\Seeders;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['username' => 'Test User', 'password' => 'password'],
        );

        Idea::factory(15)->for($user)->create();
    }
}
