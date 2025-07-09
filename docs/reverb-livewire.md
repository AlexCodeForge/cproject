# Laravel Reverb + Livewire Real-time Integration

Laravel Reverb is Laravel's official WebSocket server for real-time communication. This document outlines how to properly set up and use Reverb with Livewire for real-time features like chat, notifications, and live updates.

## Overview

**Laravel Reverb** is a first-party WebSocket server for Laravel applications that provides:
- Real-time bidirectional communication
- Built-in Laravel integration
- No external dependencies (like Pusher/Redis)
- Perfect for development and production

## 🚀 Installation & Setup

### 1. Install Reverb
```bash
composer require laravel/reverb
php artisan reverb:install
```

### 2. Configure Environment Variables
```env
# Broadcasting Configuration
BROADCAST_CONNECTION=reverb

# Reverb Server Configuration  
REVERB_APP_ID=my-app-id
REVERB_APP_KEY=my-app-key
REVERB_APP_SECRET=my-app-secret
REVERB_HOST=localhost
REVERB_PORT=9090
REVERB_SCHEME=http

# Frontend Configuration (for Vite/Echo)
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### 3. Update Broadcasting Configuration
**config/broadcasting.php**:
```php
'connections' => [
    'reverb' => [
        'driver' => 'reverb',
        'key' => env('REVERB_APP_KEY'),
        'secret' => env('REVERB_APP_SECRET'),
        'app_id' => env('REVERB_APP_ID'),
        'options' => [
            'host' => env('REVERB_HOST', '127.0.0.1'),
            'port' => env('REVERB_PORT', 443),
            'scheme' => env('REVERB_SCHEME', 'https'),
        ],
    ],
],
```

### 4. Enable Broadcasting Service Provider
**bootstrap/app.php**:
```php
return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        // ... other providers
        App\Providers\BroadcastServiceProvider::class,
    ])
    // ... rest of configuration
```

### 5. Install Frontend Dependencies
```bash
npm install --save-dev laravel-echo pusher-js
```

## 📡 Broadcasting Events

### 1. Create an Event
```bash
php artisan make:event NewChatMessage
```

### 2. Event Class Implementation
**app/Events/NewChatMessage.php**:
```php
<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ChatMessage $message
    ) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.'.$this->message->chat_channel_id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $this->message->load(['user.profile']);
        
        return [
            'message' => $this->message->toArray()
        ];
    }

    // IMPORTANT: Use default event naming (class name)
    // Do NOT define broadcastAs() for better compatibility
}
```

### 3. Trigger the Event
**In your Livewire component**:
```php
public function sendMessage(): void
{
    $message = ChatMessage::create([
        'chat_channel_id' => $this->activeChannel->id,
        'user_id' => Auth::id(),
        'message' => $this->messageText,
    ]);

    $message->load(['user.profile']);

    // Broadcast the event
    broadcast(new NewChatMessage($message));
}
```

## 🔧 Frontend Configuration

### 1. Echo Configuration
**resources/js/echo.js**:
```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
```

### 2. Import Echo in Main JS
**resources/js/app.js**:
```javascript
import './echo';
// ... other imports
```

### 3. Listening for Events in Livewire
**In your Blade template**:
```javascript
window.Echo.private(`chat.${channelId}`)
    .listen('NewChatMessage', (event) => {
        console.log('New message received:', event);
        
        // Call Livewire method to handle the message
        @this.call('handleNewMessage', event);
    });
```

### 4. Handle Events in Livewire Component
```php
public function handleNewMessage($event): void
{
    $messageData = $event['message'];

    // Prevent duplicate messages
    if ($this->chatMessages->contains('id', $messageData['id'])) {
        return;
    }

    // IMPORTANT: Fetch the actual model from database instead of creating manually
    // This prevents "Queueing collections with multiple model connections" errors
    $message = ChatMessage::with(['user.profile', 'parentMessage.user'])
        ->find($messageData['id']);
        
    if (!$message) {
        Log::error('❌ Message not found in database', ['id' => $messageData['id']]);
        return;
    }

    // Add new message to collection
    $this->chatMessages->push($message);
}
```

## 🔐 Channel Authorization

### 1. Define Authorization Logic
**routes/channels.php**:
```php
use App\Models\ChatChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{channelId}', function ($user, $channelId) {
    $channel = ChatChannel::find($channelId);

    if (! $channel) {
        return false;
    }

    // Check if user is a participant
    return $channel->participants()->where('user_id', $user->id)->exists();
});
```

## 🏃‍♂️ Running Reverb

### Development
```bash
# Start Reverb server (runs on port 9090 by default)
php artisan reverb:start

