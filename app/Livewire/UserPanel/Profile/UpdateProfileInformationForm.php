<?php

namespace App\Livewire\UserPanel\Profile;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $avatar;
    public bool $emailNotifications = true;
    public bool $realtimeNotifications = true;

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ensure a user profile exists
        $user->profile()->firstOrCreate([]);

        $this->name = $user->name;
        $this->email = $user->email;

        $notificationPreferences = $user->profile->notification_preferences ?? ['email' => true, 'realtime' => true];
        $this->emailNotifications = $notificationPreferences['email'] ?? true;
        $this->realtimeNotifications = $notificationPreferences['realtime'] ?? true;
    }

    public function updateProfileInformation(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:1024'],
            'emailNotifications' => ['boolean'],
            'realtimeNotifications' => ['boolean'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $profileData = [
            'notification_preferences' => [
                'email' => $this->emailNotifications,
                'realtime' => $this->realtimeNotifications,
            ],
        ];

        if ($this->avatar) {
            $profileData['avatar'] = $this->avatar->store('avatars', 'public');
        }

        // Use updateOrCreate to ensure the profile exists before updating
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id], // Attributes to use for finding the model
            $profileData // Attributes to update or create
        );

        $this->dispatch('profile-updated');
    }

    public function render()
    {
        return view('livewire.user-panel.profile.update-profile-information-form');
    }
}
