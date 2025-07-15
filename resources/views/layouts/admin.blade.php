<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/shared-layout.js'])
    @livewireStyles

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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

        /* Hover and active animation for nav icons */
        .nav-item:hover .w-6.h-6.flex-shrink-0,
        .mobile-nav-item:hover .w-6.h-6 {
            transform: scale(1.1) translateY(-2px);
            color: #f97316 !important; /* orange-500 */
        }

        .nav-item.active .w-6.h-6.flex-shrink-0,
        .mobile-nav-item.active .w-6.h-6 {
            color: #f97316 !important; /* orange-500 */
        }

        .nav-item .w-6.h-6.flex-shrink-0,
        .mobile-nav-item .w-6.h-6 {
            transition: transform 0.3s ease, color 0.3s ease;
        }

        /* Footer - fixed height, never scrolls */
        footer {
            flex-shrink: 0; /* Never shrink */
            height: 40px; /* Fixed height */
            overflow: hidden; /* No scrolling */
        }

        @media (max-width: 1024px) {
            .main-content-scrollable {
                padding: 1rem;
                padding-bottom: 5rem; /* Space for mobile nav */
            }
        }
        .main-content-bg {
            --bg-pattern-color-light: #e7e5e4; /* stone-200 */
            --bg-pattern-color-dark: #374151;  /* gray-700 */
            --bg-pattern-size: 2.5rem;
            background-image: radial-gradient(var(--bg-pattern-color-light) 1px, transparent 1px);
            background-size: var(--bg-pattern-size) var(--bg-pattern-size);
            background-position: 0 0;
        }
        .dark .main-content-bg {
            background-image: none;
        }
    </style>