# Start in background
php artisan reverb:start --debug
```

### Production
```bash
# Install Supervisor or similar process manager
# Create a supervisor configuration for reverb:start
```

## 🐛 Troubleshooting

### Common Issues & Solutions

#### 1. "Application does not exist" Error
**Problem**: App key mismatch between backend and frontend
**Solution**: Ensure `REVERB_APP_KEY` and `VITE_REVERB_APP_KEY` match exactly

#### 2. Events Not Being Received
**Problem**: Custom broadcast names causing issues
**Solution**: Remove `broadcastAs()` method and use default Laravel class naming

```php
// ❌ DON'T: Custom broadcast names can be problematic
public function broadcastAs(): string
{
    return 'new.chat.message';
}

// ✅ DO: Use default class naming
// Listen for 'NewChatMessage' in JavaScript
```

#### 3. Channel Authorization Fails
**Problem**: User not authorized for private channels
**Solution**: Check channel authorization logic and ensure users are properly added as participants

#### 4. Connection Issues in Laragon/Local Development
**Problem**: Host/port configuration issues
**Solution**: Use exact configuration:
```env
REVERB_HOST=localhost
REVERB_PORT=9090
VITE_REVERB_HOST=localhost
VITE_REVERB_PORT=9090
```

#### 5. Duplicate Messages
**Problem**: Messages appearing multiple times
**Solution**: Implement duplicate prevention in Livewire handler:
```php
public function handleNewMessage($event): void
{
    $messageData = $event['message'];
    
    // Prevent duplicates
    if ($this->chatMessages->contains('id', $messageData['id'])) {
        return;
    }
    
    // Fetch actual model instead of creating from array
    $message = ChatMessage::with(['user.profile'])->find($messageData['id']);
    $this->chatMessages->push($message);
}
```

#### 6. "Queueing collections with multiple model connections is not supported"
**Problem**: Error occurs when manually creating models from event data
**Solution**: Always fetch models from database instead of creating them manually:
```php
// ❌ DON'T: Create models manually from event data
$message = new ChatMessage($messageData);
$message->user = new User($messageData['user']);
$this->chatMessages->push($message);

// ✅ DO: Fetch actual models from database
$message = ChatMessage::with(['user.profile'])->find($messageData['id']);
$this->chatMessages->push($message);
```
**Why**: Manually created models lack proper database connection information, causing Laravel to fail when serializing collections with mixed connection contexts.

## 🎯 Best Practices

### 1. Event Naming
- Use default Laravel class naming (remove `broadcastAs()`)
- Keep event names descriptive and consistent

### 2. Data Broadcasting
- Always load required relationships before broadcasting
- Keep broadcast data minimal but complete
- Use `ShouldBroadcastNow` for real-time events

### 3. Channel Security
- Always implement proper channel authorization
- Use private channels for sensitive data
- Validate user permissions in channel callbacks

### 4. Frontend Handling
- Implement proper error handling in Echo listeners
- Prevent duplicate message handling
- Add connection state monitoring

### 5. Model Handling in Livewire
- Always fetch models from database in event handlers
- Never create models manually from event data arrays
- Use proper relationships loading with `with()` method
- This prevents collection connection context issues

### 6. Performance
- Limit broadcast data to essential information
- Use queued broadcasting for non-critical events
- Monitor Reverb server resources in production

## 📋 Debugging Checklist

When real-time features aren't working:

1. **✅ Reverb Server Running**: Check port 9090 is active
2. **✅ Environment Variables**: Verify all REVERB_* and VITE_REVERB_* vars match
3. **✅ Event Broadcasting**: Check Laravel logs for broadcast success
4. **✅ Channel Authorization**: Verify user can access the channel
5. **✅ Echo Connection**: Check browser console for connection state
6. **✅ Event Listening**: Ensure correct event name in JavaScript
7. **✅ Model Handling**: Fetch models from database, don't create manually
8. **✅ Build Assets**: Run `npm run build` after Echo changes

## 🔄 Development Workflow

1. **Create Event**: `php artisan make:event MyEvent`
2. **Configure Event**: Set channels, implement `ShouldBroadcastNow`
3. **Set Up Authorization**: Define channel auth in `routes/channels.php`
4. **Frontend Listener**: Add Echo listener in Blade/JS
5. **Test**: Send event and verify real-time delivery
6. **Deploy**: Ensure Reverb server runs in production

---

**Remember**: Laravel Reverb uses the pusher-js library under the hood, so error messages may reference "Pusher" even when using Reverb. This is normal behavior and not an indication of misconfiguration. 
