<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Post;

new #[Layout('layouts.app')] class extends Component
{
    public $posts;

    public function mount()
    {
        $this->posts = Post::published()->with('category')->get();
    }
}; ?>

<div>
    <section id="feed" class="section active" x-data="{
        viewMode: 'list', // 'list' or 'grid'
        activeCategory: 'all', // Stores the currently active category filter
        filterPosts() {
            // No direct filtering needed here as x-show handles it.
            // This method can be used for any Livewire updates if needed later.
        },
        toggleView(mode) {
            this.viewMode = mode;
        }
    }">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Feed de Noticias</h1>
            <div class="flex items-center gap-2">
                <button id="feed-list-view"
                        @click="toggleView('list')"
                        :class="viewMode === 'list' ? 'bg-stone-200 text-slate-700' : 'text-slate-500'"
                        class="p-2 rounded-lg"><x-ionicon-list-outline class="w-6 h-6" /></button>
                <button id="feed-grid-view"
                        @click="toggleView('grid')"
                        :class="viewMode === 'grid' ? 'bg-stone-200 text-slate-700' : 'text-slate-500'"
                        class="p-2 rounded-lg"><x-ionicon-grid-outline class="w-6 h-6" /></button>
            </div>
        </div>
        <div id="feed-filters" class="flex items-center gap-2 mb-8 overflow-x-auto pb-2">
             <button @click="activeCategory = 'all'"
                     :class="{'bg-slate-700 text-white': activeCategory === 'all', 'bg-white text-slate-600 border border-stone-200': activeCategory !== 'all'}"
                     class="feed-filter-btn px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap">Todos</button>
             @foreach ($posts->unique('category.name') as $postCategory)
                @if($postCategory->category) {{-- Ensure category exists --}}
                    @php
                        $isPremiumCategory = in_array($postCategory->category->name, ['Premium', 'Análisis Premium']);
                    @endphp
                    <button @click="activeCategory = '{{ $postCategory->category->name }}'"
                            :class="{
                                'bg-slate-700 text-white': activeCategory === '{{ $postCategory->category->name }}' && !{{ $isPremiumCategory ? 'true' : 'false' }},
                                'bg-white text-slate-600 border border-stone-200': activeCategory !== '{{ $postCategory->category->name }}' && !{{ $isPremiumCategory ? 'true' : 'false' }},
                                'bg-gradient-to-r from-amber-400 to-orange-400 text-amber-900 border-2 border-amber-300 shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-1': {{ $isPremiumCategory ? 'true' : 'false' }},
                                'font-bold': activeCategory === '{{ $postCategory->category->name }}' && {{ $isPremiumCategory ? 'true' : 'false' }}
                            }"
                            class="feed-filter-btn px-4 py-2 rounded-full text-sm whitespace-nowrap">
                        <template x-if="{{ $isPremiumCategory ? 'true' : 'false' }}">
                            <x-ionicon-diamond class="w-4 h-4" />
                        </template>
                        {{ $postCategory->category->name }}
             </button>
                @endif
             @endforeach
        </div>

        <div id="feed-container" class="space-y-8"
             :class="{
                'space-y-8': viewMode === 'list',
                'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8': viewMode === 'grid'
             }">
            @foreach ($posts as $post)
                <article class="feed-item flex p-6 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-slate-400 dark:hover:border-gray-600 transition-all overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                         data-category="{{ $post->category->name }}"
                         x-show="activeCategory === 'all' || activeCategory === '{{ $post->category->name }}'"
                         :class="{
                            'flex-col sm:flex-row': viewMode === 'list',
                            'flex-col': viewMode === 'grid',
                            'bg-white dark:bg-gray-800': !{{ $post->is_premium }},
                            'bg-stone-50 dark:bg-gray-700 border-2 border-amber-300/40 dark:border-amber-700': {{ $post->is_premium }}
                         }">
                    <div class="relative z-10" :class="viewMode === 'list' ? 'sm:w-1/3 feed-image' : 'w-full h-48 mb-4'">
                        <img src="{{ $post->featured_image_url }}" class="w-full h-full object-cover"
                             :class="{ 'blur-sm rounded-lg': {{ $post->is_premium }} }"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x400/e2e8f0/94a3b8?text=Image';">
                        @if ($post->is_premium)
                            <div class="absolute inset-0 bg-black/30 rounded-lg flex items-center justify-center">
                                <button class="bg-amber-400 hover:bg-amber-300 text-amber-900 font-bold py-2 px-4 text-sm rounded-full flex items-center gap-2 transform-gpu transition-all duration-200 hover:scale-105 shadow-lg">
                                    <x-ionicon-diamond class="w-5 h-5" />
                                    <span>Leer Análisis Premium</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 relative z-10" :class="viewMode === 'list' ? 'sm:w-2/3' : 'w-full'">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs font-bold uppercase"
                                  :class="{
                                      'text-amber-700 dark:text-amber-400 bg-amber-200/50 dark:bg-amber-800/30 px-2 py-1 rounded-full': {{ $post->is_premium }},
                                      'text-slate-500': !{{ $post->is_premium }}
                                  }">{{ $post->category->name }}</span>
                            @if ($post->is_premium)
                                <x-ionicon-diamond class="w-4 h-4 text-amber-600 dark:text-amber-400" />
                            @endif
                        </div>
                        <h3 class="text-xl font-bold my-2 text-slate-900 dark:text-white">{{ $post->title }}</h3>
                        <p class="text-slate-600 text-sm"
                           :class="{
                               'dark:text-gray-300 leading-relaxed': {{ $post->is_premium }}
                           }">{{ $post->excerpt }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
