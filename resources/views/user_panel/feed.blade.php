<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Post;

new #[Layout('layouts.app')] class extends Component
{
    public $posts;

    public function mount()
    {
        $this->posts = Post::published()->with('category')->latest()->get();
    }
}; ?>

<div>
    <section id="feed" class="section active" x-data="{
        viewMode: 'list', // 'list' or 'grid'
        activeCategory: 'all', // Stores the currently active category filter
        toggleView(mode) {
            this.viewMode = mode;
        }
    }">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Feed de Noticias</h1>
            <div class="flex items-center gap-2">
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
        <div id="feed-filters" class="flex items-center gap-2 mb-8 overflow-x-auto pb-2">
             <button @click="activeCategory = 'all'"
                     :class="{'bg-slate-700 text-white': activeCategory === 'all', 'bg-white dark:bg-gray-700 text-slate-600 dark:text-gray-300 border border-stone-200 dark:border-gray-600': activeCategory !== 'all'}"
                     class="feed-filter-btn px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap">Todos</button>
             <button @click="activeCategory = 'premium'"
                     :class="{
                         'bg-gradient-to-r from-amber-400 to-orange-400 text-amber-900 border-2 border-amber-300 shadow-md hover:shadow-lg transition-all duration-200': activeCategory === 'premium',
                         'bg-white dark:bg-gray-700 text-slate-600 dark:text-gray-300 border border-stone-200 dark:border-gray-600': activeCategory !== 'premium'
                     }"
                     class="feed-filter-btn px-4 py-2 rounded-full text-sm whitespace-nowrap flex items-center gap-1">
                 <x-ionicon-rocket-outline class="w-4 h-4" />
                 Premium
             </button>
             @foreach ($posts->unique('category.name')->filter(fn($p) => $p->category) as $postCategory)
                @php
                    $isPremiumCategory = in_array($postCategory->category->name, ['Premium', 'Análisis Premium']);
                @endphp
                <button @click="activeCategory = '{{ $postCategory->category->name }}'"
                        :class="{
                            'bg-slate-700 text-white': activeCategory === '{{ $postCategory->category->name }}' && !{{ $isPremiumCategory ? 'true' : 'false' }},
                            'bg-white dark:bg-gray-700 text-slate-600 dark:text-gray-300 border border-stone-200 dark:border-gray-600': activeCategory !== '{{ $postCategory->category->name }}' && !{{ $isPremiumCategory ? 'true' : 'false' }},
                            'bg-gradient-to-r from-amber-400 to-orange-400 text-amber-900 border-2 border-amber-300 shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-1': {{ $isPremiumCategory ? 'true' : 'false' }},
                            'font-bold': activeCategory === '{{ $postCategory->category->name }}' && {{ $isPremiumCategory ? 'true' : 'false' }}
                        }"
                        class="feed-filter-btn px-4 py-2 rounded-full text-sm whitespace-nowrap">
                    <template x-if="{{ $isPremiumCategory ? 'true' : 'false' }}">
                        <x-ionicon-rocket-outline class="w-4 h-4" />
                    </template>
                    {{ $postCategory->category->name }}
                </button>
             @endforeach
        </div>

        <div id="feed-container"
             :class="{
                'space-y-8': viewMode === 'list',
                'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8': viewMode === 'grid'
             }">
            @foreach ($posts as $post)
                <a href="{{ route('posts.show', $post->slug) }}"
                   wire:navigate
                   class="block rounded-xl transition-all duration-300"
                   data-category="{{ $post->category?->name }}"
                   x-show="activeCategory === 'all' || activeCategory === '{{ $post->category?->name }}' || (activeCategory === 'premium' && {{ $post->is_premium ? 'true' : 'false' }})"
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="opacity-0 transform scale-95"
                   x-transition:enter-end="opacity-100 transform scale-100"
                   x-transition:leave="transition ease-in duration-200"
                   x-transition:leave-start="opacity-100 transform scale-100"
                   x-transition:leave-end="opacity-0 transform scale-95">
                    <article class="feed-item flex rounded-xl shadow-sm hover:shadow-lg h-full cursor-pointer overflow-hidden"
                             :class="{
                                'flex-col sm:flex-row': viewMode === 'list',
                                'flex-col sm:flex-col': viewMode === 'grid',
                                'bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 dark:hover:border-gray-600': !{{ $post->is_premium ? 'true' : 'false' }},
                                'bg-stone-50 dark:bg-gray-700/50 border-2 border-amber-300/40 dark:border-amber-700/50 dark:hover:border-amber-700/50': {{ $post->is_premium ? 'true' : 'false' }}
                             }">
                        <div class="relative z-0" :class="viewMode === 'list' ? 'sm:w-1/3 feed-image' : 'w-full h-48'">
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
                        <div class="p-6 flex flex-col justify-between" :class="viewMode === 'list' ? 'sm:w-2/3' : 'w-full'">
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
                                <h3 class="text-xl font-bold my-2 text-slate-900 dark:text-white group-hover:text-amber-600">{{ $post->title }}</h3>
                                <p class="text-slate-600 text-sm dark:text-gray-300 leading-relaxed">{{ $post->excerpt }}</p>
                            </div>
                            <div class="text-xs text-slate-400 dark:text-gray-500 mt-4">
                                <span>{{ $post->published_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </article>
                </a>
            @endforeach
        </div>
    </section>
</div>
