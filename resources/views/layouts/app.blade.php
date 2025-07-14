<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/shared-layout.js'])
    @livewireStyles
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* --- DISCORD-LIKE FIXED LAYOUT --- */
        /* Main app container with fixed height */
        .app-container {
            height: 100vh;
            overflow: hidden; /* Prevent any scrolling on the main container */
        }

        /* Sidebar - fixed width and height with hidden scrollbar */
        #sidebar {
            flex-shrink: 0; /* Never shrink */
            overflow-y: auto; /* Allow vertical scrolling when content exceeds height */
            overflow-x: hidden; /* Prevent horizontal scrolling */
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* Internet Explorer 10+ */
            transition: all 0.3s ease-in-out;
            will-change: width;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        #sidebar::-webkit-scrollbar {
            display: none;
        }

        /* Define scrollbar-hide utility class */
        .scrollbar-hide {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none;  /* IE 10+ */
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;  /* Chrome, Safari, Opera */
        }

        /* Main content wrapper - takes remaining space */
        .main-content-wrapper {
            height: 100vh; /* Full viewport height */
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Prevent wrapper from scrolling */
        }

        /* Main content area - fixed height with independent scrolling */
        .main-content-scrollable {
            flex: 1; /* Take all available space between header and footer */
            overflow-y: auto; /* Only this area scrolls */
            overflow-x: hidden; /* Prevent horizontal scrolling */
            contain: layout style; /* Isolate layout from other elements */
            padding: 2rem;
        }

        /* Footer - fixed height, never scrolls */
        footer {
            flex-shrink: 0; /* Never shrink */
            height: 40px; /* Fixed height */
            overflow: hidden; /* No scrolling */
        }

        /* --- STYLES FOR EXPANDABLE SIDEBAR --- */
        #sidebar.is-expanded {
            width: 16rem; /* w-64 */
        }

        #sidebar.is-expanded .nav-text {
            opacity: 1;
            pointer-events: auto;
            transition-delay: 150ms;
        }

        #sidebar .nav-text {
            transition: opacity 0.3s ease;
            will-change: opacity;
        }

        /* --- INITIAL STATE: EXPANDED --- */
        #sidebar {
            width: 16rem; /* Start expanded */
        }

        #sidebar .nav-text {
            opacity: 1; /* Text visible initially */
            pointer-events: auto;
        }

        /* --- COLLAPSED STATE --- */
        #sidebar.is-collapsed {
            width: 5rem; /* w-20 */
        }

        #sidebar.is-collapsed .nav-text {
            opacity: 0;
            pointer-events: none;
        }

        /* --- LOGO ANIMATION STYLES --- */
        #logo-collapsed, #logo-expanded {
            position: absolute;
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        #logo-collapsed {
            opacity: 0; /* Start with expanded logo visible */
            transform: scale(0.8);
        }

        #logo-expanded {
            opacity: 1; /* Start visible */
            transform: scale(1);
        }

        #sidebar.is-collapsed #logo-collapsed {
            opacity: 1;
            transform: scale(1);
        }

        #sidebar.is-collapsed #logo-expanded {
            opacity: 0;
            transform: scale(0.8);
        }

        #sidebar.is-expanded #logo-collapsed {
            opacity: 0;
            transform: scale(0.8);
        }

        #sidebar.is-expanded #logo-expanded {
            opacity: 1;
            transform: scale(1);
            transition-delay: 0.1s; /* Delay for a smoother effect */
        }

        /* Navigation transitions */
        .nav-item, .mobile-nav-item {
            transition: all 0.3s ease;
        }

        @media (max-width: 1024px) {
            .main-content-scrollable {
                padding: 1rem;
                padding-bottom: 5rem; /* Space for mobile nav */
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-stone-100 dark:bg-gray-900 text-slate-800 dark:text-gray-100 transition-colors duration-300">

    <div class="flex app-container h-screen">
        <!-- =================================================================== -->
        <!-- COMPONENT: EXPANDABLE SIDEBAR NAVIGATION                            -->
        <!-- =================================================================== -->
        <aside id="sidebar" class="hidden lg:flex lg:flex-col h-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-r border-stone-200 dark:border-gray-700 py-4 transition-all duration-300 ease-in-out z-20">

            <div class="relative flex items-center justify-center px-4 mb-6 flex-shrink-0 h-8">
                <div id="logo-collapsed" class="text-3xl font-bold gradient-text">OR</div>
                <span id="logo-expanded" class="text-2xl font-bold gradient-text whitespace-nowrap">{{ config('app.name', 'OptionRocket') }}</span>
            </div>

            <!-- Scrollable content wrapper -->
            <div class="flex flex-col flex-grow overflow-y-auto scrollbar-hide">
                <!-- User Navigation -->
                <nav id="main-nav" class="flex flex-col space-y-2 px-4 pb-4">
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <x-ionicon-grid-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Dashboard</span>
                    </a>
                    <a href="{{ route('feed') }}" class="nav-item flex items-center p-3 rounded-xl overflow-hidden text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all">
                        <x-ionicon-newspaper-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Feed</span>
                    </a>
                    <a href="#" class="nav-item flex items-center p-3 rounded-xl overflow-hidden text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all">
                        <x-ionicon-trending-up-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">P&L</span>
                    </a>
                    <a href="#" class="nav-item flex items-center p-3 rounded-xl overflow-hidden text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all">
                        <x-ionicon-alert-circle-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Alertas</span>
                    </a>
                    <a href="{{ route('chat') }}" class="nav-item {{ request()->routeIs('chat') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <div class="relative">
                            <x-ionicon-chatbubbles-outline class="w-6 h-6 flex-shrink-0" />
                            <span class="absolute top-0 right-0 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                            </span>
                        </div>
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Chat</span>
                    </a>
                    <a href="#" class="nav-item flex items-center p-3 rounded-xl overflow-hidden text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all">
                        <x-ionicon-videocam-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">En Vivo</span>
                    </a>
                    <a href="#" class="nav-item flex items-center p-3 rounded-xl overflow-hidden text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all">
                        <x-ionicon-school-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Cursos</span>
                    </a>
                    <a href="#" class="nav-item flex items-center p-3 rounded-xl overflow-hidden text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all">
                        <x-ionicon-calendar-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Eventos</span>
                    </a>
                    @auth
                        @unless(auth()->user()->hasRole('premium') && !auth()->user()->hasRole('admin'))
                            <a href="{{ route('pricing') }}" class="nav-item {{ request()->routeIs('pricing') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                                <x-ionicon-cash-outline class="w-6 h-6 flex-shrink-0" />
                                <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Precios</span>
                            </a>
                        @endunless
                    @else
                        <a href="{{ route('pricing') }}" class="nav-item {{ request()->routeIs('pricing') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                            <x-ionicon-cash-outline class="w-6 h-6 flex-shrink-0" />
                            <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Precios</span>
                        </a>
                    @endauth



                    <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <x-ionicon-person-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Perfil</span>
                    </a>

                    @auth
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center p-3 rounded-xl overflow-hidden text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all" wire:navigate>
                                <x-ionicon-shield-checkmark-outline class="w-6 h-6 flex-shrink-0" />
                                <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Admin Panel</span>
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>

            <!-- Sidebar bottom controls -->
            <div class="flex flex-col space-y-2 px-4 flex-shrink-0 pt-4 border-t border-stone-200 dark:border-gray-700">
                <button id="theme-toggle" title="Cambiar Tema" class="w-full flex items-center p-3 rounded-xl text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all overflow-hidden">
                    <x-ionicon-moon-outline class="w-6 h-6 flex-shrink-0" />
                    <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Tema</span>
                </button>
                <button id="notifications-toggle"
                        title="Notificaciones"
                        class="w-full flex items-center p-3 rounded-xl text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all overflow-hidden"
                        x-data="{ unreadCount: {{ auth()->check() ? auth()->user()->unreadNotifications()->count() : 0 }} }"
                        @unread-notifications-count-updated.window="unreadCount = $event.detail.count"
                        x-on:click="$dispatch('open-notifications-sidebar')">
                    <div class="relative">
                        <x-ionicon-notifications-outline class="w-6 h-6 flex-shrink-0" />
                        <span x-show="unreadCount > 0" class="absolute top-0 right-0 flex h-2.5 w-2.5" style="display: none;">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                        </span>
                    </div>
                    <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Notificaciones</span>
                </button>
                <div class="nav-item">
                    <livewire:actions.logout />
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 main-content-wrapper">
            <main class="main-content-scrollable">
                {{ $slot }}
            </main>

            <!-- Page-wide overlay for modal-like elements -->
            <div id="page-overlay" class="fixed inset-0 bg-black/30 z-20 hidden"></div>

            <!-- FOOTER -->
            <footer class="flex-shrink-0 bg-white dark:bg-gray-800 border-t border-stone-200 dark:border-gray-700 text-slate-600 dark:text-gray-400 py-2 px-4 sm:px-8 h-10">
                <div class="container mx-auto flex items-center justify-between">
                    <div class="text-xs"><span class="font-bold text-slate-900 dark:text-white">{{ config('app.name', 'OptionRocket') }}</span> &copy; {{ date('Y') }}.</div>
                    <div class="flex items-center gap-3">
                        <a href="#" class="text-xs hover:text-slate-900 dark:hover:text-gray-200">Términos</a>
                        <a href="#" class="text-xs hover:text-slate-900 dark:hover:text-gray-200">Privacidad</a>
                    </div>
                </div>
            </footer>
        </div>

        <!-- =================================================================== -->
        <!-- COMPONENT: NOTIFICATIONS SIDEBAR (OVERLAY)                          -->
        <!-- =================================================================== -->
        <livewire:notifications-sidebar />

        <!-- MOBILE BOTTOM NAVIGATION -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-t border-stone-200 dark:border-gray-700 flex justify-around py-2 z-10">
            <a href="{{ route('dashboard') }}" class="mobile-nav-item flex flex-col items-center {{ request()->routeIs('dashboard') ? 'text-slate-700 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400' }} p-2">
                <x-ionicon-grid-outline class="w-6 h-6" />
                <span class="text-xs {{ request()->routeIs('dashboard') ? 'font-bold' : '' }}">Inicio</span>
            </a>
            <a href="#" class="mobile-nav-item flex flex-col items-center text-slate-500 dark:text-gray-400 p-2">
                <x-ionicon-newspaper-outline class="w-6 h-6" />
                <span class="text-xs">Feed</span>
            </a>
            <a href="#" class="mobile-nav-item flex flex-col items-center text-slate-500 dark:text-gray-400 p-2">
                <x-ionicon-trending-up-outline class="w-6 h-6" />
                <span class="text-xs">P&L</span>
            </a>
            <a href="#" class="mobile-nav-item flex flex-col items-center text-slate-500 dark:text-gray-400 p-2">
                <x-ionicon-alert-circle-outline class="w-6 h-6" />
                <span class="text-xs">Alertas</span>
            </a>
            @auth
                @unless(auth()->user()->hasRole('premium') && !auth()->user()->hasRole('admin'))
                    <a href="{{ route('pricing') }}" class="mobile-nav-item flex flex-col items-center {{ request()->routeIs('pricing') ? 'text-slate-700 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400' }} p-2">
                        <x-ionicon-cash-outline class="w-6 h-6" />
                        <span class="text-xs {{ request()->routeIs('pricing') ? 'font-bold' : '' }}">Precios</span>
                    </a>
                @endunless
            @else
                <a href="{{ route('pricing') }}" class="mobile-nav-item flex flex-col items-center {{ request()->routeIs('pricing') ? 'text-slate-700 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400' }} p-2">
                    <x-ionicon-cash-outline class="w-6 h-6" />
                    <span class="text-xs {{ request()->routeIs('pricing') ? 'font-bold' : '' }}">Precios</span>
                </a>
            @endauth
            <a href="{{ route('profile') }}" class="mobile-nav-item flex flex-col items-center {{ request()->routeIs('profile') ? 'text-slate-700 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400' }} p-2">
                <x-ionicon-person-outline class="w-6 h-6" />
                <span class="text-xs {{ request()->routeIs('profile') ? 'font-bold' : '' }}">Perfil</span>
            </a>
        </nav>

        <div id="overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden"></div>

    </div>

    <!-- Modals -->
    <livewire:components.modals.confirmation-modal />
    <livewire:components.modals.error-modal />
    <livewire:components.modals.success-modal />
    <livewire:components.modals.premium-modal />

    @livewireScripts
    @stack('scripts')
</body>
</html>
