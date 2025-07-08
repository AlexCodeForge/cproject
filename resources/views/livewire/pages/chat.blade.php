<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

<div>
    <section id="chat" class="section active">
        <div class="h-full flex flex-col max-w-7xl mx-auto">
            <div class="flex-1 flex bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm min-h-[calc(100vh-10rem)]">
                <!-- Sidebar with channels -->
                <div class="w-64 bg-stone-50/50 dark:bg-gray-700/50 p-4 border-r border-stone-200 dark:border-gray-600 flex-col hidden sm:flex">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Canales</h2>
                    <nav class="space-y-2 flex-grow">
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-slate-700 dark:bg-slate-600 text-white font-semibold">
                            <ion-icon name="chatbox-ellipses"></ion-icon>
                            <span># general</span>
                        </a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 rounded-lg text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-600 hover:text-slate-800 dark:hover:text-gray-200 transition-colors">
                            <ion-icon name="trending-up"></ion-icon>
                            <span># análisis</span>
                        </a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 rounded-lg text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-600 hover:text-slate-800 dark:hover:text-gray-200 transition-colors">
                            <ion-icon name="options"></ion-icon>
                            <span># opciones</span>
                        </a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 rounded-lg text-amber-600 dark:text-amber-400 hover:bg-stone-200 dark:hover:bg-gray-600 hover:text-amber-700 dark:hover:text-amber-300 font-semibold transition-colors">
                            <ion-icon name="diamond"></ion-icon>
                            <span># premium</span>
                        </a>
                    </nav>
                </div>

                <!-- Main chat area -->
                <div class="flex-1 flex flex-col">
                    <!-- Chat header -->
                    <div class="p-4 border-b border-stone-200 dark:border-gray-600">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white"># general</h3>
                    </div>

                    <!-- Messages area -->
                    <div class="flex-1 p-6 space-y-6 overflow-y-auto bg-stone-50/30 dark:bg-gray-800/30">
                        <!-- Message from Robert -->
                        <div class="flex items-start space-x-4">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-10 h-10 rounded-full" alt="Avatar" onerror="this.onerror=null;this.src='https://placehold.co/40x40/e2e8f0/94a3b8?text=RF';">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">
                                    Robert Fox
                                    <span class="text-xs text-slate-500 dark:text-gray-400 ml-2">10:30 AM</span>
                                </p>
                                <div class="mt-1 bg-white dark:bg-gray-700 border border-stone-200 dark:border-gray-600 p-3 rounded-lg rounded-tl-none shadow-sm">
                                    <p class="text-slate-800 dark:text-gray-200">¿Alguien tiene en la mira a $AMD hoy?</p>
                                </div>
                            </div>
                        </div>

                        <!-- Message from Marvin (current user) -->
                        <div class="flex items-start flex-row-reverse space-x-4 space-x-reverse">
                            <img src="https://randomuser.me/api/portraits/women/11.jpg" class="w-10 h-10 rounded-full" alt="Avatar" onerror="this.onerror=null;this.src='https://placehold.co/40x40/e2e8f0/94a3b8?text=M';">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white text-right">
                                    Marvin
                                    <span class="text-xs text-slate-500 dark:text-gray-400 ml-2">10:31 AM</span>
                                </p>
                                <div class="mt-1 bg-slate-700 dark:bg-slate-600 text-white p-3 rounded-lg rounded-tr-none shadow-sm">
                                    <p>¡Hola! Sí, esperando rebote en $155.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message input -->
                    <div class="p-4 border-t border-stone-200 dark:border-gray-600">
                        <div class="bg-stone-100 dark:bg-gray-700 flex items-center rounded-lg px-4 py-2 border border-stone-300 dark:border-gray-600 focus-within:border-slate-500 dark:focus-within:border-slate-400 transition-colors">
                            <input type="text" placeholder="Escribe un mensaje..." class="bg-transparent w-full focus:outline-none text-slate-800 dark:text-gray-200 placeholder-slate-500 dark:placeholder-gray-400">
                            <button class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200 transition-colors">
                                <ion-icon name="send" class="text-xl"></ion-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
