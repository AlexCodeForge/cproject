<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="relative bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 dark:from-amber-900/20 dark:via-amber-800/30 dark:to-orange-900/20 overflow-hidden shadow-xl sm:rounded-2xl border-2 border-amber-300/40 dark:border-amber-700">
                <!-- Decorative background elements -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-amber-300/20 dark:bg-amber-600/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-orange-300/20 dark:bg-orange-600/10 rounded-full blur-2xl"></div>

                <div class="relative z-10 p-6 md:p-10 text-gray-900 dark:text-gray-100 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                            <svg class="w-16 h-16 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>

                    <h2 class="text-4xl sm:text-5xl font-extrabold mb-4">
                        <span class="bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                            ¡Gracias por Suscribirte!
                        </span>
                    </h2>

                    <p class="text-lg text-slate-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto">
                        ¡Bienvenido a la experiencia Premium! Tu cuenta ya está activa y has desbloqueado todos los beneficios exclusivos.
                    </p>

                    <div class="text-left bg-white/50 dark:bg-gray-900/30 backdrop-blur-sm border border-amber-200/50 dark:border-amber-800/50 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-slate-800 dark:text-white">Esto es lo que obtienes con tu Membresía Premium:</h3>
                        <ul class="space-y-3 list-disc list-inside text-slate-700 dark:text-gray-300">
                            <li><strong>Alertas de Trading Exclusivas:</strong> Notificaciones en tiempo real para oportunidades de mercado.</li>
                            <li><strong>Contenido Exclusivo:</strong> Acceso a todas las publicaciones y análisis premium.</li>
                            <li><strong>Sesiones de Trading en Vivo:</strong> Únete a nuestros expertos en vivo y míralos operar.</li>
                            <li><strong>Acceso al Chat Comunitario:</strong> Interactúa con otros traders y nuestro equipo.</li>
                            <li><strong>Cursos Educativos:</strong> Acceso completo a toda nuestra biblioteca de cursos.</li>
                            <li><strong>¡Y mucho más!</strong></li>
                        </ul>
                    </div>

                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-8 py-3 border border-transparent text-base font-bold rounded-lg shadow-lg text-amber-900 bg-gradient-to-r from-amber-400 to-orange-400 hover:from-amber-300 hover:to-orange-300 transition-all duration-300 transform hover:scale-105">
                        <ion-icon name="rocket-outline" class="text-xl"></ion-icon>
                        <span>Ir a tu Panel</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
