<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

<div>
    <section id="pnl" class="section active">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-2">Registro de Ganancias y Pérdidas</h1>
        <p class="text-slate-500 mb-8">Revisa nuestras operaciones más recientes.</p>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center md:text-left">
                    <h3 class="font-semibold text-slate-600 dark:text-gray-300">Ganancia Semanal</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">+$4,850.75</p>
                    <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">+18.2% esta semana</p>
                </div>
                <div class="text-center md:text-left">
                    <h3 class="font-semibold text-slate-600 dark:text-gray-300">Total Operaciones</h3>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">9</p>
                    <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">6 ganadoras, 3 perdedoras</p>
                </div>
                <div class="text-center md:text-left">
                    <h3 class="font-semibold text-slate-600 dark:text-gray-300">Tasa de Éxito</h3>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">66.7%</p>
                    <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">Mejor que promedio</p>
                </div>
                <div class="text-center md:text-left">
                    <h3 class="font-semibold text-slate-600 dark:text-gray-300">Mejor Operación</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">+285%</p>
                    <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">$TSLA calls</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            <!-- P&L Card: Big Win -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-hidden group hover:shadow-lg transition-shadow">
                <img src="https://placehold.co/600x300/16a34a/ffffff?text=SPY+CALLS+📈" class="w-full h-48 object-cover" alt="SPY Trade Screenshot">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-white">$SPY</h3>
                        <span class="text-2xl font-bold text-green-600">+150%</span>
                    </div>
                    <p class="text-slate-600 dark:text-gray-400 mt-2">Llamadas de la semana siguiente después de la ruptura de la resistencia clave.</p>
                    <div class="flex justify-between items-center mt-4">
                        <p class="text-xs text-slate-400 dark:text-gray-500">03 de Julio, 2025</p>
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">GANADOR</span>
                    </div>
                </div>
            </div>

            <!-- P&L Card: Loss -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-hidden group hover:shadow-lg transition-shadow">
                <img src="https://placehold.co/600x300/dc2626/ffffff?text=META+PUTS+📉" class="w-full h-48 object-cover" alt="META Trade Screenshot">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-white">$META</h3>
                        <span class="text-2xl font-bold text-red-600">-25%</span>
                    </div>
                    <p class="text-slate-600 dark:text-gray-400 mt-2">Stop loss alcanzado en una operación de reversión que no se materializó.</p>
                    <div class="flex justify-between items-center mt-4">
                        <p class="text-xs text-slate-400 dark:text-gray-500">02 de Julio, 2025</p>
                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded-full">PÉRDIDA</span>
                    </div>
                </div>
            </div>

            <!-- P&L Card: TSLA Big Win -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-hidden group hover:shadow-lg transition-shadow">
                <img src="https://placehold.co/600x300/16a34a/ffffff?text=TSLA+CALLS+🚀" class="w-full h-48 object-cover" alt="TSLA Trade Screenshot">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-white">$TSLA</h3>
                        <span class="text-2xl font-bold text-green-600">+285%</span>
                    </div>
                    <p class="text-slate-600 mt-2">Llamadas semanales después del anuncio de entrega de Q2 superando expectativas.</p>
                    <div class="flex justify-between items-center mt-4">
                        <p class="text-xs text-slate-400">01 de Julio, 2025</p>
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">GANADOR</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
