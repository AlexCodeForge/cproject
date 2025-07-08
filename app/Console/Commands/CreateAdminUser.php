<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin {email=admin@optionrocket.com} {name=Admin User}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->argument('name');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return;
        }

        // Create the admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('password'), // Default password
            'email_verified_at' => now(),
        ]);

        // Assign admin role
        $user->assignRole('admin');

        $this->info("Admin user created successfully!");
        $this->info("Email: {$email}");
        $this->info("Password: password");
        $this->warn("Please change the password after first login!");

        return Command::SUCCESS;
    }
}
