<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

<div>
    <section id="courses" class="section active">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-8">Cursos Disponibles</h1><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"><div class="course-card bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-hidden flex flex-col cursor-pointer hover:shadow-lg transition-shadow"><div class="aspect-video bg-stone-200"><img src="https://images.unsplash.com/photo-1543286386-713bdd548da4?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/e2e8f0/94a3b8?text=Course';"></div><div class="p-6 flex-1 flex flex-col"><h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Introducción al Trading (101)</h3><p class="text-slate-600 dark:text-gray-400 text-sm mb-4 flex-1">Aprende los conceptos fundamentales del mercado.</p><span class="mt-4 bg-slate-700 text-white font-semibold py-2 px-4 rounded-lg text-center hover:bg-slate-800 transition-colors">Empezar Curso</span></div></div><div class="course-card relative bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 dark:from-amber-900/30 dark:via-amber-800/40 dark:to-orange-900/30 rounded-xl border-2 border-amber-300/40 dark:border-amber-700 shadow-lg overflow-hidden flex flex-col cursor-pointer hover:shadow-xl hover:border-amber-400/60 dark:hover:border-amber-600 transition-all duration-300">
                <!-- Decorative background elements -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-amber-300/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-orange-300/20 rounded-full blur-2xl"></div>

                <div class="aspect-video bg-amber-200/50 dark:bg-amber-800/30 relative z-10">
                    <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover blur-sm" onerror="this.onerror=null;this.src='https://placehold.co/600x400/f59e0b/ffffff?text=Premium+Course';">
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                        <button class="bg-amber-400 hover:bg-amber-300 dark:bg-amber-500 dark:hover:bg-amber-400 text-amber-900 dark:text-amber-100 font-bold py-2 px-4 text-sm rounded-lg flex items-center gap-2 transform-gpu transition-all duration-200 hover:scale-105 shadow-lg">
                            <ion-icon name="diamond" class="text-lg"></ion-icon>
                            <span>Acceso Premium</span>
                        </button>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-bold uppercase text-amber-700 dark:text-amber-400 bg-amber-200/50 dark:bg-amber-800/30 px-2 py-1 rounded-full">Premium</span>
                        <ion-icon name="diamond" class="text-amber-600 dark:text-amber-400 text-sm"></ion-icon>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Estrategias de Opciones Avanzadas</h3>
                    <p class="text-slate-600 dark:text-gray-300 text-sm mb-4 flex-1 leading-relaxed">Domina las estrategias más sofisticadas de opciones con acceso exclusivo a casos reales y análisis detallados.</p>
                    <button class="mt-4 bg-amber-400 hover:bg-amber-300 dark:bg-amber-500 dark:hover:bg-amber-400 text-amber-900 dark:text-amber-100 font-bold py-2 px-4 rounded-lg text-center transition-all duration-200 hover:scale-105 shadow-md flex items-center justify-center gap-2">
                        <ion-icon name="diamond" class="text-sm"></ion-icon>
                        <span>Acceder al Curso</span>
                    </button>
                </div>
            </div><div class="course-card bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-hidden flex flex-col cursor-pointer hover:shadow-lg transition-shadow"><div class="aspect-video bg-stone-200"><img src="https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/e2e8f0/94a3b8?text=Course';"></div><div class="p-6 flex-1 flex flex-col"><h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Análisis Técnico Avanzado</h3><p class="text-slate-600 dark:text-gray-400 text-sm mb-4 flex-1">Domina los indicadores y patrones de gráficos.</p><span class="mt-4 bg-slate-700 text-white font-semibold py-2 px-4 rounded-lg text-center hover:bg-slate-800 transition-colors">Empezar Curso</span></div></div></div>
    </section>
</div>
