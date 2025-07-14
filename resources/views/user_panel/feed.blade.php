<div>
    <section id="feed" class="section active" x-data="{
        viewMode: 'list', // 'list' or 'grid'
        searchOpen: false,
        toggleView(mode) {
            this.viewMode = mode;
        }
    }">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Feed de Noticias</h1>
            <div class="flex items-center gap-4">
                 <div x-show="searchOpen"
                      x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                      x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-2"
                      @focusout="if (!event.currentTarget.contains(event.relatedTarget)) { searchOpen = false }"
                      class="relative" x-cloak>
                    <input x-ref="searchInput" type="text" wire:model.live.debounce.300ms="searchTerm" placeholder="Buscar..." class="w-64 bg-stone-100 dark:bg-gray-700 border-transparent rounded-lg focus:ring-slate-500 focus:border-slate-500 pl-10 pr-10">
                    <x-ionicon-search-outline class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                    <button type="button" x-show="$wire.searchTerm" x-transition @click="$wire.set('searchTerm', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-gray-300" aria-label="Clear search" x-cloak>
                        <x-ionicon-close-circle-outline class="w-5 h-5" />
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="searchOpen = !searchOpen; if (searchOpen) { $nextTick(() => $refs.searchInput.focus()) }" class="p-2 rounded-lg text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-700">
                        <x-ionicon-search-outline class="w-6 h-6"/>
                    </button>
                    <div class="w-px h-6 bg-slate-200 dark:bg-gray-600"></div>
                    <button id="feed-list-view"
                            @click="toggleView('list')"
                            :class="viewMode === 'list' ? 'bg-stone-200 dark:bg-gray-700 text-slate-700 dark:text-white' : 'text-slate-500 dark:text-gray-400'"
                            class="p-2 rounded-lg"><x-ionicon-list-outline class="w-6 h-6" /></button>
                    <button id="feed-grid-view"
                            @click="toggleView('grid')"
                            :class="viewMode === 'grid' ? 'bg-stone-200 dark:bg-gray-700 text-slate-700 dark:text-white' : 'text-slate-500 dark:text-gray-400'"
                            class="p-2 rounded-lg"><x-ionicon-grid-outline class="w-6 h-6" /></button>
                </div>
            </div>
        </div>
        <div id="feed-filters" class="flex items-center gap-2 mb-8 overflow-x-auto pb-2">
             <button wire:click="filterByCategory('all')"
                     :class="{'bg-slate-700 text-white': @js($activeCategory) === 'all', 'bg-white dark:bg-gray-700 text-slate-600 dark:text-gray-300 border border-stone-200 dark:border-gray-600': @js($activeCategory) !== 'all'}"
                     class="feed-filter-btn px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap">Todos</button>
             <button wire:click="filterByCategory('premium')"
                     :class="{
                         'bg-gradient-to-r from-amber-400 to-orange-400 text-amber-900 border-2 border-amber-300 shadow-md hover:shadow-lg transition-all duration-200': @js($activeCategory) === 'premium',
                         'bg-white dark:bg-gray-700 text-slate-600 dark:text-gray-300 border border-stone-200 dark:border-gray-600': @js($activeCategory) !== 'premium'
                     }"
                     class="feed-filter-btn px-4 py-2 rounded-full text-sm whitespace-nowrap flex items-center gap-1">
                 <x-ionicon-rocket-outline class="w-4 h-4" />
                 Premium
             </button>
             @foreach ($categories as $category)
                @php
                    $isPremiumCategory = in_array($category->name, ['Premium', 'Análisis Premium']);
                @endphp
                <button wire:click="filterByCategory('{{ $category->name }}')"
                        :class="{
                            'bg-slate-700 text-white': @js($activeCategory) === '{{ $category->name }}' && !{{ $isPremiumCategory ? 'true' : 'false' }},
                            'bg-white dark:bg-gray-700 text-slate-600 dark:text-gray-300 border border-stone-200 dark:border-gray-600': @js($activeCategory) !== '{{ $category->name }}' && !{{ $isPremiumCategory ? 'true' : 'false' }},
                            'bg-gradient-to-r from-amber-400 to-orange-400 text-amber-900 border-2 border-amber-300 shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-1': {{ $isPremiumCategory ? 'true' : 'false' }},
                            'font-bold': @js($activeCategory) === '{{ $category->name }}' && {{ $isPremiumCategory ? 'true' : 'false' }}
                        }"
                        class="feed-filter-btn px-4 py-2 rounded-full text-sm whitespace-nowrap">
                    <template x-if="{{ $isPremiumCategory ? 'true' : 'false' }}">
                        <x-ionicon-rocket-outline class="w-4 h-4" />
                    </template>
                    {{ $category->name }}
                </button>
             @endforeach
        </div>

        <div id="feed-container"
             :class="{
                'space-y-8': viewMode === 'list',
                'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8': viewMode === 'grid'
             }">
            @forelse ($posts as $post)
                <a href="{{ route('posts.show', $post->slug) }}"
                   wire:navigate
                   class="block rounded-xl transition-all duration-300"
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="opacity-0 transform scale-95"
                   x-transition:enter-end="opacity-100 transform scale-100"
                   x-transition:leave="transition ease-in duration-200"
                   x-transition:leave-start="opacity-100 transform scale-100"
                   x-transition:leave-end="opacity-0 transform scale-95">
                    <article class="feed-item flex rounded-xl shadow-sm hover:shadow-lg cursor-pointer overflow-hidden"
                             :class="{
                                'flex-col sm:flex-row h-72': viewMode === 'list',
                                'flex-col h-112': viewMode === 'grid',
                                'bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 dark:hover:border-gray-600': !{{ $post->is_premium ? 'true' : 'false' }},
                                'bg-stone-50 dark:bg-gray-700/50 border-2 border-amber-300/40 dark:border-amber-700/50 dark:hover:border-amber-700/50': {{ $post->is_premium ? 'true' : 'false' }}
                             }">
                        <div class="relative z-0 flex-shrink-0" :class="viewMode === 'list' ? 'w-64 h-full feed-image' : 'w-full h-48'">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover"
                                 onerror="this.onerror=null;this.src='https://placehold.co/600x400/cccccc/888888?text=Image';">
                            @if ($post->is_premium && !(auth()->check() && auth()->user()->subscribed('default')))
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                    <div class="bg-amber-400 text-amber-900 font-bold py-2 px-4 text-sm rounded-full flex items-center gap-2 shadow-lg">
                                        <x-ionicon-rocket-outline class="w-5 h-5" />
                                        <span>Premium</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="p-6 flex flex-col justify-between" :class="viewMode === 'list' ? 'flex-grow h-full' : 'w-full flex-grow h-64'">
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-xs font-bold uppercase"
                                          :class="{
                                              'text-amber-700 dark:text-amber-400 bg-amber-200/50 dark:bg-amber-800/30 px-2 py-1 rounded-full': {{ $post->is_premium ? 'true' : 'false' }},
                                              'text-slate-500 dark:text-gray-400': !{{ $post->is_premium ? 'true' : 'false' }}
                                          }">{{ $post->category?->name }}</span>
                                    @if ($post->is_premium)
                                        <x-ionicon-rocket-outline class="w-4 h-4 text-amber-600 dark:text-amber-400" />
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold my-2 text-slate-900 dark:text-white group-hover:text-amber-600 line-clamp-2">{{ $post->title }}</h3>
                                <p class="text-slate-600 text-sm dark:text-gray-300 leading-relaxed line-clamp-3">{{ $post->excerpt }}</p>
                            </div>
                            <div class="text-xs text-slate-400 dark:text-gray-500 mt-4">
                                <span>{{ $post->published_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </article>
                </a>
            @empty
                <div class="col-span-full text-center py-16">
                    <x-ionicon-sad-outline class="w-16 h-16 mx-auto text-gray-400"/>
                    <h3 class="text-xl font-semibold text-slate-700 dark:text-gray-300 mt-4">No se encontraron resultados</h3>
                    <p class="text-slate-500 dark:text-gray-400 mt-2">Intenta ajustar tu búsqueda o filtros.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
