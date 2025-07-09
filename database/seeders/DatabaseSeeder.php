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
        // Run the role seeder first
        $this->call(RoleSeeder::class);
        $this->call(SubscriptionPlansSeeder::class);
        $this->call(PostCategoriesSeeder::class);
        $this->call(ChatChannelsSeeder::class);
        $this->call(PostSeeder::class);

        // Create a test admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@optionrocket.com',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create a test premium user
        $premium = User::factory()->create([
            'name' => 'Premium User',
            'email' => 'premium@optionrocket.com',
            'email_verified_at' => now(),
        ]);
        $premium->assignRole('premium');

        // Create a test free user
        $free = User::factory()->create([
            'name' => 'Free User',
            'email' => 'free@optionrocket.com',
            'email_verified_at' => now(),
        ]);
        $free->assignRole('free');
    }
}
