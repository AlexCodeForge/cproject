<button wire:click="toggleTheme" title="Cambiar Tema" class="w-full flex items-center p-3 rounded-xl text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700 hover:text-slate-800 dark:hover:text-gray-200 transition-all overflow-hidden">
    @if ($theme === 'dark')
        <x-ionicon-moon-outline class="w-6 h-6 flex-shrink-0" />
    @else
        <x-ionicon-sunny-outline class="w-6 h-6 flex-shrink-0" />
    @endif
    <span class="nav-text ml-4 text-sm font-semibold whitespace-nowrap transition-opacity">Tema</span>
</button>
