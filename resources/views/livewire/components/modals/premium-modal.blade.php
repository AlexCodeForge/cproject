<div>
    @if ($showModal)
        <div
            x-data="{ showModal: @entangle('showModal') }"
            x-show="showModal"
            x-on:keydown.escape.window="showModal = false"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                     @click="showModal = false"
                     aria-hidden="true">
                </div>

                <!-- Modal panel -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                >
                    <div class="relative bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 dark:from-amber-900 dark:via-amber-800 dark:to-orange-900 p-8 rounded-xl border-2 border-amber-300/40 dark:border-amber-700 shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <!-- Decorative background elements -->
                        <div class="absolute -top-20 -right-20 w-40 h-40 bg-amber-300/20 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-orange-300/20 rounded-full blur-2xl"></div>

                        <!-- Close button -->
                        <button wire:click="closeModal" class="absolute top-4 right-4 text-amber-800/70 dark:text-amber-200/70 hover:text-amber-900 dark:hover:text-amber-100 transition-colors">
                            <x-ionicon-close-circle-outline class="w-8 h-8"/>
                        </button>

                        <div class="relative z-10">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-amber-400 dark:bg-amber-500 p-2 rounded-lg">
                                    <ion-icon name="diamond" class="text-amber-900 dark:text-amber-100 text-xl"></ion-icon>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
                                    <p class="text-amber-700 dark:text-amber-300 text-sm font-semibold">Contenido Exclusivo</p>
                                </div>
                            </div>

                            <div class="space-y-3 mb-6">
                                <p class="text-slate-700 dark:text-gray-300">{{ $message }}</p>
                                <div class="flex items-center gap-2 text-slate-700 dark:text-gray-300">
                                    <ion-icon name="checkmark-circle" class="text-green-600 dark:text-green-400"></ion-icon>
                                    <span class="text-sm">Análisis avanzados de mercado</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-700 dark:text-gray-300">
                                    <ion-icon name="checkmark-circle" class="text-green-600 dark:text-green-400"></ion-icon>
                                    <span class="text-sm">Alertas de trading en tiempo real</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-700 dark:text-gray-300">
                                    <ion-icon name="checkmark-circle" class="text-green-600 dark:text-green-400"></ion-icon>
                                    <span class="text-sm">Webinars exclusivos</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-700 dark:text-gray-300">
                                    <ion-icon name="checkmark-circle" class="text-green-600 dark:text-green-400"></ion-icon>
                                    <span class="text-sm">Soporte prioritario</span>
                                </div>
                            </div>

                            <button wire:click="goToPricing" class="w-full bg-amber-400 hover:bg-amber-300 dark:bg-amber-500 dark:hover:bg-amber-400 text-amber-900 dark:text-amber-100 font-bold py-3 px-6 rounded-lg flex items-center justify-center gap-2 transition-all duration-200 hover:scale-105 shadow-md">
                                <ion-icon name="diamond" class="text-lg"></ion-icon>
                                <span>Obtener Premium</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
