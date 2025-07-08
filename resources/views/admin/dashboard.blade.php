<x-app-layout title="Admin Dashboard - OptionRocket" :is-admin="true">
    <!-- Admin Dashboard Section -->
    <section id="admin-dashboard" class="section active">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-8">Dashboard de Administrador</h1>

        <!-- Key Performance Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-600 dark:text-gray-400">Ingresos Mensuales (MRR)</h3>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">$12,847</p>
                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">↗ +18.3% vs mes anterior</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                        <ion-icon name="trending-up" class="text-2xl text-green-600 dark:text-green-400"></ion-icon>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-600 dark:text-gray-400">Usuarios Activos</h3>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">1,247</p>
                        <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">↗ +12.5% este mes</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                        <ion-icon name="people" class="text-2xl text-blue-600 dark:text-blue-400"></ion-icon>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-600 dark:text-gray-400">Suscriptores Premium</h3>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">387</p>
                        <p class="text-sm text-amber-600 dark:text-amber-400 mt-1">↗ +8.2% conversión</p>
                    </div>
                    <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-lg">
                        <ion-icon name="diamond" class="text-2xl text-amber-600 dark:text-amber-400"></ion-icon>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-600 dark:text-gray-400">Alertas Enviadas</h3>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">2,156</p>
                        <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">↗ +24 hoy</p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg">
                        <ion-icon name="notifications" class="text-2xl text-purple-600 dark:text-purple-400"></ion-icon>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trading Performance & Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Trading Performance Chart -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Rendimiento de Trading (30 días)</h3>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-slate-600 dark:text-gray-400">Ganancias</span>
                        <div class="w-3 h-3 bg-red-500 rounded-full ml-3"></div>
                        <span class="text-sm text-slate-600 dark:text-gray-400">Pérdidas</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600 dark:text-gray-400">Operaciones Ganadoras</span>
                        <span class="font-semibold text-green-600 dark:text-green-400">73% (89/122)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600 dark:text-gray-400">Ganancia Promedio</span>
                        <span class="font-semibold text-slate-900 dark:text-white">+$1,247</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600 dark:text-gray-400">Pérdida Promedio</span>
                        <span class="font-semibold text-slate-900 dark:text-white">-$423</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600 dark:text-gray-400">Profit Factor</span>
                        <span class="font-semibold text-green-600 dark:text-green-400">2.94</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600 dark:text-gray-400">Sharpe Ratio</span>
                        <span class="font-semibold text-blue-600 dark:text-blue-400">1.87</span>
                    </div>
                </div>
            </div>

            <!-- User Engagement Stats -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Engagement de Usuario</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                                <ion-icon name="chatbubbles" class="text-blue-600 dark:text-blue-400"></ion-icon>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white">Mensajes en Chat</p>
                                <p class="text-sm text-slate-600 dark:text-gray-400">Últimas 24h</p>
                            </div>
                        </div>
                        <span class="text-xl font-bold text-slate-900 dark:text-white">847</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-lg">
                                <ion-icon name="document-text" class="text-green-600 dark:text-green-400"></ion-icon>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white">Posts Publicados</p>
                                <p class="text-sm text-slate-600 dark:text-gray-400">Esta semana</p>
                            </div>
                        </div>
                        <span class="text-xl font-bold text-slate-900 dark:text-white">23</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-lg">
                                <ion-icon name="play-circle" class="text-purple-600 dark:text-purple-400"></ion-icon>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white">Clases en Vivo</p>
                                <p class="text-sm text-slate-600 dark:text-gray-400">Asistencia promedio</p>
                            </div>
                        </div>
                        <span class="text-xl font-bold text-slate-900 dark:text-white">156</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-amber-100 dark:bg-amber-900/30 p-2 rounded-lg">
                                <ion-icon name="star" class="text-amber-600 dark:text-amber-400"></ion-icon>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white">Valoración Media</p>
                                <p class="text-sm text-slate-600 dark:text-gray-400">Satisfacción usuarios</p>
                            </div>
                        </div>
                        <span class="text-xl font-bold text-slate-900 dark:text-white">4.8/5</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Acciones Rápidas</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition-all duration-200 hover:shadow-lg">
                    <ion-icon name="flash" class="text-xl"></ion-icon>
                    <span class="font-medium">Enviar Alerta</span>
                </button>
                <button class="flex items-center gap-3 p-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition-all duration-200 hover:shadow-lg">
                    <ion-icon name="add-circle" class="text-xl"></ion-icon>
                    <span class="font-medium">Nuevo Post</span>
                </button>
                <button class="flex items-center gap-3 p-4 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-lg transition-all duration-200 hover:shadow-lg">
                    <ion-icon name="videocam" class="text-xl"></ion-icon>
                    <span class="font-medium">Clase en Vivo</span>
                </button>
                <button class="flex items-center gap-3 p-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-lg transition-all duration-200 hover:shadow-lg">
                    <ion-icon name="analytics" class="text-xl"></ion-icon>
                    <span class="font-medium">Ver Reportes</span>
                </button>
            </div>
        </div>
    </section>
</x-app-layout>
