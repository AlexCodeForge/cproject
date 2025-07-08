<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $premiumRole = Role::firstOrCreate(['name' => 'premium']);
        $freeRole = Role::firstOrCreate(['name' => 'free']);

        // Create permissions
        $permissions = [
            // User management
            'manage-users',
            'view-users',
            'edit-users',
            'delete-users',

            // Content management
            'manage-posts',
            'create-posts',
            'edit-posts',
            'delete-posts',
            'view-premium-posts',

            // Trading alerts
            'manage-alerts',
            'create-alerts',
            'view-premium-alerts',

            // Chat system
            'manage-chat',
            'moderate-chat',
            'access-premium-channels',

            // Events
            'manage-events',
            'create-events',
            'access-premium-events',

            // Courses
            'manage-courses',
            'create-courses',
            'access-premium-courses',

            // Analytics
            'view-analytics',
            'view-admin-dashboard',

            // P&L tracking
            'manage-pnl',
            'view-public-pnl',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());

        $premiumRole->givePermissionTo([
            'view-premium-posts',
            'view-premium-alerts',
            'access-premium-channels',
            'access-premium-events',
            'access-premium-courses',
            'manage-pnl',
            'view-public-pnl',
        ]);

        $freeRole->givePermissionTo([
            'view-public-pnl',
        ]);
    }
}
