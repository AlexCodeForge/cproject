<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

// Use Livewire Volt for user dashboard
Volt::route('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Admin Routes - Protected by admin role (using Livewire Volt)
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('users', \App\Livewire\Admin\UserManagement::class)->name('admin.users');

    // Use Livewire Volt for admin dashboard
    Volt::route('dashboard', 'pages.admin.dashboard')->name('admin.dashboard');
});

require __DIR__.'/auth.php';
