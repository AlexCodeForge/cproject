<div>
    <section>
        <header class="flex flex-col sm:flex-row items-center gap-8 mb-6">
             <div class="flex-shrink-0 relative" x-data="{ isUploading: false, progress: 0 }"
                     x-on:livewire-upload-start="isUploading = true; console.log('Livewire upload started...')"
                     x-on:livewire-upload-finish="isUploading = false; console.log('Livewire upload finished.')"
                     x-on:livewire-upload-error="isUploading = false; console.log('Livewire upload error.')"
                     x-on:livewire-upload-progress="progress = $event.detail.progress; console.log('Livewire upload progress: ' + progress + '%')">
                    <img src="{{ auth()->user()->profile?->avatar_url ?? auth()->user()->avatar_url }}" class="w-32 h-32 rounded-full border-4 border-slate-200 dark:border-gray-600 object-cover" alt="Avatar de Usuario">
                    <input type="file" wire:model="avatar" x-ref="avatarInput" class="hidden" x-on:change="console.log('File input changed. File selected:', $event.target.files[0])">
                    <button @click="$refs.avatarInput.click()" class="absolute bottom-2 right-2 bg-slate-700 dark:bg-gray-600 text-white p-2 rounded-full hover:bg-slate-800 dark:hover:bg-gray-500 transition-colors">
                        <x-ionicon-camera class="w-6 h-6"></x-ionicon-camera>
                    </button>
                    <!-- Upload Progress Indicator -->
                    <div x-show="isUploading" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-full" style="display: none;">
                        <div class="relative w-20 h-20">
                            <svg class="absolute inset-0 w-full h-full text-white transform -rotate-90" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="54" stroke="currentColor" stroke-width="8" class="text-gray-500 opacity-50" fill="transparent" />
                                <circle cx="60" cy="60" r="54" stroke="currentColor" stroke-width="8" class="text-white" fill="transparent"
                                        :stroke-dasharray="2 * Math.PI * 54"
                                        :stroke-dashoffset="(2 * Math.PI * 54) * (1 - progress / 100)" />
                            </svg>
                            <span x-text="progress + '%'" class="absolute text-white text-lg font-bold top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></span>
                        </div>
                    </div>
                </div>
            <div class="flex-grow text-center sm:text-left">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Información del Perfil') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __("Actualiza la información de perfil de tu cuenta y tu dirección de correo electrónico.") }}
                </p>
            </div>
        </header>

        <form wire:submit.prevent="updateProfileInformation" class="mt-6 space-y-6">
            <div class="space-y-4">
                <div>
                    <x-input-label for="name" :value="__('Nombre')" />
                    <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Correo Electrónico')" />
                    <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                {{ __('Tu dirección de correo electrónico no está verificada.') }}
                                <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                    {{ __('Haz clic aquí para reenviar el correo electrónico de verificación.') }}
                                </button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                    {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Notification Preferences -->
            <div class="space-y-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Notificaciones') }}</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Elige cómo deseas recibir las notificaciones.') }}
                </p>
                <div class="flex items-center gap-6 mt-4">
                    <label for="emailNotifications" class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input wire:model="emailNotifications" id="emailNotifications" type="checkbox" class="sr-only" />
                            <div class="block bg-gray-200 dark:bg-gray-600 w-14 h-8 rounded-full"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                        </div>
                        <div class="ml-3 text-gray-700 dark:text-gray-300 font-medium">
                            {{ __('Notificaciones por Correo Electrónico') }}
                        </div>
                    </label>
                    <label for="realtimeNotifications" class="flex items-center cursor-pointer">
                         <div class="relative">
                            <input wire:model="realtimeNotifications" id="realtimeNotifications" type="checkbox" class="sr-only" />
                            <div class="block bg-gray-200 dark:bg-gray-600 w-14 h-8 rounded-full"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                        </div>
                         <div class="ml-3 text-gray-700 dark:text-gray-300 font-medium">
                            {{ __('Notificaciones en Tiempo Real') }}
                        </div>
                    </label>
                </div>
            </div>


            <div class="flex items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <x-primary-button>{{ __('Guardar Cambios') }}</x-primary-button>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Guardado.') }}
                </x-action-message>
            </div>
        </form>
    </section>
    <style>
        input:checked ~ .dot {
            transform: translateX(100%);
            background-color: #48bb78; /* green-500 */
        }
         input:checked ~ .block {
            background-color: #a7f3d0; /* green-200 */
        }
    </style>
</div>
