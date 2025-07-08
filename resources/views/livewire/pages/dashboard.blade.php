<?php

use App\Models\ChatMessage;
use App\Models\TradingAlert;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component
{
    public $openPositions = 4;
    public $dailyPnL = 1280.50;
    public $newAlerts = 0;
    public $unreadMessages = 0;

    public function mount()
    {
        // Load user-specific dashboard data
        $this->loadDashboardData();
    }

    private function loadDashboardData()
    {
        // Mock data for now - replace with real data later
        $this->openPositions = 4;
        $this->dailyPnL = 1280.50;

        // TODO: Implement a more robust way to count new/unread items.
        // This is a temporary implementation for the dashboard view.
        $this->newAlerts = TradingAlert::where('status', 'active')->count();
        $this->unreadMessages = ChatMessage::whereDate('created_at', today())->count();
    }
}; ?>

<div>
    <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-2">Bienvenido, {{ Auth::user()->name }}</h1>
    <p class="text-slate-500 dark:text-gray-400 mb-8">Este es tu centro de operaciones.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
            <h3 class="font-semibold text-slate-600 dark:text-gray-300">Posiciones Abiertas</h3>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ $openPositions }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
            <h3 class="font-semibold text-slate-600 dark:text-gray-300">Ganancia/Pérdida (Hoy)</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">+${{ number_format($dailyPnL, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
            <h3 class="font-semibold text-slate-600 dark:text-gray-300">Nuevas Alertas</h3>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ $newAlerts }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
            <h3 class="font-semibold text-slate-600 dark:text-gray-300">Mensajes No Leídos</h3>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ $unreadMessages }}</p>
        </div>
    </div>

    <!-- Additional Dashboard Widgets -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Premium Event Widget -->
        <div class="relative bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 dark:from-amber-900/30 dark:via-amber-800/40 dark:to-orange-900/30 p-8 rounded-xl border-2 border-amber-300/40 dark:border-amber-700 shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-amber-300/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-orange-300/20 rounded-full blur-2xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-amber-400 dark:bg-amber-500 p-2 rounded-lg">
                        <ion-icon name="calendar" class="text-amber-900 dark:text-amber-100 text-xl"></ion-icon>
                    </div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Próximo Evento Premium</h3>
                        <ion-icon name="diamond" class="text-amber-600 dark:text-amber-400 text-sm"></ion-icon>
                    </div>
                </div>

                <div class="bg-white/70 dark:bg-gray-900/30 p-6 rounded-lg border border-amber-200/50 dark:border-amber-700/50 backdrop-blur-sm">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white text-lg mb-1">Webinar: Estrategias Q4 2024</h4>
                            <p class="text-amber-700 dark:text-amber-300 text-sm font-semibold">Análisis Macroeconómico Premium</p>
                        </div>
                        <span class="bg-amber-200 dark:bg-amber-800/50 text-amber-800 dark:text-amber-200 text-xs font-bold px-3 py-1 rounded-full">PREMIUM</span>
                    </div>

                    <p class="text-slate-700 dark:text-gray-300 text-sm mb-4 leading-relaxed">
                        Únete a nuestros analistas senior para descubrir las mejores oportunidades de inversión para el último trimestre del año.
                    </p>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-1 text-slate-600 dark:text-gray-400">
                                <ion-icon name="time" class="text-amber-600 dark:text-amber-400"></ion-icon>
                                <span>15 Dic, 7:00 PM</span>
                            </div>
                            <div class="flex items-center gap-1 text-slate-600 dark:text-gray-400">
                                <ion-icon name="people" class="text-amber-600 dark:text-amber-400"></ion-icon>
                                <span>89 registrados</span>
                            </div>
                        </div>
                        <button class="bg-amber-400 hover:bg-amber-300 dark:bg-amber-500 dark:hover:bg-amber-400 text-amber-900 dark:text-amber-100 font-bold py-2 px-4 text-sm rounded-lg flex items-center gap-2 transition-all duration-200 hover:scale-105 shadow-md">
                            <ion-icon name="diamond" class="text-sm"></ion-icon>
                            <span>Registrarse</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Market News Widget -->
        <div class="bg-white dark:bg-gray-800 p-8 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-blue-100 dark:bg-blue-900/50 p-2 rounded-lg">
                    <ion-icon name="newspaper" class="text-blue-600 dark:text-blue-400 text-xl"></ion-icon>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Noticias del Mercado</h3>
            </div>

            <div class="space-y-4">
                <article class="border-l-4 border-green-500 pl-4 py-2">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-900 dark:text-white text-sm mb-1">S&P 500 alcanza nuevos máximos históricos</h4>
                            <p class="text-slate-600 dark:text-gray-400 text-xs mb-2">Los índices principales continúan su tendencia alcista impulsados por resultados corporativos positivos.</p>
                            <span class="text-green-600 dark:text-green-400 text-xs font-semibold">+1.2%</span>
                        </div>
                        <span class="text-xs text-slate-400 dark:text-gray-500 ml-2">12:45</span>
                    </div>
                </article>

                <article class="border-l-4 border-red-500 pl-4 py-2">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-900 dark:text-white text-sm mb-1">Bitcoin retrocede tras decisión de la Fed</h4>
                            <p class="text-slate-600 dark:text-gray-400 text-xs mb-2">Las criptomonedas se ven afectadas por el cambio en la política monetaria.</p>
                            <span class="text-red-600 dark:text-red-400 text-xs font-semibold">-3.7%</span>
                        </div>
                        <span class="text-xs text-slate-400 dark:text-gray-500 ml-2">11:30</span>
                    </div>
                </article>

                <article class="border-l-4 border-blue-500 pl-4 py-2">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-900 dark:text-white text-sm mb-1">Petróleo WTI supera los $85 por barril</h4>
                            <p class="text-slate-600 dark:text-gray-400 text-xs mb-2">Tensiones geopolíticas impulsan los precios del crudo a niveles no vistos en 6 meses.</p>
                            <span class="text-blue-600 dark:text-blue-400 text-xs font-semibold">+2.8%</span>
                        </div>
                        <span class="text-xs text-slate-400 dark:text-gray-500 ml-2">10:15</span>
                    </div>
                </article>
            </div>

            <div class="mt-6 pt-4 border-t border-stone-200 dark:border-gray-700">
                <button class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-semibold flex items-center gap-1 transition-colors">
                    <span>Ver todas las noticias</span>
                    <ion-icon name="arrow-forward" class="text-xs"></ion-icon>
                </button>
            </div>
        </div>
    </div>
</div>
