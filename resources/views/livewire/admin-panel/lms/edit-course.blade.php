<div class="p-6">
    <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-6">Gestionar Curso: {{ $course->title }}</h1>

    <!-- Course Details Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6 mb-8">
        <h2 class="text-xl font-semibold text-slate-800 dark:text-white mb-4">Detalles del Curso</h2>
        <form wire:submit.prevent="saveCourseDetails" class="space-y-4">
            <div>
                <label for="courseTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título del Curso</label>
                <input type="text" id="courseTitle" wire:model.defer="courseTitle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                @error('courseTitle') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="courseDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                <div wire:ignore>
                    <input id="courseDescription" type="hidden" name="courseDescription" wire:model.defer="courseDescription">
                    <trix-editor input="courseDescription" class="trix-content mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"></trix-editor>
                </div>
                @error('courseDescription') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="courseImageUrl" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL de la Imagen (Opcional)</label>
                <input type="text" id="courseImageUrl" wire:model.defer="courseImageUrl" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                @error('courseImageUrl') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="courseIsPremium" wire:model.defer="courseIsPremium" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                <label for="courseIsPremium" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Curso Premium</label>
                @error('courseIsPremium') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="courseStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado del Curso</label>
                <select id="courseStatus" wire:model.defer="courseStatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    <option value="draft">Borrador</option>
                    <option value="published">Publicado</option>
                    <option value="archived">Archivado</option>
                </select>
                @error('courseStatus') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-slate-700 dark:bg-gray-700 text-white rounded-lg hover:bg-slate-800 dark:hover:bg-gray-600 font-semibold transition-colors">
                    Guardar Detalles del Curso
                </button>
            </div>
        </form>
    </div>

    <!-- Sections Management -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6 mb-8">
        <h2 class="text-xl font-semibold text-slate-800 dark:text-white mb-4">Secciones del Curso</h2>

        <div x-data="{ sections: @entangle('sections').defer }" x-on:section-reordered.window="sections = $event.detail"
             x-init="
                 new Sortable($el, {
                     animation: 150,
                     handle: '.handle-section',
                     onEnd: function (evt) {
                         const newOrder = Array.from(evt.to.children).map((item, index) => ({
                             value: item.dataset.id,
                             order: index + 1
                         }));
                         $wire.reorderSections(newOrder);
                     }
                 });
             ">
            @forelse ($sections as $section)
                <div x-data="{
                        openLessonForm: false,
                        alpineEditingLessonId: null,
                        editingSection: {{ $editingSection && $editingSection->id === $section['id'] ? 'true' : 'false' }},
                        lessons: @js($section['lessons'])
                     }"
                     @lessons-updated.window="if ($event.detail.sectionId === {{ $section['id'] }}) lessons = Array.isArray($event.detail) && $event.detail.length > 0 ? $event.detail[0].lessons : $event.detail.lessons"
                     @lesson-updated-hide-form.window="alpineEditingLessonId = null"
                     wire:key="section-{{ $section['id'] }}"
                     data-id="{{ $section['id'] }}"
                     class="bg-stone-50 dark:bg-gray-700 rounded-lg p-4 mb-4 border border-stone-200 dark:border-gray-600">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <x-ionicon-reorder-four-outline class="w-6 h-6 handle-section cursor-grab text-gray-500 dark:text-gray-400"/>
                            @if ($editingSection && $editingSection->id === $section['id'])
                                <input type="text" wire:model.defer="editingSectionTitle" class="flex-grow rounded-md border-gray-300 shadow-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                @error('editingSectionTitle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @else
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Sección: {{ $section['title'] }}</h3>
                            @endif
                        </div>
                        <div>
                            @if ($editingSection && $editingSection->id === $section['id'])
                                <button wire:click="updateSection" class="ml-2 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200">Guardar</button>
                                <button wire:click="$set('editingSection', null)" class="ml-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Cancelar</button>
                            @else
                                <button wire:click="editSection({{ $section['id'] }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">Editar Título</button>
                                <button wire:click="deleteSection({{ $section['id'] }})" class="ml-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200">Eliminar Sección</button>
                                <button @click="openLessonForm = !openLessonForm" class="ml-2 text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">Añadir Lección</button>
                            @endif
                        </div>
                    </div>

                    <!-- Add New Lesson Form -->
                    <div x-show="openLessonForm" class="p-4 border border-stone-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 mb-4">
                        <h4 class="text-md font-semibold text-slate-700 dark:text-white mb-2">Añadir Nueva Lección</h4>
                        <form wire:submit.prevent="saveNewLesson" class="space-y-3">
                            <input type="hidden" wire:model="newLessonSectionId" value="{{ $section['id'] }}">
                            <div>
                                <label for="newLessonTitle-{{ $section['id'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título de la Lección</label>
                                <input type="text" id="newLessonTitle-{{ $section['id'] }}" wire:model.defer="newLessonTitle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                @error('newLessonTitle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="newLessonVideoFile-{{ $section['id'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Archivo de Video (MP4, MOV, etc.)</label>
                                <input type="file" id="newLessonVideoFile-{{ $section['id'] }}" wire:model="newLessonVideoFile" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300 dark:hover:file:bg-blue-800">
                                @error('newLessonVideoFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                <div wire:loading wire:target="newLessonVideoFile" class="text-blue-500 text-xs mt-1">Cargando archivo...</div>
                            </div>
                            <div>
                                <label for="newLessonContent-{{ $section['id'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contenido (Opcional)</label>
                                <textarea id="newLessonContent-{{ $section['id'] }}" wire:model.defer="newLessonContent" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"></textarea>
                                @error('newLessonContent') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" wire:loading.attr="disabled" wire:target="newLessonVideoFile" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Guardar Lección</button>
                                <button type="button" @click="openLessonForm = false" wire:loading.attr="disabled" wire:target="newLessonVideoFile" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Cancelar</button>
                            </div>
                        </form>
                    </div>

                    <!-- Lessons List -->
                    <div wire:ignore>
                        <ul x-init="
                            new Sortable($el, {
                                animation: 150,
                                handle: '.handle-lesson',
                                onEnd: function (evt) {
                                    const newOrder = Array.from(evt.to.children)
                                        .filter(el => el.hasAttribute('data-id')) // Ensure we only get lesson items
                                        .map((item, index) => ({
                                            value: item.dataset.id,
                                            order: index + 1
                                        }));
                                    $wire.reorderLessons(newOrder, {{ $section['id'] }});
                                }
                            });
                        ">
                            <template x-for="lesson in lessons" :key="lesson.id">
                                <li :data-id="lesson.id" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg mb-2 border border-stone-200 dark:border-gray-700 overflow-hidden">
                                    <div class="flex items-center justify-between p-3">
                                        <div class="flex items-center gap-2 flex-grow">
                                            <x-ionicon-reorder-four-outline class="w-6 h-6 handle-lesson cursor-grab text-gray-500 dark:text-gray-400"/>

                                            <div x-show="alpineEditingLessonId !== lesson.id" class="flex-grow">
                                                <span class="font-medium text-slate-800 dark:text-white" x-text="`Lección ${lesson.order}: ${lesson.title}`"></span>
                                            </div>

                                            <div x-show="alpineEditingLessonId === lesson.id" class="flex-grow">
                                                <input type="text" wire:model.defer="editingLessonTitle" class="flex-grow rounded-md border-gray-300 shadow-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div x-show="alpineEditingLessonId === lesson.id">
                                                <button wire:click="updateLesson" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200">Guardar</button>
                                                <button @click.prevent="alpineEditingLessonId = null; $wire.set('editingLesson', null)" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Cancelar</button>
                                            </div>
                                            <div x-show="alpineEditingLessonId !== lesson.id">
                                                <button @click.prevent="alpineEditingLessonId = lesson.id; $wire.editLesson(lesson.id)" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">Editar</button>
                                                <button @click="$wire.deleteLesson(lesson.id)" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200">Eliminar</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="alpineEditingLessonId === lesson.id" x-transition class="p-4 border-t border-stone-200 dark:border-gray-600 bg-white dark:bg-gray-800">
                                        <h4 class="text-md font-semibold text-slate-700 dark:text-white mb-2">Editar Lección</h4>
                                        <form wire:submit.prevent="updateLesson" class="space-y-3">
                                            <div>
                                                <label :for="`editingLessonContent-${lesson.id}`" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contenido (Opcional)</label>
                                                <textarea :id="`editingLessonContent-${lesson.id}`" wire:model.defer="editingLessonContent" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"></textarea>
                                                @error('editingLessonContent') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label :for="`editingLessonVideoFile-${lesson.id}`" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reemplazar Archivo de Video</label>
                                                <div x-show="$wire.editingLessonCurrentVideoPath">
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                                        Archivo actual: <a :href="$wire.editingLessonCurrentVideoPath ? `/storage/${$wire.editingLessonCurrentVideoPath.replace('public/', '')}` : '#'" target="_blank" class="text-blue-500 hover:underline" x-text="$wire.editingLessonCurrentVideoPath ? $wire.editingLessonCurrentVideoPath.split('/').pop() : ''"></a>
                                                    </p>
                                                </div>
                                                <div x-show="!$wire.editingLessonCurrentVideoPath">
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">No hay archivo de video actual.</p>
                                                </div>
                                                <input type="file" :id="`editingLessonVideoFile-${lesson.id}`" wire:model="editingLessonVideoFile" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300 dark:hover:file:bg-blue-800">
                                                @error('editingLessonVideoFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                <div wire:loading wire:target="editingLessonVideoFile" class="text-blue-500 text-xs mt-1">Cargando archivo...</div>
                                            </div>
                                            <div class="flex justify-end">
                                                <button type="submit" wire:loading.attr="disabled" wire:target="editingLessonVideoFile" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Actualizar Lección</button>
                                                <button type="button" @click.prevent="alpineEditingLessonId = null; $wire.set('editingLesson', null)" wire:loading.attr="disabled" wire:target="editingLessonVideoFile" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            </template>
                             <template x-if="!lessons || lessons.length === 0">
                                <li>
                                    <p class="text-slate-500 dark:text-gray-400 p-3">No hay lecciones en esta sección.</p>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            @empty
                <p class="text-slate-500 dark:text-gray-400">No hay secciones en este curso.</p>
            @endforelse
        </div>

        <!-- Add New Section Form -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-2">Añadir Nueva Sección</h3>
            <form wire:submit.prevent="addSection" class="flex gap-2">
                <input type="text" wire:model.defer="newSectionTitle" placeholder="Título de la nueva sección" class="flex-grow rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-colors">Añadir Sección</button>
            </form>
            @error('newSectionTitle') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script src="https://unpkg.com/trix@1.3.1/dist/trix.js"></script>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@1.3.1/dist/trix.css">
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Trix Editor for Course Description
            const trixEditor = document.querySelector('trix-editor');
            if (trixEditor) {
                trixEditor.addEventListener('trix-change', (event) => {
                    @this.set('courseDescription', event.target.value);
                });
            }
        });
    </script>
@endpush
