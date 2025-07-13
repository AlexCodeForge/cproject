<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Gestionar Categorías de Posts</h1>
        <div class="flex items-center gap-4">
            <div class="flex items-center space-x-4">
                <x-text-input wire:model.live.debounce.300ms="search" placeholder="Buscar categoría..." class="px-4 py-2 rounded-lg" />
                <select wire:model.live="statusFilter" class="w-48 px-4 py-2 pr-10 rounded-lg border border-stone-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="">Todos los Estados</option>
                    <option value="1">Activa</option>
                    <option value="0">Inactiva</option>
                </select>
            </div>
            <button wire:click="create" class="bg-slate-700 dark:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-slate-800 dark:hover:bg-gray-600">Nueva Categoría</button>
        </div>
    </div>

    <!-- Category Management Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Total Categorías</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalCategories }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                    <x-ionicon-grid-outline class="text-blue-600 dark:text-blue-400 text-xl w-6 h-6"/>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Categorías Activas</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $activeCategories }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                    <x-ionicon-checkmark-circle-outline class="text-green-600 dark:text-green-400 text-xl w-6 h-6"/>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Categorías Vacías</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $emptyCategories }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/50 p-3 rounded-full">
                    <x-ionicon-sad-outline class="text-yellow-600 dark:text-yellow-400 text-xl w-6 h-6"/>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-stone-50 dark:bg-gray-700/50 text-xs text-slate-600 dark:text-gray-400 uppercase">
                <tr>
                    <th class="p-4">Nombre</th>
                    <th class="p-4">Posts</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4">Orden</th>
                    <th class="p-4">Creado</th>
                    <th class="p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr wire:key="category-{{ $category->id }}" class="border-b border-stone-200 dark:border-gray-700 hover:bg-stone-50 dark:hover:bg-gray-700/50">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full" style="background-color: {{ $category->color ?? '#ccc' }}"></span>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $category->name }}</p>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 text-xs font-semibold px-2 py-1 rounded-full">{{ $category->posts_count }}</span>
                        </td>
                        <td class="p-4">
                            @if ($category->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                    Activa
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                    Inactiva
                                </span>
                            @endif
                        </td>
                         <td class="p-4">
                            <span class="text-slate-500 dark:text-gray-400">{{ $category->sort_order }}</span>
                        </td>
                        <td class="p-4">
                            <span class="text-slate-500 dark:text-gray-400">{{ $category->created_at->format('d M, Y') }}</span>
                        </td>
                        <td class="p-4">
                            <button wire:click="viewCategory({{ $category->id }})" class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white">Ver</button>
                            <button wire:click="edit({{ $category->id }})" class="ml-4 text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white">Editar</button>
                            <button wire:click="confirmCategoryDeletion({{ $category->id }})" class="ml-4 text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron categorías.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>

    <!-- Category Detail Modal -->
    @if ($showModal && $selectedCategory)
        @teleport('body')
            <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="category-modal-{{ $selectedCategory->id }}">
                <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-stone-50 dark:bg-gray-800 rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden" @click.stop>
                        <div class="p-6 bg-stone-100 dark:bg-gray-700/50 flex-shrink-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $selectedCategory->name }}</h2>
                                    <p class="text-sm text-slate-500 dark:text-gray-400">Creada el {{ $selectedCategory->created_at->format('d M, Y') }}</p>
                                </div>
                                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <x-ionicon-close class="text-2xl w-6 h-6"/>
                                </button>
                            </div>
                        </div>
                        <div class="flex-grow p-6 overflow-y-auto">
                            <h3 class="font-semibold text-slate-600 dark:text-gray-300 mb-4">Posts en esta categoría ({{ $selectedCategory->posts->count() }})</h3>
                            @if ($selectedCategory->posts->count() > 0)
                                <div class="bg-white dark:bg-gray-700/50 rounded-lg shadow-sm overflow-x-auto">
                                    <ul class="divide-y divide-stone-200 dark:divide-gray-700">
                                        @foreach ($selectedCategory->posts as $post)
                                            <li class="p-4">{{ $post->title }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <p class="text-slate-500 dark:text-gray-400">No hay posts en esta categoría.</p>
                            @endif
                        </div>
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
    <!-- Create/Edit Category Modal -->
    @if ($showCategoryModal)
    @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="create-edit-category-modal">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" wire:click="$set('showCategoryModal', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <form wire:submit.prevent="save" class="relative bg-stone-50 dark:bg-gray-800 rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden" @click.stop>
                    <div class="p-6 bg-stone-100 dark:bg-gray-700/50 flex-shrink-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ optional($editingCategory)->exists ? 'Editar Categoría' : 'Crear Nueva Categoría' }}</h2>
                            </div>
                            <button type="button" wire:click="$set('showCategoryModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <x-ionicon-close class="text-2xl w-6 h-6"/>
                            </button>
                        </div>
                    </div>
                    <div class="flex-grow p-6 overflow-y-auto space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre de la Categoría</label>
                            <input type="text" id="name" wire:model.defer="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                            <textarea id="description" wire:model.defer="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"></textarea>
                            @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                             <div>
                                <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label>
                                <input type="color" id="color" wire:model.defer="color" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                @error('color') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Icono (ej: 'home-outline')</label>
                                <input type="text" id="icon" wire:model.defer="icon" placeholder="home-outline" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                @error('icon') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Orden de Clasificación</label>
                            <input type="number" id="sort_order" wire:model.defer="sort_order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            @error('sort_order') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="is_active" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                <input type="checkbox" id="is_active" wire:model.defer="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-indigo-600 dark:checked:border-indigo-600">
                                <span class="ml-2">Categoría Activa</span>
                            </label>
                            @error('is_active') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-stone-100 dark:bg-gray-700/50 flex justify-end flex-shrink-0">
                        <button type="button" wire:click="$set('showCategoryModal', false)" class="px-4 py-2 bg-stone-200 dark:bg-slate-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-stone-300 dark:hover:bg-slate-500 font-semibold transition-colors mr-3">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-800 font-semibold transition-colors">
                            Guardar Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endteleport
    @endif
</div>
