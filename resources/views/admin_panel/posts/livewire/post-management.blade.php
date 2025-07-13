<div class="p-6">
    <div class="flex flex-wrap justify-between items-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Gestionar Publicaciones</h1>
        <div class="flex items-center gap-4 ml-auto pt-2">
            <div class="flex flex-wrap items-center gap-4">
                <x-text-input wire:model.live.debounce.300ms="search" placeholder="Buscar publicación..." class="px-4 py-2 rounded-lg w-auto" />
                <select wire:model.live="category" class="w-48 px-4 py-2 pr-10 rounded-lg border border-stone-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="">Todas las Categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ ucfirst($cat->name) }}</option>
                    @endforeach
                </select>
                <select wire:model.live="status" class="w-48 px-4 py-2 pr-10 rounded-lg border border-stone-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="">Todos los Estados</option>
                    @foreach($availableStatuses as $stat)
                        <option value="{{ $stat }}">{{ ucfirst($stat) }}</option>
                    @endforeach
                </select>
                <select wire:model.live="featured" class="w-48 px-4 py-2 pr-10 rounded-lg border border-stone-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="">Todos (Destacados)</option>
                    @foreach($availableFeatured as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="createNewPost" class="bg-slate-700 dark:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-slate-800 dark:hover:bg-gray-600 whitespace-nowrap" >
                Nueva Publicación
            </button>
        </div>
    </div>

    <!-- Post Management Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Total Publicaciones</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $posts->total() }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                    <x-ionicon-document-text-outline class="text-blue-600 dark:text-blue-400 text-xl h-6 w-6" />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Publicaciones Publicadas</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $this->publishedPostsCount }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                    <x-ionicon-checkmark-circle-outline class="text-green-600 dark:text-green-400 text-xl h-6 w-6" />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Publicaciones Destacadas</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $this->featuredPostsCount }}</p>
                </div>
                <div class="bg-amber-100 dark:bg-amber-900/50 p-3 rounded-full">
                    <x-ionicon-star-outline class="text-amber-600 dark:text-amber-400 text-xl h-6 w-6" />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Publicaciones Premium</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $this->premiumPostsCount }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full">
                    <x-ionicon-diamond-outline class="text-purple-600 dark:text-purple-400 text-xl h-6 w-6" />
                </div>
            </div>
        </div>
    </div>

    {{-- Session Message Placeholder --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-stone-50 dark:bg-gray-700/50 text-xs text-slate-600 dark:text-gray-400 uppercase">
                <tr>
                    <th class="p-4">Título</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4">Categoría</th>
                    <th class="p-4">Autor</th>
                    <th class="p-4">Publicado El</th>
                    <th class="p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr class="border-b border-stone-200 dark:border-gray-700 hover:bg-stone-50 dark:hover:bg-gray-700/50">
                        <td class="p-4">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $post->title }}</p>
                                <p class="text-slate-500 dark:text-gray-400 text-xs">{{ $post->slug }}</p>
                            </div>
                        </td>
                        <td class="p-4">
                            @if ($post->status === 'published')
                                <span class="bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs font-semibold px-2 py-1 rounded-full">Publicado</span>
                            @elseif ($post->status === 'draft')
                                <span class="bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 text-xs font-semibold px-2 py-1 rounded-full">Borrador</span>
                            @else
                                <span class="bg-stone-100 dark:bg-gray-700 text-stone-800 dark:text-gray-300 text-xs font-semibold px-2 py-1 rounded-full">Archivado</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($post->category)
                                <span class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300 text-xs font-semibold px-2 py-1 rounded-full">{{ $post->category->name }}</span>
                            @else
                                <span class="bg-stone-100 dark:bg-gray-700 text-stone-800 dark:text-gray-300 text-xs font-semibold px-2 py-1 rounded-full">Sin Categoría</span>
                            @endif
                        </td>
                        <td class="p-4 text-slate-900 dark:text-white">{{ $post->user->name }}</td>
                        <td class="p-4 text-slate-900 dark:text-white">{{ $post->published_at ? $post->published_at->format('d M, Y') : 'N/A' }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-4 text-sm">
                                <button wire:click="showPost('{{ $post->slug }}')" class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">Ver</button>
                                <button wire:click="editPost({{ $post->id }})" class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white">Editar</button>
                                <button wire:click="confirmDelete({{ $post->id }})" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron publicaciones que coincidan con sus criterios.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
