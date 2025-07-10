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

        // Create or retrieve a test admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@optionrocket.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // Default password
            ]
        );
        $admin->assignRole('admin');

        // Create or retrieve a test premium user
        $premium = User::firstOrCreate(
            ['email' => 'premium@optionrocket.com'],
            [
                'name' => 'Premium User',
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // Default password
            ]
        );
        $premium->assignRole('premium');

        // Create or retrieve a test free user
        $free = User::firstOrCreate(
            ['email' => 'free@optionrocket.com'],
            [
                'name' => 'Free User',
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // Default password
            ]
        );
        $free->assignRole('free');
    }
}
