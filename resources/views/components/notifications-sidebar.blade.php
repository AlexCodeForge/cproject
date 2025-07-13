        <!-- =================================================================== -->
        <!-- COMPONENT: NOTIFICATIONS SIDEBAR (OVERLAY)                          -->
        <!-- =================================================================== -->
        <aside id="notifications-sidebar" class="fixed top-0 right-0 h-full w-80 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-l border-stone-200 dark:border-gray-700 p-6 transform translate-x-full transition-transform duration-300 ease-in-out z-30">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Notificaciones</h2>
                <button id="close-notifications" class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200 text-2xl">&times;</button>
            </div>
            <div class="space-y-4">
                <div class="flex items-start space-x-3 bg-stone-100/50 dark:bg-gray-700/50 p-3 rounded-lg">
                    <x-ionicon-trending-up-outline class="text-green-600 w-6 h-6 mt-1" />
                    <div>
                        <p class="text-sm text-slate-800 dark:text-gray-200">Nueva alerta de compra para <span class="font-bold">NVDA</span>.</p>
                        <p class="text-xs text-slate-500 dark:text-gray-400">Hace 5 minutos</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 p-3 rounded-lg">
                    <x-ionicon-chatbubble-ellipses-outline class="text-blue-600 w-6 h-6 mt-1" />
                    <div>
                        <p class="text-sm text-slate-800 dark:text-gray-200"><span class="font-bold">Ana Torres</span> te mencionó en <span class="font-bold"># general</span>.</p>
                        <p class="text-xs text-slate-500 dark:text-gray-400">Hace 20 minutos</p>
                    </div>
                </div>
            </div>
        </aside>
