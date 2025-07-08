<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PackageIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that all required packages are properly installed and configured.
     */
    public function test_all_packages_are_installed(): void
    {
        // Test Laravel Sanctum
        $this->assertTrue(class_exists('Laravel\Sanctum\Sanctum'));

        // Test Laravel Cashier
        $this->assertTrue(class_exists('Laravel\Cashier\Cashier'));

        // Test Spatie Laravel Permission
        $this->assertTrue(class_exists('Spatie\Permission\Models\Role'));
        $this->assertTrue(class_exists('Spatie\Permission\Models\Permission'));

        // Test Laravel Reverb
        $this->assertTrue(class_exists('Laravel\Reverb\ReverbServiceProvider'));

        // Test Intervention Image
        $this->assertTrue(class_exists('Intervention\Image\ImageManager'));

        // Test Spatie Activity Log
        $this->assertTrue(class_exists('Spatie\Activitylog\Models\Activity'));

        // Test Laravel Telescope (dev only)
        if (app()->environment('local', 'testing')) {
            $this->assertTrue(class_exists('Laravel\Telescope\TelescopeServiceProvider'));
        }

        // Test Livewire
        $this->assertTrue(class_exists('Livewire\Livewire'));
    }

    /**
     * Test that service providers are registered.
     */
    public function test_service_providers_are_registered(): void
    {
        $providers = app()->getLoadedProviders();

        // Check key service providers
        $this->assertArrayHasKey('Laravel\Sanctum\SanctumServiceProvider', $providers);
        $this->assertArrayHasKey('Laravel\Cashier\CashierServiceProvider', $providers);
        $this->assertArrayHasKey('Spatie\Permission\PermissionServiceProvider', $providers);
        $this->assertArrayHasKey('Laravel\Reverb\ReverbServiceProvider', $providers);
        $this->assertArrayHasKey('Livewire\LivewireServiceProvider', $providers);
    }

    /**
     * Test that configuration files are accessible.
     */
    public function test_configuration_files_are_accessible(): void
    {
        // Test that key config files are accessible
        $this->assertNotNull(config('sanctum'));
        $this->assertNotNull(config('cashier'));
        $this->assertNotNull(config('permission'));
        $this->assertNotNull(config('reverb'));
        $this->assertNotNull(config('livewire'));
    }

    /**
     * Test that basic application routes work.
     */
    public function test_basic_application_routes(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test that database connections work.
     */
    public function test_database_connection(): void
    {
        // Test that we can connect to the database
        $this->assertDatabaseCount('users', 0);
    }
}
