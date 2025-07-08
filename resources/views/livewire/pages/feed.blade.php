<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

<div>
    <section id="feed" class="section active">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Feed de Noticias</h1>
            <div class="flex items-center gap-2">
                <button id="feed-list-view" class="p-2 rounded-lg text-slate-700 bg-stone-200"><ion-icon name="list-outline" class="text-xl"></ion-icon></button>
                <button id="feed-grid-view" class="p-2 rounded-lg text-slate-500"><ion-icon name="grid-outline" class="text-xl"></ion-icon></button>
            </div>
        </div>
        <div id="feed-filters" class="flex items-center gap-2 mb-8 overflow-x-auto pb-2">
             <button data-category="all" class="feed-filter-btn bg-slate-700 text-white px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap">Todos</button>
             <button data-category="Criptomonedas" class="feed-filter-btn bg-white text-slate-600 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap border border-stone-200">Criptomonedas</button>
             <button data-category="Premium" class="feed-filter-btn bg-gradient-to-r from-amber-400 to-orange-400 text-amber-900 px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap border-2 border-amber-300 shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-1">
                 <ion-icon name="diamond" class="text-xs"></ion-icon>Premium
             </button>
             <button data-category="Análisis Premium" class="feed-filter-btn bg-white text-slate-600 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap border border-stone-200">Análisis Premium</button>
             <button data-category="Mercados Globales" class="feed-filter-btn bg-white text-slate-600 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap border border-stone-200">Mercados Globales</button>
             <button data-category="Materias Primas" class="feed-filter-btn bg-white text-slate-600 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap border border-stone-200">Materias Primas</button>
        </div>

        <div id="feed-container" class="space-y-8">
            <article class="feed-item flex flex-col sm:flex-row bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-slate-400 dark:hover:border-gray-600 transition-all overflow-hidden hover:shadow-md transition-shadow cursor-pointer" data-category="Criptomonedas">
                <div class="sm:w-1/3 feed-image"><img src="https://images.unsplash.com/photo-1639322537228-f710d846310a?q=80&w=1932&auto=format&fit=crop" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/e2e8f0/94a3b8?text=Image';"></div>
                <div class="p-6 sm:w-2/3"><span class="text-xs font-semibold uppercase text-slate-500">Criptomonedas</span><h3 class="text-xl font-bold my-2 text-slate-900 dark:text-white">El Halving de Bitcoin y su impacto.</h3><p class="text-slate-600 text-sm">Un análisis sobre los efectos históricos y las proyecciones futuras.</p></div>
            </article>
            <article class="feed-item relative flex flex-col sm:flex-row bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 dark:from-amber-900/30 dark:via-amber-800/40 dark:to-orange-900/30 p-8 rounded-xl border-2 border-amber-300/40 dark:border-amber-700 shadow-lg hover:shadow-xl hover:border-amber-400/60 dark:hover:border-amber-600 transition-all duration-300 overflow-hidden cursor-pointer" data-category="Análisis Premium">
                <!-- Decorative background elements -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-amber-300/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-orange-300/20 rounded-full blur-2xl"></div>

                <div class="sm:w-1/3 relative feed-image z-10">
                    <img src="https://images.unsplash.com/photo-1665686308827-eb62e4f6604d?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover blur-sm rounded-lg" onerror="this.onerror=null;this.src='https://placehold.co/600x400/e2e8f0/94a3b8?text=Image';">
                    <div class="absolute inset-0 bg-black/30 rounded-lg flex items-center justify-center">
                        <button class="bg-amber-400 hover:bg-amber-300 text-amber-900 font-bold py-2 px-4 text-sm rounded-full flex items-center gap-2 transform-gpu transition-all duration-200 hover:scale-105 shadow-lg">
                            <ion-icon name="diamond" class="text-lg"></ion-icon>
                            <span>Leer Análisis Premium</span>
                        </button>
                    </div>
                </div>
                <div class="p-6 sm:w-2/3 relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-bold uppercase text-amber-700 dark:text-amber-400 bg-amber-200/50 dark:bg-amber-800/30 px-2 py-1 rounded-full">Análisis Premium</span>
                        <ion-icon name="diamond" class="text-amber-600 dark:text-amber-400 text-sm"></ion-icon>
                    </div>
                    <h3 class="text-xl font-bold my-2 text-slate-900 dark:text-white">3 Opciones de compra en el sector tecnológico.</h3>
                    <p class="text-slate-600 dark:text-gray-300 text-sm leading-relaxed">Nuestros analistas han identificado tres empresas con un potencial de crecimiento explosivo en el sector tecnológico.</p>
                </div>
            </article>
            <article class="feed-item relative flex flex-col sm:flex-row bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 dark:from-amber-900/30 dark:via-amber-800/40 dark:to-orange-900/30 p-8 rounded-xl border-2 border-amber-300/40 dark:border-amber-700 shadow-lg hover:shadow-xl hover:border-amber-400/60 dark:hover:border-amber-600 transition-all duration-300 overflow-hidden cursor-pointer" data-category="Premium">
                <!-- Decorative background elements -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-amber-300/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-orange-300/20 rounded-full blur-2xl"></div>

                <div class="sm:w-1/3 relative feed-image z-10">
                    <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover blur-sm rounded-lg" onerror="this.onerror=null;this.src='https://placehold.co/600x400/e2e8f0/94a3b8?text=Image';">
                    <div class="absolute inset-0 bg-black/30 rounded-lg flex items-center justify-center">
                        <button class="bg-amber-400 hover:bg-amber-300 text-amber-900 font-bold py-2 px-4 text-sm rounded-full flex items-center gap-2 transform-gpu transition-all duration-200 hover:scale-105 shadow-lg">
                            <ion-icon name="diamond" class="text-lg"></ion-icon>
                            <span>Ver Estrategia Premium</span>
                        </button>
                    </div>
                </div>
                <div class="p-6 sm:w-2/3 relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-bold uppercase text-amber-700 dark:text-amber-400 bg-amber-200/50 dark:bg-amber-800/30 px-2 py-1 rounded-full">Premium</span>
                        <ion-icon name="diamond" class="text-amber-600 dark:text-amber-400 text-sm"></ion-icon>
                    </div>
                    <h3 class="text-xl font-bold my-2 text-slate-900 dark:text-white">Estrategia de Cobertura para Mercados Volátiles</h3>
                    <p class="text-slate-600 dark:text-gray-300 text-sm leading-relaxed">Aprende cómo proteger tu portafolio durante períodos de alta volatilidad con técnicas avanzadas de hedging y gestión de riesgo.</p>
                </div>
            </article>
             <article class="feed-item flex flex-col sm:flex-row bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-slate-400 dark:hover:border-gray-600 transition-all overflow-hidden hover:shadow-md transition-shadow cursor-pointer" data-category="Mercados Globales">
                <div class="sm:w-1/3 feed-image"><img src="https://images.unsplash.com/photo-1621111848521-4b71329313a9?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/e2e8f0/94a3b8?text=Image';"></div>
                <div class="p-6 sm:w-2/3"><span class="text-xs font-semibold uppercase text-slate-500">Mercados Globales</span><h3 class="text-xl font-bold my-2 text-slate-900 dark:text-white">El Dólar se fortalece ante la incertidumbre.</h3><p class="text-slate-600 text-sm">Analizamos cómo la política monetaria de la Fed está impactando los mercados de divisas a nivel mundial.</p></div>
            </article>
             <article class="feed-item flex flex-col sm:flex-row bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-slate-400 dark:hover:border-gray-600 transition-all overflow-hidden hover:shadow-md transition-shadow cursor-pointer" data-category="Materias Primas">
                <div class="sm:w-1/3 feed-image"><img src="https://images.unsplash.com/photo-1565058352213-79a11c8e0a88?q=80&w=2071&auto=format&fit=crop" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/e2e8f0/94a3b8?text=Image';"></div>
                <div class="p-6 sm:w-2/3"><span class="text-xs font-semibold uppercase text-slate-500">Materias Primas</span><h3 class="text-xl font-bold my-2 text-slate-900 dark:text-white">El Petróleo alcanza máximos de 3 meses.</h3><p class="text-slate-600 text-sm">Las tensiones geopolíticas y los recortes de producción impulsan los precios del crudo.</p></div>
            </article>
        </div>
    </section>
</div>
