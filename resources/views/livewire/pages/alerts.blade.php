<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

<div>
    <section id="alerts" class="section active">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-8">Alertas de Trading Premium</h1>

        <!-- Analytics Widget -->
        <div class="bg-gradient-to-r from-slate-50 to-stone-50 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-stone-200 dark:border-gray-600 p-6 mb-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <ion-icon name="analytics-outline" class="text-2xl text-blue-600 dark:text-blue-400"></ion-icon>
                    Panel de Análisis
                </h2>
                <span class="text-xs text-slate-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-3 py-1 rounded-full border border-stone-200 dark:border-gray-600">
                    Actualizado hace 5 min
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Active Alerts -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-stone-200 dark:border-gray-600">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-600 dark:text-gray-400">Alertas Activas</span>
                        <ion-icon name="notifications-outline" class="text-lg text-blue-600 dark:text-blue-400"></ion-icon>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">4</div>
                    <div class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1 mt-1">
                        <ion-icon name="trending-up-outline" class="text-sm"></ion-icon>
                        +25% esta semana
                    </div>
                </div>

                <!-- Win Rate -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-stone-200 dark:border-gray-600">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-600 dark:text-gray-400">Tasa de Éxito</span>
                        <ion-icon name="trophy-outline" class="text-lg text-amber-600 dark:text-amber-400"></ion-icon>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">78%</div>
                    <div class="text-xs text-slate-500 dark:text-gray-400 mt-1">Últimas 30 alertas</div>
                </div>

                <!-- Average Return -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-stone-200 dark:border-gray-600">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-600 dark:text-gray-400">Retorno Promedio</span>
                        <ion-icon name="trending-up-outline" class="text-lg text-green-600 dark:text-green-400"></ion-icon>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">+12.4%</div>
                    <div class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1 mt-1">
                        <ion-icon name="arrow-up-outline" class="text-sm"></ion-icon>
                        +2.1% vs mes anterior
                    </div>
                </div>

                <!-- Risk Level -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-stone-200 dark:border-gray-600">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-600 dark:text-gray-400">Nivel de Riesgo</span>
                        <ion-icon name="shield-checkmark-outline" class="text-lg text-blue-600 dark:text-blue-400"></ion-icon>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">Medio</div>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="flex-1 bg-stone-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-600 dark:bg-blue-400 h-2 rounded-full" style="width: 60%"></div>
                        </div>
                        <span class="text-xs text-slate-500 dark:text-gray-400">60%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-slate-400 dark:hover:border-gray-600 transition-all">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <img src="https://s3-symbol-logo.tradingview.com/tesla--big.svg" class="w-8 h-8 rounded-full" alt="TSLA" onerror="this.onerror=null;this.src='https://placehold.co/32x32/e2e8f0/94a3b8?text=TSLA';">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">TSLA</h2>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">COMPRA</span>
                </div>
                <p class="text-sm text-slate-500 mt-1">Análisis de Ruptura</p>
                <div class="mt-4 h-48"><canvas id="alertChart1"></canvas></div>
                <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-xs text-slate-500">Entrada</p>
                        <p class="font-bold text-slate-800 dark:text-white">$185.50</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Profit</p>
                        <p class="font-bold text-green-600">$195.00</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Stop</p>
                        <p class="font-bold text-red-600">$181.00</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-slate-400 dark:hover:border-gray-600 transition-all">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <img src="https://s3-symbol-logo.tradingview.com/apple--big.svg" class="w-8 h-8 rounded-full" alt="AAPL" onerror="this.onerror=null;this.src='https://placehold.co/32x32/e2e8f0/94a3b8?text=AAPL';">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">AAPL</h2>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">COMPRA</span>
                </div>
                <p class="text-sm text-slate-500 mt-1">Pullback a Media Móvil</p>
                <div class="mt-4 h-48"><canvas id="alertChart2"></canvas></div>
                <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-xs text-slate-500">Entrada</p>
                        <p class="font-bold text-slate-800 dark:text-white">$212.30</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Profit</p>
                        <p class="font-bold text-green-600">$225.00</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Stop</p>
                        <p class="font-bold text-red-600">$208.70</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-slate-400 dark:hover:border-gray-600 transition-all">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <img src="https://s3-symbol-logo.tradingview.com/nvidia--big.svg" class="w-8 h-8 rounded-full" alt="NVDA" onerror="this.onerror=null;this.src='https://placehold.co/32x32/e2e8f0/94a3b8?text=NVDA';">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">NVDA</h2>
                    </div>
                    <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full">VENTA</span>
                </div>
                <p class="text-sm text-slate-500 mt-1">Divergencia Bajista</p>
                <div class="mt-4 h-48"><canvas id="alertChart3"></canvas></div>
                <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-xs text-slate-500">Entrada</p>
                        <p class="font-bold text-slate-800 dark:text-white">$120.80</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Profit</p>
                        <p class="font-bold text-green-600">$115.50</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Stop</p>
                        <p class="font-bold text-red-600">$123.50</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
