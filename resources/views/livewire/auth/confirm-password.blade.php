<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex flex-col lg:flex-row">
    <!-- Left Side - Hero Section -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 dark:from-gray-950 dark:via-gray-900 dark:to-black relative overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute top-20 left-20 w-32 h-32 bg-amber-400/20 dark:bg-amber-300/30 rounded-full blur-2xl"></div>
        <div class="absolute bottom-32 right-16 w-48 h-48 bg-orange-400/15 dark:bg-orange-300/25 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-amber-300/10 dark:bg-amber-200/20 rounded-full blur-xl"></div>
        <div class="absolute top-10 right-10 w-16 h-16 bg-amber-500/10 dark:bg-amber-400/20 rounded-full blur-lg"></div>
        <div class="absolute bottom-10 left-10 w-20 h-20 bg-orange-500/10 dark:bg-orange-400/20 rounded-full blur-xl"></div>

        <!-- Centered Content -->
        <div class="relative z-10 flex flex-col justify-center items-center text-center text-white p-12 w-full min-h-screen">
            <!-- Logo -->
            <div class="mb-8">
                <h1 class="text-5xl font-black gradient-text dark:text-white">OptionRocket</h1>
                <div class="w-24 h-1 bg-gradient-to-r from-amber-400 to-orange-400 dark:from-amber-300 dark:to-orange-300 mx-auto mt-2 rounded-full"></div>
            </div>

            <!-- Hero Content -->
            <div class="max-w-md mx-auto">
                <h2 class="text-3xl font-bold mb-6 leading-tight text-white dark:text-gray-100">
                    Acceso Seguro
                </h2>
                <p class="text-lg text-gray-300 dark:text-gray-200 mb-8 leading-relaxed">
                    Por tu seguridad, necesitamos que confirmes tu contraseña para continuar.
                </p>
            </div>
        </div>
    </div>

    <!-- Right Side - Auth Forms -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative min-h-screen bg-stone-100 dark:bg-gray-900">
        <!-- Mobile Logo -->
        <div class="lg:hidden absolute top-4 left-4 sm:top-6 sm:left-6 z-10">
            <h1 class="text-xl sm:text-2xl font-bold gradient-text">OptionRocket</h1>
        </div>

        <!-- Theme Toggle -->
        <button id="theme-toggle" class="absolute top-4 right-4 sm:top-6 sm:right-6 lg:top-8 lg:right-8 p-2 rounded-lg text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all z-10">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
            </svg>
        </button>
        <div class="w-full max-w-md mt-12 sm:mt-16 lg:mt-0">
            <div class="text-center mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-2">Confirmar Contraseña</h2>
                <p class="text-sm sm:text-base text-slate-600 dark:text-gray-400">Esta es un área segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.</p>
            </div>

            <form wire:submit="confirmPassword" class="space-y-4 sm:space-y-6">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2">
                        Contraseña
                    </label>
                    <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        Confirmar
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
