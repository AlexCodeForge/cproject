<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

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

    <!-- Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

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
                        <ion-icon name="grid-outline" class="text-2xl flex-shrink-0"></ion-icon>
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Dashboard</span>
                    </a>
                    <a href="{{ route('feed') }}" class="nav-item {{ request()->routeIs('feed') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <ion-icon name="newspaper-outline" class="text-2xl flex-shrink-0"></ion-icon>
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Feed</span>
                    </a>
                    <a href="{{ route('pnl') }}" class="nav-item {{ request()->routeIs('pnl') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <ion-icon name="trending-up-outline" class="text-2xl flex-shrink-0"></ion-icon>
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">P&L</span>
                    </a>
                    <a href="{{ route('alerts') }}" class="nav-item {{ request()->routeIs('alerts') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <ion-icon name="alert-circle-outline" class="text-2xl flex-shrink-0"></ion-icon>
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Alertas</span>
                    </a>
                    <a href="{{ route('chat') }}" class="nav-item {{ request()->routeIs('chat') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <div class="relative">
                            <ion-icon name="chatbubbles-outline" class="text-2xl flex-shrink-0"></ion-icon>
                            @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="absolute top-0 right-0 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                            </span>
                            @endif
                        </div>
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Chat</span>
                    </a>
                    <a href="{{ route('live') }}" class="nav-item {{ request()->routeIs('live') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <ion-icon name="videocam-outline" class="text-2xl flex-shrink-0"></ion-icon>
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">En Vivo</span>
                    </a>
                    <a href="{{ route('courses') }}" class="nav-item {{ request()->routeIs('courses') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <ion-icon name="school-outline" class="text-2xl flex-shrink-0"></ion-icon>
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Cursos</span>
                    </a>
                    <a href="{{ route('events') }}" class="nav-item {{ request()->routeIs('events') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <ion-icon name="calendar-outline" class="text-2xl flex-shrink-0"></ion-icon>
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Eventos</span>
                    </a>
                    <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active bg-stone-200 dark:bg-gray-700 text-slate-800 dark:text-gray-200' : 'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200' }} flex items-center p-3 rounded-xl overflow-hidden transition-all" wire:navigate>
                        <ion-icon name="person-outline" class="text-2xl flex-shrink-0"></ion-icon>
                        <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Perfil</span>
                    </a>

                    @auth
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center p-3 rounded-xl overflow-hidden text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all" wire:navigate>
                                <ion-icon name="shield-checkmark-outline" class="text-2xl flex-shrink-0"></ion-icon>
                                <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Admin Panel</span>
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>

            <!-- Sidebar bottom controls -->
            <div class="flex flex-col space-y-2 px-4 flex-shrink-0 pt-4 border-t border-stone-200 dark:border-gray-700">
                <button id="theme-toggle" title="Cambiar Tema" class="w-full flex items-center p-3 rounded-xl text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all overflow-hidden">
                    <ion-icon name="moon-outline" class="text-2xl flex-shrink-0"></ion-icon>
                    <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Tema</span>
                </button>
                <button id="notifications-toggle" title="Notificaciones" class="w-full flex items-center p-3 rounded-xl text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all overflow-hidden">
                    <div class="relative">
                        <ion-icon name="notifications-outline" class="text-2xl flex-shrink-0"></ion-icon>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span class="absolute top-0 right-0 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                        </span>
                        @endif
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
        <aside id="notifications-sidebar" class="fixed top-0 right-0 h-full w-80 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-l border-stone-200 dark:border-gray-700 p-6 transform translate-x-full transition-transform duration-300 ease-in-out z-30">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Notificaciones</h2>
                <button id="close-notifications" class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200 text-2xl">&times;</button>
            </div>
            <div class="space-y-4">
                <div class="flex items-start space-x-3 bg-stone-100/50 dark:bg-gray-700/50 p-3 rounded-lg">
                    <ion-icon name="trending-up-outline" class="text-green-600 text-2xl mt-1"></ion-icon>
                    <div>
                        <p class="text-sm text-slate-800 dark:text-gray-200">Nueva alerta de compra para <span class="font-bold">NVDA</span>.</p>
                        <p class="text-xs text-slate-500 dark:text-gray-400">Hace 5 minutos</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 p-3 rounded-lg">
                    <ion-icon name="chatbubble-ellipses-outline" class="text-blue-600 text-2xl mt-1"></ion-icon>
                    <div>
                        <p class="text-sm text-slate-800 dark:text-gray-200"><span class="font-bold">Ana Torres</span> te mencionó en <span class="font-bold"># general</span>.</p>
                        <p class="text-xs text-slate-500 dark:text-gray-400">Hace 20 minutos</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- =================================================================== -->
        <!-- COMPONENT: MOBILE BOTTOM NAVIGATION                                 -->
        <!-- =================================================================== -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-t border-stone-200 dark:border-gray-700 flex justify-around py-2 z-30">
            <a href="{{ route('dashboard') }}" class="mobile-nav-item flex flex-col items-center justify-center w-16 text-xs gap-1 {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-gray-400' }}">
                <ion-icon name="grid" class="text-2xl"></ion-icon>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('feed') }}" class="mobile-nav-item flex flex-col items-center justify-center w-16 text-xs gap-1 {{ request()->routeIs('feed') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-gray-400' }}">
                <ion-icon name="newspaper-outline" class="text-2xl"></ion-icon>
                <span>Feed</span>
            </a>
            <a href="{{ route('alerts') }}" class="mobile-nav-item flex flex-col items-center justify-center w-16 text-xs gap-1 {{ request()->routeIs('alerts') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-gray-400' }}">
                <ion-icon name="alert-circle-outline" class="text-2xl"></ion-icon>
                <span>Alertas</span>
            </a>
            <a href="{{ route('chat') }}" class="mobile-nav-item flex flex-col items-center justify-center w-16 text-xs gap-1 {{ request()->routeIs('chat') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-gray-400' }}">
                <div class="relative">
                    <ion-icon name="chatbubbles-outline" class="text-2xl"></ion-icon>
                    {{-- This will be dynamic later --}}
                    <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                    </span>
                </div>
                <span>Chat</span>
            </a>
            <a href="{{ route('profile') }}" class="mobile-nav-item flex flex-col items-center justify-center w-16 text-xs gap-1 {{ request()->routeIs('profile') ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500 dark:text-gray-400' }}">
                <ion-icon name="person-outline" class="text-2xl"></ion-icon>
                <span>Perfil</span>
            </a>
        </nav>
    </div>

    @stack('scripts')

    <!-- Unified Layout JavaScript -->
    <script>
        // Global state for layout functionality
        window.OptionRocketLayout = {
            initialized: false,
            eventListeners: new Map(),
        };

        function initializeLayoutFunctionality() {
            // Prevent multiple initializations
            if (window.OptionRocketLayout.initialized) {
                cleanupEventListeners();
            }

            // Auto-collapse sidebar on page load with a delay for visual effect
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                // Add sidebar hover functionality
                setupSidebarEvents(sidebar);
            }

            // Theme toggle functionality
            setupThemeToggle();

            // Notifications functionality
            setupNotifications();

            // Mark as initialized
            window.OptionRocketLayout.initialized = true;
        }

        function cleanupEventListeners() {
            // Remove all tracked event listeners
            window.OptionRocketLayout.eventListeners.forEach((listener, element) => {
                if (element && listener) {
                    element.removeEventListener(listener.event, listener.handler);
                }
            });
            window.OptionRocketLayout.eventListeners.clear();
        }

        function addTrackedEventListener(element, event, handler) {
            if (element) {
                // Remove existing listener if any
                const existing = window.OptionRocketLayout.eventListeners.get(element);
                if (existing && existing.event === event) {
                    element.removeEventListener(existing.event, existing.handler);
                }

                // Add new listener
                element.addEventListener(event, handler);
                window.OptionRocketLayout.eventListeners.set(element, { event, handler });
            }
        }

        function setupSidebarEvents(sidebar) {
            addTrackedEventListener(sidebar, 'mouseenter', function() {
                this.classList.remove('is-collapsed');
                this.classList.add('is-expanded');
            });

            addTrackedEventListener(sidebar, 'mouseleave', function() {
                this.classList.remove('is-expanded');
                this.classList.add('is-collapsed');
            });
        }

        function setupThemeToggle() {
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                const themeIcon = themeToggle.querySelector('ion-icon');

                // Check for saved theme preference or default to 'light'
                const currentTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.classList.toggle('dark', currentTheme === 'dark');
                if (themeIcon) {
                    themeIcon.setAttribute('name', currentTheme === 'dark' ? 'sunny-outline' : 'moon-outline');
                }

                addTrackedEventListener(themeToggle, 'click', function() {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    const icon = this.querySelector('ion-icon');
                    if (icon) {
                        icon.setAttribute('name', isDark ? 'sunny-outline' : 'moon-outline');
                    }
                });
            }
        }

        function setupNotifications() {
            const notificationsToggle = document.getElementById('notifications-toggle');
            const notificationsSidebar = document.getElementById('notifications-sidebar');
            const closeNotifications = document.getElementById('close-notifications');

            if (notificationsToggle && notificationsSidebar) {
                addTrackedEventListener(notificationsToggle, 'click', function() {
                    notificationsSidebar.classList.remove('translate-x-full');
                });
            }

            if (closeNotifications && notificationsSidebar) {
                addTrackedEventListener(closeNotifications, 'click', function() {
                    notificationsSidebar.classList.add('translate-x-full');
                });
            }

            // Close notifications when clicking outside
            if (notificationsSidebar && notificationsToggle) {
                addTrackedEventListener(document, 'click', function(e) {
                    if (!notificationsSidebar.contains(e.target) && !notificationsToggle.contains(e.target)) {
                        notificationsSidebar.classList.add('translate-x-full');
                    }
                });
            }
        }

        // Initialize on DOMContentLoaded (for first page load)
        document.addEventListener('DOMContentLoaded', initializeLayoutFunctionality);

        // Re-initialize on Livewire navigation (for SPA-style navigation)
        document.addEventListener('livewire:navigated', initializeLayoutFunctionality);

        // Cleanup on page unload
        window.addEventListener('beforeunload', cleanupEventListeners);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.add('is-collapsed');
                sidebar.classList.remove('is-expanded'); // Ensure expanded class is removed
            }
        });
    </script>
</body>
</html>
