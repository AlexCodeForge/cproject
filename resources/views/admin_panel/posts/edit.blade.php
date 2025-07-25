<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Editar Publicación</h1>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form wire:submit="update" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div x-data="{ activeTab: 'content' }" class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6 space-y-6">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <a href="#" x-on:click.prevent="activeTab = 'content'"
                           :class="activeTab === 'content' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                           class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Contenido
                        </a>
                        <a href="#" x-on:click.prevent="activeTab = 'seo'"
                           :class="activeTab === 'seo' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                           class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            SEO & Publicación
                        </a>
                        <a href="#" x-on:click.prevent="activeTab = 'config'"
                           :class="activeTab === 'config' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                           class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Configuraciones
                        </a>
                    </nav>
                </div>

                <!-- Tab Content: Content -->
                <div x-show="activeTab === 'content'" class="space-y-6">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Contenido de la Publicación</h2>
                    <!-- Title -->
                    <div>
                        <x-input-label for="title" value="Título" />
                        <x-text-input wire:model="title" id="title" class="block mt-1 w-full" type="text" name="title" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <x-input-label for="excerpt" value="Extracto" />
                        <textarea wire:model="excerpt" id="excerpt" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" rows="3"></textarea>
                        <x-input-error :messages="$errors->get('excerpt')" class="mt-2" />
                    </div>

                    <!-- Content -->
                    <div>
                        <x-input-label for="content" value="Contenido" />
                        <div wire:ignore
                             class="mt-1"
                             x-data="{ content: @entangle('content') }"
                             x-init="
                                if (!document.querySelector('#summernote-edit').classList.contains('summernote-initialized')) {
                                    $('#summernote-edit').summernote({
                                        placeholder: 'Escribe tu contenido aquí...',
                                        tabsize: 2,
                                        height: 300,
                                        contentsCss: '{{ asset('css/app.css') }}', // Add this line
                                        toolbar: [
                                            ['style', ['style']],
                                            ['font', ['bold', 'underline', 'clear']],
                                            ['color', ['color']],
                                            ['para', ['ul', 'ol', 'paragraph']],
                                            ['table', ['table']],
                                            ['insert', ['link', 'picture', 'video']],
                                            ['view', ['codeview']]
                                        ],
                                        callbacks: {
                                            onChange: function(contents, $editable) {
                                                @this.set('content', contents);
                                            },
                                            onImageUpload: function(files) {
                                                let file = files[0];
                                                let reader = new FileReader();
                                                reader.onloadend = function() {
                                                    let formData = new FormData();
                                                    formData.append('file', file);
                                                    formData.append('_token', '{{ csrf_token() }}');
                                                    fetch('{{ route('admin.summernote.upload') }}', {
                                                        method: 'POST',
                                                        body: formData,
                                                    })
                                                    .then(response => response.json())
                                                    .then(result => {
                                                        $('#summernote-edit').summernote('insertImage', result.url);
                                                    })
                                                    .catch(error => {
                                                        console.error('Summernote upload error:', error);
                                                    });
                                                }
                                                reader.readAsDataURL(file);
                                            }
                                        }
                                    });
                                    document.querySelector('#summernote-edit').classList.add('summernote-initialized');
                                }
                                // Load existing content into Summernote
                                $('#summernote-edit').summernote('code', content);

                                // Use a MutationObserver to aggressively remove any Summernote modal backdrops
                                const observer = new MutationObserver((mutationsList, observer) => {
                                    for (const mutation of mutationsList) {
                                        if (mutation.type === 'childList') {
                                            mutation.addedNodes.forEach(node => {
                                                if (node.nodeType === 1 && node.classList.contains('note-modal-backdrop')) {
                                                    node.remove();
                                                }
                                            });
                                        }
                                    }
                                });

                                observer.observe(document.body, { childList: true, subtree: true });

                                // Disconnect the observer when the component is removed
                                $el.closest('[wire:id]').addEventListener('livewire:navigating', () => {
                                    observer.disconnect();
                                });
                             "
                             x-on:livewire:navigating.window="if(window.jQuery && $('#summernote-edit').summernote){ $('#summernote-edit').summernote('destroy'); }">
                            <textarea id="summernote-edit" wire:model.defer="content" class="hidden"></textarea>
                        </div>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>
                </div>

                <!-- Tab Content: SEO & Publishing -->
                <div x-show="activeTab === 'seo'" class="space-y-6">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Información de SEO y Publicación</h2>
                    <!-- Meta Title -->
                    <div>
                        <x-input-label for="meta_title" value="Meta Título" />
                        <x-text-input wire:model="meta_title" id="meta_title" class="block mt-1 w-full" type="text" name="meta_title" />
                        <x-input-error :messages="$errors->get('meta_title')" class="mt-2" />
                    </div>

                    <!-- Meta Description -->
                    <div class="mt-4">
                        <x-input-label for="meta_description" value="Meta Descripción" />
                        <textarea wire:model="meta_description" id="meta_description" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" rows="3"></textarea>
                        <x-input-error :messages="$errors->get('meta_description')" class="mt-2" />
                    </div>

                    <!-- Published At -->
                    <div class="mt-4">
                        <x-input-label for="published_at" value="Publicado El" />
                        <x-text-input wire:model="published_at" id="published_at" class="block mt-1 w-full" type="date" name="published_at" />
                        <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                    </div>

                    <!-- Reading Time -->
                    <div class="mt-4">
                        <x-input-label for="reading_time" value="Tiempo de Lectura (minutos)" />
                        <x-text-input wire:model="reading_time" id="reading_time" class="block mt-1 w-full" type="number" name="reading_time" min="1" />
                        <x-input-error :messages="$errors->get('reading_time')" class="mt-2" />
                    </div>

                    <!-- Difficulty Level -->
                    <div class="mt-4">
                        <x-input-label for="difficulty_level" value="Nivel de Dificultad (1.0 - 5.0)" />
                        <x-text-input wire:model="difficulty_level" id="difficulty_level" class="block mt-1 w-full" type="number" name="difficulty_level" step="0.1" min="1.0" max="5.0" />
                        <x-input-error :messages="$errors->get('difficulty_level')" class="mt-2" />
                    </div>
                </div>

                <!-- Tab Content: Configuraciones -->
                <div x-show="activeTab === 'config'" class="space-y-6">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Configuraciones de la Publicación</h2>
                    <!-- Publication Status -->
                    <div>
                        <x-input-label for="status" value="Estado" />
                        <select wire:model="status" id="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            <option value="draft">Borrador</option>
                            <option value="published">Publicado</option>
                            <option value="archived">Archivado</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <!-- Visibility & Features -->
                    <div class="mt-4">
                        <h3 class="font-semibold text-slate-600 dark:text-gray-300 mb-2">Visibilidad y Características</h3>
                        <div class="flex items-center space-x-6">
                            <label for="is_premium" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" wire:model="is_premium" id="is_premium" class="sr-only">
                                    <div class="block w-14 h-8 rounded-full"
                                         x-bind:class="{'bg-indigo-600': $wire.is_premium, 'bg-gray-600': !$wire.is_premium}"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"
                                         x-bind:class="{'translate-x-full': $wire.is_premium}"></div>
                                </div>
                                <div class="ml-3 text-gray-700 dark:text-gray-300 font-medium">
                                    Contenido Premium
                                </div>
                                <x-input-error :messages="$errors->get('is_premium')" class="mt-2" />
                            </label>
                            <label for="is_featured" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" wire:model="is_featured" id="is_featured" class="sr-only">
                                    <div class="block w-14 h-8 rounded-full"
                                         x-bind:class="{'bg-indigo-600': $wire.is_featured, 'bg-gray-600': !$wire.is_featured}"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"
                                         x-bind:class="{'translate-x-full': $wire.is_featured}"></div>
                                </div>
                                <div class="ml-3 text-gray-700 dark:text-gray-300 font-medium">
                                    Publicación Destacada
                                </div>
                                <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                            </label>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="mt-4">
                        <x-input-label for="tags" value="Etiquetas (separadas por comas)" />
                        <x-text-input wire:model="tags" id="tags" class="block mt-1 w-full" type="text" name="tags" placeholder="etiqueta1, etiqueta2, etiqueta3" />
                        <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar Widgets -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Actions Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6 space-y-4">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Acciones</h2>
                <div class="flex flex-col space-y-4">
                    <x-secondary-button type="button" wire:click="saveAsDraft" class="w-full py-3 !px-6 !font-bold">
                        Guardar como borrador
                    </x-secondary-button>
                    <x-primary-button type="button" wire:click="publishPost" class="w-full py-3 !px-6 !font-bold">
                        Publicar
                    </x-primary-button>
                </div>
            </div>

            <!-- Featured Image Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6 space-y-6">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Imagen Destacada</h2>
                <div>
                    <x-input-label for="featured_image" value="Subir Nueva Imagen" />
                    <input type="file" wire:model="featured_image" id="featured_image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <x-input-error :messages="$errors->get('featured_image')" class="mt-2" />

                    @if ($featured_image)
                        <p class="text-sm text-gray-500 mt-2">Vista previa nueva:</p>
                        <img src="{{ $featured_image->temporaryUrl() }}" class="mt-2 h-32 w-32 object-cover rounded-md">
                    @elseif ($existing_featured_image)
                        <p class="text-sm text-gray-500 mt-2">Imagen actual:</p>
                        <img src="{{ asset('storage/' . $existing_featured_image) }}" class="mt-2 h-32 w-32 object-cover rounded-md">
                    @endif
                </div>
            </div>

            <!-- Categories Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6 space-y-6">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Categorías</h2>
                <div>
                    <x-input-label for="category_id" value="Categoría" />
                    <select wire:model="category_id" id="category_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                        <option value="">Seleccionar Categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>
            </div>
        </div>
    </form>
</div>
