<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Gestionar Usuarios</h1>
        <div class="flex items-center gap-4">
            <div class="flex items-center space-x-4">
                <x-text-input wire:model.live.debounce.300ms="search" placeholder="Buscar usuario..." class="px-4 py-2 rounded-lg" />
                <select wire:model.live="role" class="w-48 px-4 py-2 pr-10 rounded-lg border border-stone-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="">Todos los Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- User Management Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Total Usuarios</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalUsers }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                    <x-ionicon-people-outline class="text-blue-600 dark:text-blue-400 text-xl h-6 w-6" />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Usuarios Premium</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $premiumUsers }}</p>
                </div>
                <div class="bg-amber-100 dark:bg-amber-900/50 p-3 rounded-full">
                    <x-ionicon-star-outline class="text-amber-600 dark:text-amber-400 text-xl h-6 w-6" />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Usuarios Activos</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $activeUsers }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                    <x-ionicon-checkmark-circle-outline class="text-green-600 dark:text-green-400 text-xl h-6 w-6" />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Nuevos Hoy</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $newUsersToday }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full">
                    <x-ionicon-person-add-outline class="text-purple-600 dark:text-purple-400 text-xl h-6 w-6" />
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-stone-50 dark:bg-gray-700/50 text-xs text-slate-600 dark:text-gray-400 uppercase">
                <tr>
                    <th class="p-4">Usuario</th>
                    <th class="p-4">Rol</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-stone-200 dark:border-gray-700 hover:bg-stone-50 dark:hover:bg-gray-700/50">
                        <td class="p-4 flex items-center gap-3">
                            <img class="w-10 h-10 rounded-full" src="{{ $user->avatar_url }}" alt="">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
                                <p class="text-slate-500 dark:text-gray-400 text-xs">{{ $user->email }}</p>
                            </div>
                        </td>
                        <td class="p-4">
                            @if($user->roles->isNotEmpty())
                                <span class="bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 text-xs font-semibold px-2 py-1 rounded-full">{{ $user->roles->first()->name }}</span>
                            @else
                                <span class="bg-stone-100 dark:bg-gray-700 text-stone-800 dark:text-gray-300 text-xs font-semibold px-2 py-1 rounded-full">Sin Rol</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if ($user->email_verified_at)
                                <span class="bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs font-semibold px-2 py-1 rounded-full">Activo</span>
                            @else
                                <span class="bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 text-xs font-semibold px-2 py-1 rounded-full">Pendiente</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <button wire:click="viewUser({{ $user->id }})" class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white">Ver</button>
                            @if (!$user->email_verified_at)
                                <button wire:click="toggleUserStatus({{ $user->id }})" class="ml-4 text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white">
                                    Verificar
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron usuarios que coincidan con sus criterios.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- User Detail Modal -->
    @if ($showModal && $selectedUser)
        @teleport('body')
            <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="user-modal-{{ $selectedUser->id }}">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" wire:click="closeModal"></div>

                <!-- Modal Container -->
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-stone-50 dark:bg-gray-800 rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden" @click.stop>

                        <!-- Modal Header -->
                        <div class="p-6 bg-stone-100 dark:bg-gray-700/50 flex-shrink-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="h-16 w-16 rounded-full" src="{{ $selectedUser->avatar_url }}" alt="{{ $selectedUser->name }}">
                                    <div class="ml-4">
                                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $selectedUser->name }}</h2>
                                        <p class="text-sm text-slate-500 dark:text-gray-400">{{ $selectedUser->email }}</p>
                                    </div>
                                </div>
                                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <x-ionicon-close-outline class="text-2xl h-6 w-6" />
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="flex-grow p-6 grid grid-cols-1 md:grid-cols-3 gap-6 overflow-y-auto">
                            <!-- Left Column -->
                            <div class="md:col-span-1 space-y-4">
                                <div>
                                    <h3 class="font-semibold text-slate-600 dark:text-gray-300">Rol de Usuario</h3>
                                    <p class="text-slate-900 dark:text-white">{{ $selectedUser->roles->pluck('name')->join(', ') ?: 'Sin Rol' }}</p>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-600 dark:text-gray-300">Miembro Desde</h3>
                                    <p class="text-slate-900 dark:text-white">{{ $selectedUser->created_at->format('d M, Y') }}</p>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-600 dark:text-gray-300">Estado del Email</h3>
                                    @if ($selectedUser->email_verified_at)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                            Verificado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">
                                            Pendiente
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- Right Column -->
                            <div class="md:col-span-2 space-y-4">
                                <div>
                                    <h3 class="font-semibold text-slate-600 dark:text-gray-300">Biografía</h3>
                                    <p class="text-slate-900 dark:text-white">{{ $selectedUser->profile?->bio ?: 'No disponible' }}</p>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-600 dark:text-gray-300">Suscripción</h3>
                                    <p class="text-slate-900 dark:text-white">{{ $selectedUser->subscriptions->first()?->name ?? 'Sin Suscripción' }}</p>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-600 dark:text-gray-300">Publicaciones Recientes</h3>
                                    <ul class="list-disc list-inside text-slate-900 dark:text-white">
                                        @forelse($selectedUser->posts->take(3) as $post)
                                            <li>{{ $post->title }}</li>
                                        @empty
                                            <li>Sin publicaciones recientes.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="px-6 py-4 bg-stone-100 dark:bg-gray-700/50 flex justify-end flex-shrink-0">
                            <button wire:click="closeModal" class="px-4 py-2 bg-stone-200 dark:bg-slate-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-stone-300 dark:hover:bg-slate-600 font-semibold transition-colors">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endteleport
    @endif
</div>
