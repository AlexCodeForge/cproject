<div>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Información del Perfil') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Actualiza la información de perfil de tu cuenta, dirección de correo electrónico y avatar.") }}
            </p>
        </header>

        <form wire:submit.prevent="updateProfileInformation" class="mt-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Avatar Upload -->
                <div class="md:col-span-1 flex flex-col items-center space-y-4">
                    <x-input-label for="avatar" value="{{ __('Avatar') }}" class="sr-only" />

                    <div x-data="{ photoName: null, photoPreview: null }" class="w-full">
                        <input type="file" class="hidden"
                               wire:model="avatar"
                               x-ref="photo"
                               x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                               " />

                        <!-- Current Avatar -->
                        <div class="mt-2" x-show="!photoPreview">
                            <img src="{{ auth()->user()->profile?->avatar_url ?? auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="rounded-full h-32 w-32 object-cover mx-auto">
                        </div>

                        <!-- New Avatar Preview -->
                        <div class="mt-2" x-show="photoPreview" style="display: none;">
                            <span class="block rounded-full w-32 h-32 bg-cover bg-no-repeat bg-center mx-auto"
                                  x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                            </span>
                        </div>

                        <x-secondary-button class="mt-4 w-full justify-center" type="button" x-on:click.prevent="$refs.photo.click()">
                            {{ __('Seleccionar Nuevo Avatar') }}
                        </x-secondary-button>

                        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    </div>
                </div>

                <!-- Name and Email -->
                <div class="md:col-span-3 space-y-4">
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
