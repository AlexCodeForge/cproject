<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Gestionar Cursos</h1>
        <div class="flex items-center gap-4">
            <div class="flex items-center space-x-4">
                <x-text-input wire:model.live.debounce.300ms="search" placeholder="Buscar curso..." class="px-4 py-2 rounded-lg" />
                <select wire:model.live="statusFilter" class="w-48 px-4 py-2 pr-10 rounded-lg border border-stone-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="">Todos los Estados</option>
                    <option value="draft">Borrador</option>
                    <option value="published">Publicado</option>
                    <option value="archived">Archivado</option>
                </select>
                <select wire:model.live="isPremiumFilter" class="w-48 px-4 py-2 pr-10 rounded-lg border border-stone-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="">Todos los Tipos</option>
                    <option value="0">Gratis</option>
                    <option value="1">Premium</option>
                </select>
            </div>
            <button wire:click="create" class="bg-slate-700 dark:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-slate-800 dark:hover:bg-gray-600">Nuevo Curso</button>
        </div>
    </div>

    <!-- Course Management Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Total Cursos</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalCourses }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                    <x-ionicon-book-outline class="text-blue-600 dark:text-blue-400 text-xl w-6 h-6"/>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Cursos Publicados</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $publishedCourses }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                    <x-ionicon-checkmark-circle-outline class="text-green-600 dark:text-green-400 text-xl w-6 h-6"/>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Cursos Premium</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $premiumCourses }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/50 p-3 rounded-full">
                    <x-ionicon-star-outline class="text-yellow-600 dark:text-yellow-400 text-xl w-6 h-6"/>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Cursos Borrador</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $draftCourses }}</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/50 p-3 rounded-full">
                    <x-ionicon-document-text-outline class="text-red-600 dark:text-red-400 text-xl w-6 h-6"/>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-stone-50 dark:bg-gray-700/50 text-xs text-slate-600 dark:text-gray-400 uppercase">
                <tr>
                    <th class="p-4">Título del Curso</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4">Tipo</th>
                    <th class="p-4">Creado</th>
                    <th class="p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                    <tr wire:key="course-{{ $course->id }}" class="border-b border-stone-200 dark:border-gray-700 hover:bg-stone-50 dark:hover:bg-gray-700/50">
                        <td class="p-4">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $course->title }}</p>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($course->status == 'published') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300
                                @elseif($course->status == 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                @else bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 @endif">
                                {{ ucfirst($course->status) }}
                            </span>
                        </td>
                        <td class="p-4">
                            @if($course->is_premium)
                                <span class="bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 text-xs font-semibold px-2 py-1 rounded-full">Premium</span>
                            @else
                                <span class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 text-xs font-semibold px-2 py-1 rounded-full">Gratis</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="text-slate-500 dark:text-gray-400">{{ $course->created_at->format('d M, Y') }}</span>
                        </td>
                        <td class="p-4">
                            <a href="{{ route('admin.lms.courses.edit', $course->id) }}" class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white">Gestionar Contenido</a>
                            <button wire:click="edit({{ $course->id }})" class="ml-4 text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white">Editar Info</button>
                            <button wire:click="confirmCourseDeletion({{ $course->id }})" class="ml-4 text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron cursos.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $courses->links() }}
    </div>

    <!-- Create/Edit Course Modal -->
    @if ($showCourseModal)
    @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="create-edit-course-modal">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" wire:click="$set('showCourseModal', false)"></div>

            <!-- Modal Container -->
            <div class="flex min-h-full items-center justify-center p-4">
                <form wire:submit.prevent="save" class="relative bg-stone-50 dark:bg-gray-800 rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden" @click.stop>
                    <!-- Modal Header -->
                    <div class="p-6 bg-stone-100 dark:bg-gray-700/50 flex-shrink-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $editingCourse->exists ? 'Editar Curso' : 'Crear Nuevo Curso' }}</h2>
                            </div>
                            <button type="button" wire:click="$set('showCourseModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <x-ionicon-close class="text-2xl w-6 h-6"/>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="flex-grow p-6 overflow-y-auto space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título del Curso</label>
                            <input type="text" id="title" wire:model.defer="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            @error('title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                            <textarea id="description" wire:model.defer="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"></textarea>
                            @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado del Curso</label>
                            <select id="status" wire:model.defer="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="draft">Borrador</option>
                                <option value="published">Publicado</option>
                                <option value="archived">Archivado</option>
                            </select>
                            @error('status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="is_premium" wire:model.defer="is_premium" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            <label for="is_premium" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Curso Premium</label>
                            @error('is_premium') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-stone-100 dark:bg-gray-700/50 flex justify-end items-center flex-shrink-0">
                        <button type="button" wire:click="$set('showCourseModal', false)" class="mr-4 px-4 py-2 bg-stone-200 dark:bg-slate-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-stone-300 dark:hover:bg-slate-600 font-semibold transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-slate-700 dark:bg-gray-700 text-white rounded-lg hover:bg-slate-800 dark:hover:bg-gray-600 font-semibold transition-colors">
                            Guardar Curso
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endteleport
    @endif
</div>
