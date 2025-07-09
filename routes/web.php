<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\UserPanel\PricingPage;
use App\Livewire\AdminPanel\Dashboard as AdminDashboard;
use App\Livewire\AdminPanel\Users\UserManagement;
use App\Livewire\AdminPanel\Posts\CreatePost;
use App\Livewire\AdminPanel\Posts\EditPost;
use App\Livewire\AdminPanel\Posts\Index as PostIndex;
use App\Livewire\UserPanel\Posts\ShowPost;
use App\Livewire\UserPanel\Dashboard;
use App\Livewire\UserPanel\Feed;
use App\Livewire\UserPanel\Pnl;
use App\Livewire\UserPanel\Alerts;
use App\Livewire\UserPanel\Live;
use App\Livewire\UserPanel\Courses;
use App\Livewire\UserPanel\Events;
use App\Livewire\UserPanel\Profile;
use App\Livewire\UserPanel\Chat;
use App\Livewire\AdminPanel\Chat\ChannelManagement;

Route::view('/', 'welcome');

// Use Livewire Volt for user dashboard
Volt::route('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Volt::route('feed', Feed::class)
    ->middleware(['auth', 'verified'])
    ->name('feed');

Volt::route('pnl', Pnl::class)
    ->middleware(['auth', 'verified'])
    ->name('pnl');

Volt::route('alerts', Alerts::class)
    ->middleware(['auth', 'verified'])
    ->name('alerts');

Volt::route('chat', Chat::class)
    ->middleware(['auth', 'verified'])
    ->name('chat');

Volt::route('live', Live::class)
    ->middleware(['auth', 'verified'])
    ->name('live');

Volt::route('courses', Courses::class)
    ->middleware(['auth', 'verified'])
    ->name('courses');

Volt::route('events', Events::class)
    ->middleware(['auth', 'verified'])
    ->name('events');

Volt::route('profile', Profile::class)
    ->middleware(['auth'])
    ->name('profile');

Volt::route('/pricing', PricingPage::class)
    ->middleware(['auth'])
    ->name('pricing');

Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'checkout'])->middleware(['auth'])->name('checkout');
Route::post('/stripe/webhook', [App\Http\Controllers\WebhookController::class, 'handleWebhook'])->name('cashier.webhook');
Route::post('/billing/cancel', [App\Http\Controllers\BillingController::class, 'cancel'])->middleware(['auth'])->name('billing.cancel');

Route::get('/posts/{post:slug}', ShowPost::class)->name('posts.show');

// Admin Routes - Protected by admin role
Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin'])->name('admin.')->group(function () {
    Volt::route('/dashboard', AdminDashboard::class)->name('dashboard');
    Volt::route('/users', UserManagement::class)->name('users');
    Volt::route('/posts', PostIndex::class)->name('posts.index');
    Volt::route('/posts/create', CreatePost::class)->name('posts.create');
    Volt::route('/posts/{post}/edit', EditPost::class)->name('posts.edit');
    Volt::route('/chat/channels', ChannelManagement::class)->name('chat.channels');
});

require __DIR__.'/auth.php';
