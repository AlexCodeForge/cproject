<?php

namespace App\Livewire\UserPanel\Profile;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public $avatar;
    public string $name = '';
    public string $email = '';
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

        // Use updateOrCreate to ensure the profile exists before updating
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id], // Attributes to use for finding the model
            $profileData // Attributes to update or create
        );

        $this->dispatch('profile-updated');
        $this->js('window.location.reload()');
    }

    public function updatedAvatar()
    {
        Log::info('Iniciando la actualización del avatar.');

        try {
            $validated = $this->validate([
                'avatar' => ['required', 'image', 'max:1024'],
            ]);
            Log::info('Validación de avatar exitosa.');

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $path = $this->avatar->store('avatars', 'public');
            Log::info('Avatar guardado en la ruta: ' . $path);

            $user->profile()->updateOrCreate(['user_id' => $user->id], ['avatar' => $path]);
            Log::info('Perfil de usuario actualizado en la base de datos para el ID de usuario: ' . $user->id);

            $this->dispatch('profile-updated');

        } catch (ValidationException $e) {
            $errorMessage = $e->validator->errors()->first('avatar');
            Log::error('Fallo en la validación de la subida del avatar: ' . $errorMessage, ['errors' => $e->errors()]);
            $this->dispatch('show-error-modal', message: 'Error de validación: ' . $errorMessage);
        } catch (\Exception $e) {
            Log::error('Ocurrió un error inesperado durante la subida del avatar: ' . $e->getMessage());
            $this->dispatch('show-error-modal', message: 'Ocurrió un error inesperado. Por favor, inténtalo de nuevo.');
        }
    }

    public function render()
    {
        return view('livewire.user-panel.profile.update-profile-information-form');
    }
}
