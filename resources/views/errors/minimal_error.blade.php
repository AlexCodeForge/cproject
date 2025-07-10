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
                    Ups, ¡Algo Salió Mal!
                </h2>
                <p class="text-lg text-gray-300 dark:text-gray-200 mb-8 leading-relaxed">
                    Parece que encontramos un pequeño problema. Pero no te preocupes, estamos trabajando en ello.
                </p>
            </div>
        </div>
    </div>

    <!-- Right Side - Error Message -->
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

        <div class="w-full max-w-md mt-12 sm:mt-16 lg:mt-0 text-center">
            <h2 class="text-5xl sm:text-6xl font-bold text-slate-900 dark:text-white mb-4">
                {{ $errorCode ?? 'Error' }}
            </h2>
            <p class="text-lg sm:text-xl text-slate-600 dark:text-gray-400 mb-8">
                {{ $errorMessage ?? 'La página que buscas no existe o algo salió mal.' }}
            </p>
            <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all">
                Ir al Inicio
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    .gradient-text {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;

        const currentTheme = localStorage.getItem('theme') || 'light';
        html.classList.toggle('dark', currentTheme === 'dark');

        updateThemeIcon(currentTheme === 'dark');

        themeToggle.addEventListener('click', () => {
            const isDark = html.classList.contains('dark');
            html.classList.toggle('dark', !isDark);
            localStorage.setItem('theme', !isDark ? 'dark' : 'light');
            updateThemeIcon(!isDark);
        });

        function updateThemeIcon(isDark) {
            const icon = themeToggle.querySelector('svg path');
            if (isDark) {
                icon.setAttribute('d', 'M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z');
            } else {
                icon.setAttribute('d', 'M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z');
            }
        }
    });
</script>
@endpush