</head>
<body class="bg-stone-100 dark:bg-gray-900 text-slate-800 dark:text-gray-100 transition-colors duration-300">
    <div class="flex app-container h-screen">
        <!-- SIDEBAR NAVIGATION -->
        <aside id="sidebar" class="hidden lg:flex lg:flex-col h-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-r border-stone-200 dark:border-gray-700 py-4 transition-all duration-300 ease-in-out z-20">

            <div class="relative flex items-center justify-center px-4 mb-6 flex-shrink-0 h-8">
                <div id="logo-collapsed" class="text-3xl font-bold gradient-text">OR</div>
                <span id="logo-expanded" class="text-2xl font-bold gradient-text whitespace-nowrap">{{ config('app.name', 'OptionRocket') }}</span>
            </div>

            <!-- Scrollable content wrapper -->
            <div class="flex flex-col flex-grow overflow-y-auto scrollbar-hide">
                <nav id="main-nav" class="flex flex-col space-y-2 px-4 pb-4">
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <x-ionicon-bar-chart-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Admin</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <x-ionicon-people-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Usuarios</span>
                    </a>
                    <a href="#" class="nav-item flex items-center p-3 rounded-xl overflow-hidden text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all">
                        <x-ionicon-trending-up-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">P&L</span>
                    </a>

                    <a href="{{ route('admin.chat.channels') }}" class="nav-item {{ request()->routeIs('admin.chat.channels') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <x-ionicon-chatbubble-ellipses-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Chat Channels</span>
                    </a>
                    <a href="{{ route('admin.posts.index') }}" class="nav-item {{ request()->routeIs('admin.posts.index') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <x-ionicon-document-text-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Posts</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.index') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <x-ionicon-grid-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Categorías</span>
                    </a>
                    <a href="{{ route('admin.pagos.index') }}" class="nav-item {{ request()->routeIs('admin.pagos.index') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <x-ionicon-cash-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Pagos</span>
                    </a>
                    <a href="#" class="nav-item flex items-center p-3 rounded-xl overflow-hidden text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all">
                        <x-ionicon-megaphone-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Alertas</span>
                    </a>
                    <a href="#" class="nav-item flex items-center p-3 rounded-xl overflow-hidden text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all">
                        <x-ionicon-calendar-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Eventos</span>
                    </a>
                    <a href="#" class="nav-item flex items-center p-3 rounded-xl overflow-hidden text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all">
                        <x-ionicon-settings-outline class="w-6 h-6 flex-shrink-0" />
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Configuración</span>
                    </a>
                </nav>
            </div>

            <!-- Sidebar bottom controls -->
            <div class="flex flex-col space-y-2 px-4 flex-shrink-0 pt-4 border-t border-stone-200 dark:border-gray-700">
                <livewire:components.theme-toggle />
                <a href="{{ route('dashboard') }}" title="Modo Usuario" class="w-full flex items-center p-3 rounded-xl text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all overflow-hidden" wire:navigate>
                    <x-ionicon-home-outline class="w-6 h-6 flex-shrink-0" />
                    <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Usuario</span>
                </a>
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
        <div class="flex-1 main-content-wrapper relative">
            <div class="absolute inset-0 main-content-bg -z-20"></div>
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-r from-sky-200/20 to-blue-200/20 dark:from-sky-900/20 dark:to-blue-900/20 rounded-full blur-3xl -z-10"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-r from-purple-200/20 to-indigo-200/20 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-full blur-3xl -z-10"></div>
            <main class="flex-1 p-4 sm:p-8 main-content-scrollable isolate">
                {{ $slot }}
            </main>

            <!-- Page-wide overlay for modal-like elements -->
            <div id="page-overlay" class="fixed inset-0 bg-black/30 z-20 hidden"></div>

            <!-- FOOTER -->
            <footer class="flex-shrink-0 bg-white dark:bg-gray-800 border-t border-stone-200 dark:border-gray-700 text-slate-600 dark:text-gray-400 py-2 px-4 sm:px-8">
                <div class="container mx-auto flex items-center justify-between">
                    <div class="text-xs"><span class="font-bold text-slate-900 dark:text-white">OptionRocket</span> &copy; {{ date('Y') }}.</div>
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
            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-item flex flex-col items-center {{ request()->routeIs('admin.dashboard') ? 'text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400' }} p-2" wire:navigate>
                <x-ionicon-bar-chart-outline class="w-6 h-6" />
                <span class="text-xs">Admin</span>
            </a>
            <a href="{{ route('admin.users') }}" class="mobile-nav-item flex flex-col items-center {{ request()->routeIs('admin.users') ? 'text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400' }} p-2" wire:navigate>
                <x-ionicon-people-outline class="w-6 h-6" />
                <span class="text-xs">Usuarios</span>
            </a>
            <a href="#" class="mobile-nav-item flex flex-col items-center text-slate-500 dark:text-gray-400 p-2">
                <x-ionicon-trending-up-outline class="w-6 h-6" />
                <span class="text-xs">P&L</span>
            </a>
            <a href="{{ route('dashboard') }}" class="mobile-nav-item flex flex-col items-center text-slate-500 dark:text-gray-400 p-2" wire:navigate>
                <x-ionicon-home-outline class="w-6 h-6" />
                <span class="text-xs">Usuario</span>
            </a>
        </nav>
    </div>

    @livewireScripts
    <livewire:components.modals.confirmation-modal />
    <livewire:components.modals.success-modal />
    <livewire:components.modals.error-modal />
    <script>
        document.addEventListener('livewire:navigated', () => {
            // Re-initialize any JS that needs to run on page load/navigation
            // For example, if you use a tooltip library:
            // initTooltips();
        });

        document.addEventListener('livewire:init', () => {
            Livewire.on('post-created', (event) => {
                setTimeout(() => {
                    window.location.href = "{{ route('admin.posts.index') }}";
                }, 2000); // 2-second delay
            });

            Livewire.on('post-updated', (event) => {
                setTimeout(() => {
                    window.location.href = "{{ route('admin.posts.index') }}";
                }, 2000); // 2-second delay
            });
        });
    </script>
    @stack('scripts')
    @stack('modals')
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
</body>
</html>
