<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class NotificationsSidebar extends Component
{
    public $id;
    public $notifications;
    public $unreadCount = 0;
    public $isOpen = false;

    public function mount()
    {
        if (Auth::check()) {
            $this->id = Auth::id();
        }
        $this->loadNotifications();
    }

    #[On('open-notifications-sidebar')]
    public function open()
    {
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function loadNotifications()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $this->notifications = $user->notifications()->latest()->limit(10)->get();
            $this->unreadCount = $user->unreadNotifications()->count();
        } else {
            $this->notifications = collect();
            $this->unreadCount = 0;
        }

        $this->dispatch('unread-notifications-count-updated', count: $this->unreadCount);
    }

    #[On('echo-private:App.Models.User.{id},.Illuminate\Notifications\Events\BroadcastNotificationCreated')]
    public function handleNewNotification($notification)
    {
        $this->loadNotifications();
    }

    public function markAsRead($notificationId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $notification = $user->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                $this->loadNotifications();
            }
        }
    }

    public function markAllAsRead()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAsReadAndRedirect($notificationId, $url)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $notification = $user->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                return redirect($url);
            }
        }
        return redirect($url);
    }

    public function render()
    {
        return view('livewire.notifications-sidebar');
    }
}
