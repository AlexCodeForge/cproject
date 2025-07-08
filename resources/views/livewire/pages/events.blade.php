<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

<div>
    <section id="events" class="section active">

        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-8">Próximos Eventos</h1>

        <!-- Featured Premium Event - Smaller Card -->
        <div class="mb-8">
            <div class="bg-gradient-to-br from-amber-50 via-amber-100 to-orange-100 dark:from-amber-900/30 dark:via-amber-800/40 dark:to-orange-900/30 rounded-xl border-2 border-amber-300 dark:border-amber-700 shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="relative">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-600/10 to-orange-600/10 dark:from-amber-400/5 dark:to-orange-400/5"></div>
                    <!-- Update decorative background circles -->
                    <div class="absolute top-0 right-0 w-20 h-20 bg-amber-300/20 dark:bg-amber-600/10 rounded-full -translate-y-10 translate-x-10"></div>
                    <div class="absolute bottom-0 left-0 w-16 h-16 bg-orange-300/20 dark:bg-orange-600/10 rounded-full translate-y-8 -translate-x-8"></div>

                    <!-- Content -->
                    <div class="relative p-6">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
                            <!-- Date Badge -->
                            <div class="bg-gradient-to-br from-amber-500 to-orange-500 text-white rounded-xl p-4 text-center shadow-lg transform group-hover:scale-105 transition-transform duration-300">
                                <p class="text-2xl md:text-3xl font-black">15</p>
                                <p class="text-sm font-bold uppercase tracking-wider">JUL</p>
                                <p class="text-xs opacity-90 mt-1">2025</p>
                            </div>

                            <!-- Event Info -->
                            <div class="flex-grow">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="bg-gradient-to-r from-amber-600 to-orange-600 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1 shadow-md">
                                        <ion-icon name="diamond" class="text-sm"></ion-icon>
                                        EVENTO PREMIUM EXCLUSIVO
                                    </span>
                                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse transform-gpu">
                                        CUPOS LIMITADOS
                                    </span>
                                </div>

                                <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white mb-3 leading-tight">
                                    🚀 Masterclass: Análisis Macroeconómico Q3
                                </h2>

                                <p class="text-sm text-slate-700 dark:text-gray-300 mb-4 leading-relaxed">
                                    Una inmersión profunda con <strong>expertos de Wall Street</strong> en los factores que moverán el mercado en el tercer trimestre.
                                </p>

                                <!-- Event Details -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                                    <div class="flex items-center gap-2 bg-white/70 dark:bg-gray-800/70 rounded-lg p-2">
                                        <ion-icon name="time-outline" class="text-lg text-amber-600"></ion-icon>
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white text-sm">7:00 PM EST</p>
                                            <p class="text-xs text-slate-600 dark:text-gray-400">2 horas</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 bg-white/70 dark:bg-gray-800/70 rounded-lg p-2">
                                        <ion-icon name="people-outline" class="text-lg text-amber-600"></ion-icon>
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white text-sm">Solo 50 plazas</p>
                                            <p class="text-xs text-slate-600 dark:text-gray-400">Interacción directa</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 bg-white/70 dark:bg-gray-800/70 rounded-lg p-2">
                                        <ion-icon name="gift-outline" class="text-lg text-amber-600"></ion-icon>
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white text-sm">Bonus incluido</p>
                                            <p class="text-xs text-slate-600 dark:text-gray-400">Reporte PDF</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Speakers -->
                                <div class="mb-4">
                                    <p class="text-xs font-semibold text-slate-600 dark:text-gray-400 mb-2">PRESENTADO POR:</p>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-2">
                                            <img src="https://randomuser.me/api/portraits/men/75.jpg" class="w-8 h-8 rounded-full border-2 border-amber-300" alt="David Rodriguez">
                                            <div>
                                                <p class="font-bold text-slate-900 dark:text-white text-sm">David Rodriguez</p>
                                                <p class="text-xs text-slate-600 dark:text-gray-400">Ex-Goldman Sachs</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <img src="https://randomuser.me/api/portraits/women/68.jpg" class="w-8 h-8 rounded-full border-2 border-amber-300" alt="Sarah Chen">
                                            <div>
                                                <p class="font-bold text-slate-900 dark:text-white text-sm">Sarah Chen</p>
                                                <p class="text-xs text-slate-600 dark:text-gray-400">Estratega de JP Morgan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="flex-shrink-0">
                                <button class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2 text-sm">
                                    <ion-icon name="calendar-outline" class="text-lg"></ion-icon>
                                    <span>RESERVAR PLAZA</span>
                                </button>
                                <p class="text-center text-xs text-slate-500 dark:text-gray-400 mt-1">Solo miembros Premium</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Regular Events -->
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Otros Eventos Próximos</h2>
            <div class="space-y-4">
                <!-- Public Event 1 -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm flex items-center gap-6 hover:shadow-md hover:border-slate-300 dark:hover:border-gray-600 transition-all group">
                    <div class="bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 rounded-lg p-4 text-center w-20 flex-shrink-0 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 transition-colors">
                        <p class="text-2xl font-bold">08</p>
                        <p class="text-sm font-semibold">JUL</p>
                </div>
                <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">GRATIS</span>
                            <span class="text-xs text-slate-500 dark:text-gray-400">7:30 AM EST</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">Sesión de Trading en Vivo: Apertura de Mercado</h3>
                        <p class="text-slate-600 dark:text-gray-400 text-sm">Opera junto a nuestros traders expertos durante la volátil apertura del mercado.</p>
                    </div>
                    <button class="bg-slate-100 dark:bg-gray-700 hover:bg-slate-700 dark:hover:bg-gray-600 hover:text-white dark:hover:text-white text-slate-700 dark:text-gray-300 font-semibold py-2 px-6 rounded-lg transition-all flex items-center gap-2">
                        <ion-icon name="notifications-outline"></ion-icon>
                        <span>Recordar</span>
                    </button>
                </div>

                <!-- Public Event 2 -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm flex items-center gap-6 hover:shadow-md hover:border-slate-300 dark:hover:border-gray-600 transition-all group">
                    <div class="bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 rounded-lg p-4 text-center w-20 flex-shrink-0 group-hover:bg-slate-200 dark:group-hover:bg-gray-600 transition-colors">
                        <p class="text-2xl font-bold">12</p>
                        <p class="text-sm font-semibold">JUL</p>
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">GRATIS</span>
                            <span class="text-xs text-slate-500 dark:text-gray-400">2:00 PM EST</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">Análisis Semanal del Mercado con David Vega</h3>
                        <p class="text-slate-600 dark:text-gray-400 text-sm">Revisión de los principales índices y activos para la próxima semana.</p>
                    </div>
                    <button class="bg-slate-100 dark:bg-gray-700 hover:bg-slate-700 dark:hover:bg-gray-600 hover:text-white dark:hover:text-white text-slate-700 dark:text-gray-300 font-semibold py-2 px-6 rounded-lg transition-all flex items-center gap-2">
                        <ion-icon name="notifications-outline"></ion-icon>
                        <span>Recordar</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>
