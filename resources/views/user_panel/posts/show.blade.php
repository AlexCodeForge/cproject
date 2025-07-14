@section('title', $post->title)

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('feed') }}" wire:navigate class="inline-flex items-center text-sm font-semibold text-slate-600 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white mb-6">
        <x-ionicon-arrow-back-outline class="w-5 h-5 mr-2" />
        Volver al Feed
    </a>

    <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        @if($post->featured_image)
            <div class="relative">
                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-64 sm:h-80 md:h-96 object-cover">
                @if ($post->is_premium)
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent"></div>
                @endif
            </div>
        @endif

        <div class="p-6 sm:p-8 lg:p-10 relative">
            @if(auth()->check() && auth()->user()->hasRole('admin'))
            <div class="absolute top-4 right-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center justify-center p-2 rounded-full text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 transition duration-150 ease-in-out">
                            <x-ionicon-ellipsis-vertical class="h-6 w-6" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('admin.posts.edit', $post)">
                            {{ __('Editar Post') }}
                        </x-dropdown-link>

                        <x-dropdown-link href="#" wire:click.prevent="toggleFeatured">
                            {{ $post->is_featured ? __('Marcar como no Destacado') : __('Marcar como Destacado') }}
                        </x-dropdown-link>

                        <x-dropdown-link href="#" wire:click.prevent="toggleArchived">
                             {{ $post->status === 'archived' ? __('Desarchivar Post') : __('Archivar Post') }}
                        </x-dropdown-link>

                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

                        <x-dropdown-link href="#" wire:click.prevent="confirmDelete" class="text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 dark:text-red-500">
                            {{ __('Eliminar Post') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
            @endif

            <header class="mb-6">
                @if($post->category)
                    <a href="#" class="inline-block text-sm font-bold uppercase tracking-wider mb-2"
                       style="color: {{ $post->is_premium ? 'var(--premium-color, #c09a3e)' : 'var(--secondary-color, #64748b)' }};">
                        {{ $post->category->name }}
                    </a>
                @endif

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 dark:text-white leading-tight mb-4">
                    {{ $post->title }}
                </h1>

                <div class="flex flex-wrap items-center text-sm text-slate-500 dark:text-gray-400 gap-x-4 gap-y-2">
                    <div class="flex items-center">
                        <x-ionicon-person-circle-outline class="w-5 h-5 mr-1.5" />
                        <span>{{ $post->user->name }}</span>
                    </div>
                    <div class="flex items-center">
                        <x-ionicon-calendar-outline class="w-5 h-5 mr-1.5" />
                        <time datetime="{{ $post->published_at->toIso8601String() }}">
                            {{ $post->published_at->format('d M, Y') }}
                        </time>
                    </div>
                    @if($post->reading_time)
                        <div class="flex items-center">
                            <x-ionicon-time-outline class="w-5 h-5 mr-1.5" />
                            <span>{{ $post->reading_time }} min de lectura</span>
                        </div>
                    @endif
                    @if($post->difficulty_level)
                        <div class="flex items-center">
                            <x-ionicon-cellular-outline class="w-5 h-5 mr-1.5" />
                            <span>Dificultad: {{ $post->difficulty_level }}/5</span>
                        </div>
                    @endif
                </div>
            </header>

            <div class="prose dark:prose-invert prose-lg max-w-none text-slate-700 dark:text-gray-300 leading-relaxed">
                @if ($post->is_premium && !(auth()->check() && auth()->user()->subscribed('default')))
                    <div class="relative">
                        <div class="max-h-60 overflow-hidden blur-sm select-none">
                            {!! $post->content !!}
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-gray-800 via-white/80 dark:via-gray-800/80 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center p-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl max-w-md mx-auto">
                                <x-ionicon-rocket-outline class="w-12 h-12 mx-auto text-amber-500" />
                                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mt-4">Contenido Premium</h3>
                                <p class="text-slate-600 dark:text-gray-300 mt-2 mb-6">Este análisis es exclusivo para miembros premium. Desbloquea este y todos los demás beneficios.</p>
                                <a href="{{ route('pricing') }}" wire:navigate class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-full transition-transform transform hover:scale-105 shadow-lg">
                                    Hazte Premium
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    {!! $post->content !!}
                @endif
            </div>

            @if(!empty($post->tags))
                <footer class="mt-8 pt-6 border-t border-stone-200 dark:border-gray-700 min-h-min">
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <span class="inline-block bg-stone-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300 text-xs font-semibold px-3 py-1.5 rounded-full">#{{ $tag }}</span>
                        @endforeach
                    </div>
                </footer>
            @endif

        </div>
    </article>

    @if($relatedPosts->count() > 0)
        <section class="mt-12">
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">Publicaciones Relacionadas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($relatedPosts as $relatedPost)
                    <a href="{{ route('posts.show', $relatedPost->slug) }}" wire:navigate class="block bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        <img src="{{ $relatedPost->featured_image_url }}" alt="{{ $relatedPost->title }}" class="h-48 w-full object-cover">
                        <div class="p-5">
                            @if($relatedPost->category)
                                <span class="text-xs font-bold uppercase text-slate-500 dark:text-gray-400">{{ $relatedPost->category->name }}</span>
                            @endif
                            <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $relatedPost->title }}</h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-gray-300">{{ Str::limit($relatedPost->excerpt, 100) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
