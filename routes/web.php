<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\UserPanel\PricingPage;
use App\Livewire\AdminPanel\Dashboard as AdminDashboard;
use App\Livewire\AdminPanel\Users\UserManagement;
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
use App\Livewire\AdminPanel\Categories\CategoryManagement;
use App\Livewire\AdminPanel\Posts\PostManagement;
use App\Livewire\AdminPanel\Posts\CreatePost;
use App\Livewire\AdminPanel\Posts\EditPost;
use App\Livewire\UserPanel\Posts\ShowPost;


//must change this when landing page is ready
Route::get('/', function () {
    return redirect()->route('login');
});

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
Route::post('/billing/cancel', [App\Http\Controllers\BillingController::class, 'cancel'])->middleware(['auth'])->name('billing.cancel');

Volt::route('/posts/{post:slug}', App\Livewire\UserPanel\Posts\ShowPost::class)->name('posts.show');

// Admin Panel Routes
Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/', \App\Livewire\AdminPanel\Dashboard::class)->name('dashboard');
    Route::get('/users', UserManagement::class)->name('users');
    Route::get('/posts', PostManagement::class)->name('posts.index');
    Route::get('/chat', ChannelManagement::class)->name('chat.channels');
    Route::get('/categories', CategoryManagement::class)->name('categories.index');
    Route::get('/pagos', \App\Livewire\AdminPanel\Pagos\PaymentManagement::class)->name('pagos.index');

    // Route for creating a post
    Route::get('/posts/create', \App\Livewire\AdminPanel\Posts\CreatePost::class)->name('posts.create');
    Route::get('/posts/{post}/edit', EditPost::class)->name('posts.edit');
});

require __DIR__.'/auth.php';

// Temporarily add this route for email preview
Route::get('/email-preview', function () {
    $user = App\Models\User::first(); // Or create a dummy user
    if (!$user) {
        // If no user exists, create a dummy one for testing
        $user = App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    // Instantiate the Notification
    $notification = new App\Notifications\CustomEmailVerificationNotification($user);

    // Call the toMail method on the Notification to get the Mailable instance
    $mailable = $notification->toMail($user); // The toMail method usually takes a notifiable (user) as argument

    // Render the Mailable
    return $mailable->render();
});

Route::get('/php-check', function () {
    $extensions = [
        'curl',
        'dom',
        'mbstring',
        'openssl',
        'json',
    ];
    $results = [];
    foreach ($extensions as $extension) {
        $results[$extension] = extension_loaded($extension);
    }
    return response()->json($results);
});
