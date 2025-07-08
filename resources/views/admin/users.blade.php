<x-app-layout title="User Management - OptionRocket" :is-admin="true">
    <!-- User Management Section -->
    <section id="user-management" class="section active">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-2">Gestión de Usuarios</h1>
                <p class="text-slate-500 dark:text-gray-400">Administra usuarios, roles y permisos del sistema.</p>
            </div>
            <button class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2 transition-all duration-200 hover:shadow-lg">
                <ion-icon name="person-add" class="text-xl"></ion-icon>
                <span>Nuevo Usuario</span>
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-600 dark:text-gray-300">Total Usuarios</h3>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ \App\Models\User::count() }}</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                        <ion-icon name="people" class="text-2xl text-blue-600 dark:text-blue-400"></ion-icon>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-600 dark:text-gray-300">Usuarios Activos</h3>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ \App\Models\User::whereNotNull('email_verified_at')->count() }}</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                        <ion-icon name="checkmark-circle" class="text-2xl text-green-600 dark:text-green-400"></ion-icon>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-600 dark:text-gray-300">Administradores</h3>
                        <p class="text-3xl font-bold text-purple-600 mt-2">{{ \App\Models\User::role('admin')->count() }}</p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg">
                        <ion-icon name="shield-checkmark" class="text-2xl text-purple-600 dark:text-purple-400"></ion-icon>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-600 dark:text-gray-300">Nuevos (30 días)</h3>
                        <p class="text-3xl font-bold text-amber-600 mt-2">{{ \App\Models\User::where('created_at', '>=', now()->subDays(30))->count() }}</p>
                    </div>
                    <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-lg">
                        <ion-icon name="calendar" class="text-2xl text-amber-600 dark:text-amber-400"></ion-icon>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm mb-8">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <ion-icon name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 dark:text-gray-500"></ion-icon>
                        <input type="text" placeholder="Buscar usuarios..." class="w-full pl-10 pr-4 py-3 bg-stone-50 dark:bg-gray-700 border border-stone-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-slate-900 dark:text-white">
                    </div>
                </div>
                <select class="px-4 py-3 bg-stone-50 dark:bg-gray-700 border border-stone-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-slate-900 dark:text-white">
                    <option>Todos los roles</option>
                    <option>Administrador</option>
                    <option>Usuario</option>
                </select>
                <select class="px-4 py-3 bg-stone-50 dark:bg-gray-700 border border-stone-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-slate-900 dark:text-white">
                    <option>Todos los estados</option>
                    <option>Activo</option>
                    <option>Inactivo</option>
                    <option>Pendiente verificación</option>
                </select>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Lista de Usuarios</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-stone-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-gray-400 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-gray-400 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-gray-400 uppercase tracking-wider">Registro</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200 dark:divide-gray-700">
                        @foreach(\App\Models\User::with('roles')->latest()->paginate(10) as $user)
                        <tr class="hover:bg-stone-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-sm text-slate-500 dark:text-gray-400">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900 dark:text-white">{{ $user->email }}</div>
                                @if($user->email_verified_at)
                                    <div class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1">
                                        <ion-icon name="checkmark-circle" class="text-xs"></ion-icon>
                                        Verificado
                                    </div>
                                @else
                                    <div class="text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1">
                                        <ion-icon name="time" class="text-xs"></ion-icon>
                                        Pendiente
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->hasRole('admin'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                        <ion-icon name="shield-checkmark" class="mr-1 text-xs"></ion-icon>
                                        Administrador
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        <ion-icon name="person" class="mr-1 text-xs"></ion-icon>
                                        Usuario
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        <ion-icon name="checkmark-circle" class="mr-1 text-xs"></ion-icon>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                        <ion-icon name="time" class="mr-1 text-xs"></ion-icon>
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-gray-400">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <button class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                        <ion-icon name="create" class="text-lg"></ion-icon>
                                    </button>
                                    <button class="text-slate-600 dark:text-gray-400 hover:text-slate-700 dark:hover:text-gray-300 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <ion-icon name="eye" class="text-lg"></ion-icon>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <button class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <ion-icon name="trash" class="text-lg"></ion-icon>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-stone-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-500 dark:text-gray-400">
                        Mostrando 1-10 de {{ \App\Models\User::count() }} usuarios
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-2 text-sm text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            Anterior
                        </button>
                        <button class="px-3 py-2 text-sm bg-blue-600 text-white rounded-lg">1</button>
                        <button class="px-3 py-2 text-sm text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-gray-700 rounded-lg transition-colors">2</button>
                        <button class="px-3 py-2 text-sm text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-gray-700 rounded-lg transition-colors">3</button>
                        <button class="px-3 py-2 text-sm text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
