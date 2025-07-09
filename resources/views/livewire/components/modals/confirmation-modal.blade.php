<div>
    @if ($showModal)
        @teleport('body')
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:key="confirmation-modal">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" wire:click="closeModal"></div>

            <!-- Modal Content -->
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4 border border-stone-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="bg-red-100 dark:bg-red-900/50 p-3 rounded-full">
                            {{-- You can customize the icon via a property if needed --}}
                            <x-ionicon-trash class="text-2xl text-red-600 dark:text-red-400 w-6 h-6"/>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">{{ $title }}</h3>
                            <p class="text-slate-600 dark:text-gray-400">
                                {{ $message }}
                            </p>
                        </div>
                        <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 dark:text-gray-400 dark:hover:text-gray-200">
                            <x-ionicon-close class="text-xl w-6 h-6"/>
                        </button>
                    </div>
                    <div class="flex justify-end gap-3">
                        <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                            Cancelar
                        </x-secondary-button>

                        <x-danger-button class="ml-3" wire:click="confirm" wire:loading.attr="disabled">
                            Confirmar
                        </x-danger-button>
                    </div>
                </div>
            </div>
        </div>
        @endteleport
    @endif
</div>
