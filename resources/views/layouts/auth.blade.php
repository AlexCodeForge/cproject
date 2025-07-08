<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'OptionRocket - Autenticación' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
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
                    <h1 class="text-5xl font-black text-white">OptionRocket</h1>
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
                                <svg class="w-4 h-4 text-amber-400 dark:text-amber-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-gray-300 dark:text-gray-200">Alertas de trading en tiempo real</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-amber-400/20 dark:bg-amber-400/30 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-400 dark:text-amber-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-300 dark:text-gray-200">Comunidad de traders expertos</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-amber-400/20 dark:bg-amber-400/30 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-400 dark:text-amber-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                </svg>
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
                <h1 class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-amber-400 to-orange-400 bg-clip-text text-transparent">OptionRocket</h1>
            </div>

            <!-- Theme Toggle -->
            <button id="theme-toggle" class="absolute top-4 right-4 sm:top-6 sm:right-6 lg:top-8 lg:right-8 p-2 rounded-lg text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all z-10">
                <svg class="w-6 h-6 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                </svg>
                <svg class="w-6 h-6 block dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
            </button>

            <!-- Main Content Area -->
            {{ $slot }}
        </div>
    </div>

    @livewireScripts

    <!-- Custom JavaScript -->
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
        });
    </script>
</body>
</html>
