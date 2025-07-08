<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Pages\PricingPage;

Route::view('/', 'welcome');

// Use Livewire Volt for user dashboard
Volt::route('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Volt::route('feed', 'pages.feed')
    ->middleware(['auth', 'verified'])
    ->name('feed');

Volt::route('pnl', 'pages.pnl')
    ->middleware(['auth', 'verified'])
    ->name('pnl');

Volt::route('alerts', 'pages.alerts')
    ->middleware(['auth', 'verified'])
    ->name('alerts');

Volt::route('chat', 'pages.chat')
    ->middleware(['auth', 'verified'])
    ->name('chat');

Volt::route('live', 'pages.live')
    ->middleware(['auth', 'verified'])
    ->name('live');

Volt::route('courses', 'pages.courses')
    ->middleware(['auth', 'verified'])
    ->name('courses');

Volt::route('events', 'pages.events')
    ->middleware(['auth', 'verified'])
    ->name('events');

Volt::route('profile', 'pages.profile')
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
