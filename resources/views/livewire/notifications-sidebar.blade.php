<div x-cloak>
    <!-- Overlay -->
    <div
        x-show="$wire.isOpen"
        x-transition:enter="transition-opacity ease-in-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in-out duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        wire:click.self="close"
        class="fixed inset-0 bg-black/30 z-20"
        style="display: none;"
    ></div>

    <!-- Sidebar -->
    <aside
        :class="{ 'translate-x-0': $wire.isOpen, 'translate-x-full': !$wire.isOpen }"
        class="fixed top-0 right-0 h-full w-80 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-l border-stone-200 dark:border-gray-700 p-6 z-30 transform transition-transform duration-300 ease-in-out"
    >
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                Notificaciones
                @if($unreadCount > 0)
                    <span class="text-sm font-medium text-white bg-red-600 rounded-full px-2 py-1 ml-2">{{ $unreadCount }}</span>
                @endif
            </h2>
            <button wire:click="close" class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200 text-2xl">&times;</button>
        </div>

        @if($unreadCount > 0)
            <div class="mb-4">
                <button wire:click="markAllAsRead" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Marcar todas como leídas</button>
            </div>
        @endif

        <div class="space-y-4">
            @forelse($notifications as $notification)
                <a href="{{ $notification->data['url'] }}" wire:click.prevent="markAsReadAndRedirect('{{ $notification->id }}', '{{ $notification->data['url'] }}')"
                   class="block p-3 rounded-lg transition-colors @if(is_null($notification->read_at)) bg-stone-100/50 dark:bg-gray-700/50 hover:bg-stone-200/60 dark:hover:bg-gray-600/60 @else bg-transparent hover:bg-stone-100/50 dark:hover:bg-gray-700/50 @endif">
                    <div class="flex items-start space-x-3">
                        @if(is_null($notification->read_at))
                            <span class="flex h-2 w-2 mt-1.5 rounded-full bg-blue-500"></span>
                        @else
                            <span class="flex h-2 w-2 mt-1.5 rounded-full bg-transparent"></span>
                        @endif
                        <div>
                            <p class="text-sm text-slate-800 dark:text-gray-200">{{ $notification->data['message'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center text-slate-500 dark:text-gray-400 py-10">
                    <x-ionicon-notifications-off-outline class="w-12 h-12 mx-auto mb-4"/>
                    <p>No tienes notificaciones nuevas.</p>
                </div>
            @endforelse
        </div>
    </aside>
</div>
