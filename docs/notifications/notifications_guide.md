# Notification System Implementation Guide

This guide outlines the step-by-step process for creating and implementing new real-time notifications within the application, following our established pattern.

Our system uses Laravel's built-in notification capabilities, broadcasted over a private channel with Laravel Reverb, and rendered on the frontend by a dynamic Livewire component.

## 1. Create the Notification Class

First, create a new notification class using the Artisan command. Name it descriptively based on the event (e.g., `NewComment`, `UserFollowed`).

```bash
php artisan make:notification NameOfYourNotification
```

### Example: New Post Notification

Our existing implementation for new posts is a perfect reference:
`php artisan make:notification NewPostPublished`

### Class Configuration

After creation, you must modify the generated file in `app/Notifications/`.

**File:** `app/Notifications/NewPostPublished.php`

**Key Modifications:**

- **Implement `ShouldBroadcast`:** This interface tells Laravel that this notification should be sent to the broadcasting channels (Reverb).
- **Constructor:** Pass any models or data the notification needs. In our example, we pass the `Post` model.
- **`via()` method:** This must return `['database', 'broadcast']` to ensure the notification is saved to the database and sent to the browser in real-time.
- **`toDatabase()` method:** This defines the data structure that gets stored in the `notifications` table. It's crucial to include a `message` and a `url` for the frontend component to use.
- **`toBroadcast()` method:** This defines the payload sent through Reverb. We keep this consistent by simply having it return the data from `toDatabase()`.

```php
<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewPostPublished extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(public Post $post)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'message' => "A new post '{$this->post->title}' has been published.",
            'url' => route('posts.show', $this->post->slug),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
```

## 2. Dispatch the Notification

From any controller or Livewire component where the triggering event occurs, you can send your new notification.

**Key Steps:**

- **Import `User` and `Notification`:** Import the `App\Models\User` model and the `Illuminate\Support\Facades\Notification` facade.
- **Import Your Notification Class:** Import the class you just created (e.g., `use App\Notifications\NewPostPublished;`).
- **Fetch Users:** Get a collection of users who should receive the notification. **Important:** To avoid notifying a user about their own action, use a `where()` clause to exclude the current user's ID.
- **Send Notification:** Use `Notification::send($users, new YourNotification($data));` to dispatch it.

### Example: `CreatePost` Component

**File:** `app/Livewire/AdminPanel/Posts/CreatePost.php`

In the `save()` method, after the post is created and we've confirmed its status is `'published'`, we dispatch the notification.

```php
// ... inside the save() method ...

if ($post->status === 'published') {
    // Get all users except the one who created the post
    $usersToNotify = User::where('id', '!=', Auth::id())->get();

    if ($usersToNotify->isNotEmpty()) {
        // Send the notification
        Notification::send($usersToNotify, new NewPostPublished($post));
        Log::info('Sent NewPostPublished notification to ' . $usersToNotify->count() . ' users.');
    }
}

// ...
```

## 3. Frontend Integration (Automatic)

The great news is that you do **not** need to do any extra work on the frontend. The existing `NotificationsSidebar` component is already configured to handle any new notification that follows this pattern.

**File:** `app/Livewire/NotificationsSidebar.php`

The `getListeners()` method in this component dynamically listens for a notification event on the currently authenticated user's private channel.

```php
protected function getListeners()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();
    if ($user) {
        // Listens for ANY broadcasted notification for this user
        return ["echo-private:App.Models.User.{$user->id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'handleNewNotification'];
    }
    return [];
}
```

When a new notification arrives, the `handleNewNotification()` method calls `loadNotifications()`, which refreshes the list from the database. The frontend then automatically updates with the new notification, complete with its message and link, thanks to the data you defined in the `toDatabase()` method. 
