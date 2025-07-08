<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Pages\PricingPage;

Route::view('/', 'welcome');

// Use Livewire Volt for user dashboard
Volt::route('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Volt::route('/pricing', 'pages.pricing-page')
    ->middleware(['auth'])
    ->name('pricing');

Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'checkout'])->middleware(['auth'])->name('checkout');
Route::post('/stripe/webhook', [App\Http\Controllers\WebhookController::class, 'handleWebhook'])->name('cashier.webhook');
Route::post('/billing/cancel', [App\Http\Controllers\BillingController::class, 'cancel'])->middleware(['auth'])->name('billing.cancel');

// Admin Routes - Protected by admin role (using Livewire Volt)
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('users', \App\Livewire\Admin\UserManagement::class)->name('admin.users');

    // Use Livewire Volt for admin dashboard
    Volt::route('dashboard', 'pages.admin.dashboard')->name('admin.dashboard');
});

require __DIR__.'/auth.php';
