<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed reference data first
        $this->call([
            SubscriptionPlansSeeder::class,
            PostCategoriesSeeder::class,
            ChatChannelsSeeder::class,
        ]);

        // Create test user with profile
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create additional test users for development
        User::factory(10)->create();
    }
}
