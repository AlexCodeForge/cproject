<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Iniciar Sesión</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Ionicons via CDN -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-text {
            background: linear-gradient(135deg, #f59e0b, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .auth-form {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.3s ease-in-out;
        }
        .auth-form.hidden {
            opacity: 0;
            transform: translateY(20px);
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-stone-100 dark:bg-gray-900 text-slate-800 dark:text-gray-100 transition-colors duration-300 font-sans">

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
                    <h1 class="text-5xl font-black gradient-text dark:text-white">{{ config('app.name', 'OptionRocket') }}</h1>
                    <div class="w-24 h-1 bg-gradient-to-r from-amber-400 to-orange-400 dark:from-amber-300 dark:to-orange-300 mx-auto mt-2 rounded-full"></div>
                </div>

                <!-- Hero Content -->
                <div class="max-w-md mx-auto">
                    <h2 class="text-3xl font-bold mb-6 leading-tight text-white dark:text-gray-100">
                        Lanza tu Trading a la
                        <span class="bg-gradient-to-r from-amber-400 to-orange-400 dark:from-amber-300 dark:to-orange-300 bg-clip-text text-transparent">
                            Estratosfera
                        </span>
                    </h2>
                    <p class="text-lg text-gray-300 dark:text-gray-200 mb-8 leading-relaxed">
                        Únete a miles de traders que ya confían en OptionRocket para sus inversiones y análisis de mercado.
                    </p>

                    <!-- Features -->
                    <div class="space-y-4 text-left">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-amber-400/20 dark:bg-amber-400/30 rounded-full flex items-center justify-center">
                                <ion-icon name="trending-up" class="text-amber-400 dark:text-amber-300"></ion-icon>
                            </div>
                            <span class="text-gray-300 dark:text-gray-200">Alertas de trading en tiempo real</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-amber-400/20 dark:bg-amber-400/30 rounded-full flex items-center justify-center">
                                <ion-icon name="people" class="text-amber-400 dark:text-amber-300"></ion-icon>
                            </div>
                            <span class="text-gray-300 dark:text-gray-200">Comunidad de traders expertos</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-amber-400/20 dark:bg-amber-400/30 rounded-full flex items-center justify-center">
                                <ion-icon name="school" class="text-amber-400 dark:text-amber-300"></ion-icon>
                            </div>
                            <span class="text-gray-300 dark:text-gray-200">Cursos y análisis premium</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Auth Forms -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative min-h-screen">
            <!-- Mobile Logo -->
            <div class="lg:hidden absolute top-4 left-4 sm:top-6 sm:left-6 z-10">
                <h1 class="text-xl sm:text-2xl font-bold gradient-text">{{ config('app.name', 'OptionRocket') }}</h1>
            </div>

            <!-- Theme Toggle -->
            <button id="theme-toggle" class="absolute top-4 right-4 sm:top-6 sm:right-6 lg:top-8 lg:right-8 p-2 rounded-lg text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all z-10">
                <ion-icon name="moon-outline" class="text-xl sm:text-2xl"></ion-icon>
            </button>

            <div class="w-full max-w-md mt-12 sm:mt-16 lg:mt-0">
                <!-- Login Form -->
                <div id="login-form" class="auth-form">
                    <div class="text-center mb-6 sm:mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-2">Bienvenido de vuelta</h2>
                        <p class="text-sm sm:text-base text-slate-600 dark:text-gray-400">Inicia sesión para continuar con tu trading</p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-6">
                        @csrf

                        <!-- Email Input -->
                        <div>
                            <label for="login-email" class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2">
                                Correo Electrónico
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <ion-icon name="mail-outline" class="text-slate-400 text-lg"></ion-icon>
                                </div>
                                <input
                                    type="email"
                                    id="login-email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="w-full pl-10 pr-4 py-3 border border-stone-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-sm sm:text-base"
                                    placeholder="tu@email.com"
                                    required
                                    autofocus
                                    autocomplete="username"
                                >
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label for="login-password" class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2">
                                Contraseña
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <ion-icon name="lock-closed-outline" class="text-slate-400 text-lg"></ion-icon>
                                </div>
                                <input
                                    type="password"
                                    id="login-password"
                                    name="password"
                                    class="w-full pl-10 pr-12 py-3 border border-stone-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-sm sm:text-base"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="current-password"
                                >
                                <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <ion-icon name="eye-outline" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-200 text-lg"></ion-icon>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                            <label class="flex items-center">
                                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-amber-500 bg-white dark:bg-gray-700 border-stone-300 dark:border-gray-600 rounded focus:ring-amber-500 focus:ring-2">
                                <span class="ml-2 text-sm text-slate-600 dark:text-gray-400">Recordarme</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 font-semibold">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2"
                        >
                            <ion-icon name="log-in-outline" class="text-lg"></ion-icon>
                            <span class="text-sm sm:text-base">Iniciar Sesión</span>
                        </button>

                        <!-- Social Login -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-stone-300 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-stone-100 dark:bg-gray-900 text-slate-500 dark:text-gray-400">O continúa con</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" class="flex items-center justify-center gap-2 px-3 py-2 border border-stone-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-stone-50 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                <span class="text-xs sm:text-sm font-medium">Google</span>
                            </button>
                            <button type="button" class="flex items-center justify-center gap-2 px-3 py-2 border border-stone-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-stone-50 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span class="text-xs sm:text-sm font-medium">Facebook</span>
                            </button>
                        </div>
                    </form>

                    <!-- Switch to Signup -->
                    <div class="mt-6 sm:mt-8 text-center">
                        <p class="text-sm sm:text-base text-slate-600 dark:text-gray-400">
                            ¿No tienes una cuenta?
                            <button id="show-signup" class="text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 font-semibold transition-colors">
                                Regístrate gratis
                            </button>
                        </p>
                    </div>
                </div>

                <!-- Signup Form -->
                <div id="signup-form" class="auth-form hidden">
                    <div class="text-center mb-6 sm:mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-2">Crear cuenta</h2>
                        <p class="text-sm sm:text-base text-slate-600 dark:text-gray-400">Únete a la comunidad de OptionRocket</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-6">
                        @csrf

                        <!-- Name Input -->
                        <div>
                            <label for="signup-name" class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2">
                                Nombre Completo
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <ion-icon name="person-outline" class="text-slate-400 text-lg"></ion-icon>
                                </div>
                                <input
                                    type="text"
                                    id="signup-name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="w-full pl-10 pr-4 py-3 border border-stone-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-sm sm:text-base"
                                    placeholder="Juan Pérez"
                                    required
                                    autofocus
                                    autocomplete="name"
                                >
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Input -->
                        <div>
                            <label for="signup-email" class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2">
                                Correo Electrónico
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <ion-icon name="mail-outline" class="text-slate-400 text-lg"></ion-icon>
                                </div>
                                <input
                                    type="email"
                                    id="signup-email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="w-full pl-10 pr-4 py-3 border border-stone-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-sm sm:text-base"
                                    placeholder="tu@email.com"
                                    required
                                    autocomplete="username"
                                >
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label for="signup-password" class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2">
                                Contraseña
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <ion-icon name="lock-closed-outline" class="text-slate-400 text-lg"></ion-icon>
                                </div>
                                <input
                                    type="password"
                                    id="signup-password"
                                    name="password"
                                    class="w-full pl-10 pr-12 py-3 border border-stone-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-sm sm:text-base"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="new-password"
                                >
                                <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <ion-icon name="eye-outline" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-200 text-lg"></ion-icon>
                                </button>
                            </div>
                            <div class="mt-1 text-xs text-slate-500 dark:text-gray-400">
                                Mínimo 8 caracteres con letras y números
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password Input -->
                        <div>
                            <label for="signup-password-confirmation" class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2">
                                Confirmar Contraseña
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <ion-icon name="lock-closed-outline" class="text-slate-400 text-lg"></ion-icon>
                                </div>
                                <input
                                    type="password"
                                    id="signup-password-confirmation"
                                    name="password_confirmation"
                                    class="w-full pl-10 pr-12 py-3 border border-stone-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-sm sm:text-base"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="new-password"
                                >
                                <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <ion-icon name="eye-outline" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-200 text-lg"></ion-icon>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Terms & Privacy -->
                        <div class="flex items-start gap-3">
                            <div class="flex items-center h-5 mt-0.5">
                                <input type="checkbox" name="terms" class="w-4 h-4 text-amber-500 bg-white dark:bg-gray-700 border-stone-300 dark:border-gray-600 rounded focus:ring-amber-500 focus:ring-2" required>
                            </div>
                            <div class="text-sm">
                                <span class="text-slate-600 dark:text-gray-400">
                                    Acepto los
                                    <a href="#" class="text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 font-semibold">Términos de Servicio</a>
                                    y la
                                    <a href="#" class="text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 font-semibold">Política de Privacidad</a>
                                </span>
                            </div>
                        </div>

                        <!-- Signup Button -->
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2"
                        >
                            <ion-icon name="rocket-outline" class="text-lg"></ion-icon>
                            <span class="text-sm sm:text-base">Crear Cuenta</span>
                        </button>

                        <!-- Social Signup -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-stone-300 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-stone-100 dark:bg-gray-900 text-slate-500 dark:text-gray-400">O regístrate con</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" class="flex items-center justify-center gap-2 px-3 py-2 border border-stone-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-stone-50 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                <span class="text-xs sm:text-sm font-medium">Google</span>
                            </button>
                            <button type="button" class="flex items-center justify-center gap-2 px-3 py-2 border border-stone-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-stone-50 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span class="text-xs sm:text-sm font-medium">Facebook</span>
                            </button>
                        </div>
                    </form>

                    <!-- Switch to Login -->
                    <div class="mt-6 sm:mt-8 text-center">
                        <p class="text-sm sm:text-base text-slate-600 dark:text-gray-400">
                            ¿Ya tienes una cuenta?
                            <button id="show-login" class="text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 font-semibold transition-colors">
                                Inicia sesión
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom JavaScript for Auth Page -->
    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;

        // Check for saved theme preference or default to light
        const currentTheme = localStorage.getItem('theme') || 'light';
        html.classList.toggle('dark', currentTheme === 'dark');

        themeToggle.addEventListener('click', () => {
            const isDark = html.classList.contains('dark');
            html.classList.toggle('dark', !isDark);
            localStorage.setItem('theme', !isDark ? 'dark' : 'light');

            // Update icon
            const icon = themeToggle.querySelector('ion-icon');
            icon.setAttribute('name', !isDark ? 'sunny-outline' : 'moon-outline');
        });

        // Form Switching
        const loginForm = document.getElementById('login-form');
        const signupForm = document.getElementById('signup-form');
        const showSignupBtn = document.getElementById('show-signup');
        const showLoginBtn = document.getElementById('show-login');

        showSignupBtn.addEventListener('click', () => {
            loginForm.classList.add('hidden');
            signupForm.classList.remove('hidden');
            // Focus on first input of signup form
            document.getElementById('signup-name').focus();
        });

        showLoginBtn.addEventListener('click', () => {
            signupForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
            // Focus on first input of login form
            document.getElementById('login-email').focus();
        });

        // Password Toggle Visibility
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const passwordInput = button.parentElement.querySelector('input[type="password"], input[type="text"]');
                const icon = button.querySelector('ion-icon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.setAttribute('name', 'eye-off-outline');
                } else {
                    passwordInput.type = 'password';
                    icon.setAttribute('name', 'eye-outline');
                }
            });
        });

        // Enhanced form animations
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('ring-2', 'ring-amber-500');
            });

            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('ring-2', 'ring-amber-500');
            });
        });

        // Handle validation errors - show appropriate form
        @if($errors->any())
            @if(old('name') || old('password_confirmation'))
                // Show signup form if registration errors
                document.addEventListener('DOMContentLoaded', () => {
                    loginForm.classList.add('hidden');
                    signupForm.classList.remove('hidden');
                });
            @endif
        @endif
    </script>
</body>
</html>
