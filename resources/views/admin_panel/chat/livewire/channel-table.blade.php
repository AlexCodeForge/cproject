<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Gestionar Canales de Chat</h1>
        <div class="flex items-center gap-4">
            <div class="flex items-center space-x-4">
                <x-text-input wire:model.live.debounce.300ms="search" placeholder="Buscar canal..." class="px-4 py-2 rounded-lg" />
                <select wire:model.live="typeFilter" class="w-48 px-4 py-2 pr-10 rounded-lg border border-stone-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="">Todos los Tipos</option>
                    <option value="public">Público</option>
                    <option value="premium">Premium</option>
                    <option value="private">Privado</option>
                    <option value="direct">Directo</option>
                </select>
            </div>
            <button wire:click="create" class="bg-slate-700 dark:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-slate-800 dark:hover:bg-gray-600">Nuevo Canal</button>
        </div>
    </div>

    <!-- Channel Management Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Total Canales</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalChannels }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                    <x-ionicon-chatbubbles-outline class="text-blue-600 dark:text-blue-400 text-xl w-6 h-6"/>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Canales Activos</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $activeChannels }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                    <x-ionicon-checkmark-circle-outline class="text-green-600 dark:text-green-400 text-xl w-6 h-6"/>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Canales Vacíos</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $emptyChannels }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/50 p-3 rounded-full">
                    <x-ionicon-sad-outline class="text-yellow-600 dark:text-yellow-400 text-xl w-6 h-6"/>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Nuevos Hoy</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $newChannelsToday }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full">
                    <x-ionicon-add-circle-outline class="text-purple-600 dark:text-purple-400 text-xl w-6 h-6"/>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-stone-50 dark:bg-gray-700/50 text-xs text-slate-600 dark:text-gray-400 uppercase">
                <tr>
                    <th class="p-4">Nombre del Canal</th>
                    <th class="p-4">Tipo</th>
                    <th class="p-4">Participantes</th>
                    <th class="p-4">Creado</th>
                    <th class="p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($channels as $channel)
                    <tr wire:key="channel-{{ $channel->id }}" class="border-b border-stone-200 dark:border-gray-700 hover:bg-stone-50 dark:hover:bg-gray-700/50">
                        <td class="p-4">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $channel->name }}</p>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                {{ ucfirst($channel->type) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 text-xs font-semibold px-2 py-1 rounded-full">{{ $channel->participants_count }}</span>
                        </td>
                        <td class="p-4">
                            <span class="text-slate-500 dark:text-gray-400">{{ $channel->created_at->format('d M, Y') }}</span>
                        </td>
                        <td class="p-4">
                            <button wire:click="viewChannel({{ $channel->id }})" class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white">Ver</button>
                            <button wire:click="edit({{ $channel->id }})" class="ml-4 text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white">Editar</button>
                            <button wire:click="confirmChannelDeletion({{ $channel->id }})" class="ml-4 text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron canales.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $channels->links() }}
    </div>

    <!-- Channel Detail Modal -->
    @if ($showModal && $selectedChannel)
        @teleport('body')
            <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="channel-modal-{{ $selectedChannel->id }}">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" wire:click="closeModal"></div>

                <!-- Modal Container -->
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-stone-50 dark:bg-gray-800 rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden" @click.stop>

                        <!-- Modal Header -->
                        <div class="p-6 bg-stone-100 dark:bg-gray-700/50 flex-shrink-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $selectedChannel->name }}</h2>
                                    <p class="text-sm text-slate-500 dark:text-gray-400">Creado el {{ $selectedChannel->created_at->format('d M, Y') }}</p>
                                </div>
                                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <x-ionicon-close class="text-2xl w-6 h-6"/>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="flex-grow p-6 overflow-y-auto">
                            <h3 class="font-semibold text-slate-600 dark:text-gray-300 mb-4">Participantes ({{ $selectedChannel->participants->count() }})</h3>
                            @if ($selectedChannel->participants->count() > 0)
                                <div class="bg-white dark:bg-gray-700/50 rounded-lg shadow-sm overflow-x-auto">
                                    <table class="w-full text-sm text-left">
                                        <thead class="bg-stone-50 dark:bg-gray-700/50 text-xs text-slate-600 dark:text-gray-400 uppercase">
                                            <tr>
                                                <th class="p-4">Usuario</th>
                                                <th class="p-4">Rol</th>
                                                <th class="p-4">Miembro Desde</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($selectedChannel->participants as $participant)
                                                @if($participant->user)
                                                <tr wire:key="participant-{{ $participant->id }}" class="border-b border-stone-200 dark:border-gray-700 hover:bg-stone-50 dark:hover:bg-gray-700/50">
                                                    <td class="p-4 flex items-center gap-3">
                                                        <img class="w-10 h-10 rounded-full" src="{{ $participant->user->profile_photo_url }}" alt="">
                                                        <div>
                                                            <p class="font-semibold text-slate-900 dark:text-white">{{ $participant->user->name }}</p>
                                                            <p class="text-slate-500 dark:text-gray-400 text-xs">{{ $participant->user->email }}</p>
                                                        </div>
                                                    </td>
                                                    <td class="p-4">
                                                        @if($participant->user->roles->isNotEmpty())
                                                            <span class="bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 text-xs font-semibold px-2 py-1 rounded-full">{{ $participant->user->roles->first()->name }}</span>
                                                        @else
                                                            <span class="bg-stone-100 dark:bg-gray-700 text-stone-800 dark:text-gray-300 text-xs font-semibold px-2 py-1 rounded-full">Sin Rol</span>
                                                        @endif
                                                    </td>
                                                    <td class="p-4 text-slate-500 dark:text-gray-400">{{ $participant->joined_at?->format('d M, Y') ?? 'N/A' }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-slate-500 dark:text-gray-400">No hay participantes en este canal.</p>
                            @endif
                        </div>

                        <!-- Modal Footer -->
                        <div class="px-6 py-4 bg-stone-100 dark:bg-gray-700/50 flex justify-end items-center flex-shrink-0">
                            <button wire:click="closeModal" class="px-4 py-2 bg-stone-200 dark:bg-slate-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-stone-300 dark:hover:bg-slate-600 font-semibold transition-colors">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endteleport
    @endif
    <!-- Create/Edit Channel Modal -->
    @if ($showChannelModal)
    @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="create-edit-channel-modal">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" wire:click="$set('showChannelModal', false)"></div>

            <!-- Modal Container -->
            <div class="flex min-h-full items-center justify-center p-4">
                <form wire:submit.prevent="save" class="relative bg-stone-50 dark:bg-gray-800 rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden" @click.stop>
                    <!-- Modal Header -->
                    <div class="p-6 bg-stone-100 dark:bg-gray-700/50 flex-shrink-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $editingChannel->exists ? 'Editar Canal' : 'Crear Nuevo Canal' }}</h2>
                            </div>
                            <button type="button" wire:click="$set('showChannelModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <x-ionicon-close class="text-2xl w-6 h-6"/>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="flex-grow p-6 overflow-y-auto space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Canal</label>
                            <input type="text" id="name" wire:model.defer="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                            <textarea id="description" wire:model.defer="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"></textarea>
                            @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Canal</label>
                            <select id="type" wire:model.defer="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="public">Público</option>
                                <option value="premium">Premium</option>
                                <option value="private">Privado</option>
                                <option value="direct">Directo</option>
                            </select>
                            @error('type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="max_members" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Máximo de Miembros (dejar en blanco para ilimitado)</label>
                            <input type="number" id="max_members" wire:model.defer="max_members" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            @error('max_members') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="requires_premium" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                <input type="checkbox" id="requires_premium" wire:model.defer="requires_premium" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-indigo-600 dark:checked:border-indigo-600">
                                <span class="ml-2">Requiere Suscripción Premium</span>
                            </label>
                            @error('requires_premium') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="is_active" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                <input type="checkbox" id="is_active" wire:model.defer="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-indigo-600 dark:checked:border-indigo-600">
                                <span class="ml-2">Canal Activo</span>
                            </label>
                            @error('is_active') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-stone-100 dark:bg-gray-700/50 flex justify-end flex-shrink-0">
                        <button type="button" wire:click="$set('showChannelModal', false)" class="px-4 py-2 bg-stone-200 dark:bg-slate-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-stone-300 dark:hover:bg-slate-500 font-semibold transition-colors mr-3">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-800 font-semibold transition-colors">
                            Guardar Canal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endteleport
    @endif

</div>
