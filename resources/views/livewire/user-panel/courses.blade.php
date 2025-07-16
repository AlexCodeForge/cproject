<div>
    <section id="courses" class="section active">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-8">Cursos Disponibles</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($courses as $course)
                <div class="course-card {{ $course->is_premium ? 'relative bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 dark:from-amber-900/30 dark:via-amber-800/40 dark:to-orange-900/30 rounded-xl border-2 border-amber-300/40 dark:border-amber-700 shadow-lg overflow-hidden flex flex-col cursor-pointer hover:shadow-xl hover:border-amber-400/60 dark:hover:border-amber-600 transition-all duration-300' : 'bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-hidden flex flex-col cursor-pointer hover:shadow-lg transition-shadow' }}">
                    @if ($course->is_premium)
                        <!-- Decorative background elements -->
                        <div class="absolute -top-20 -right-20 w-40 h-40 bg-amber-300/20 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-orange-300/20 rounded-full blur-2xl"></div>
                    @endif

                    <div class="aspect-video {{ $course->is_premium ? 'bg-amber-200/50 dark:bg-amber-800/30 relative z-10' : 'bg-stone-200' }}">
                        <img src="{{ $course->image_url ?? 'https://placehold.co/600x400/e2e8f0/94a3b8?text=Course' }}" class="w-full h-full object-cover {{ $course->is_premium ? 'blur-sm' : '' }}" alt="Course Image">
                        @if ($course->is_premium && !Auth::user()->isPremium())
                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                <a href="{{ route('pricing') }}" class="bg-amber-400 hover:bg-amber-300 dark:bg-amber-500 dark:hover:bg-amber-400 text-amber-900 dark:text-amber-100 font-bold py-2 px-4 text-sm rounded-lg flex items-center gap-2 transform-gpu transition-all duration-200 hover:scale-105 shadow-lg">
                                    <ion-icon name="diamond" class="text-lg"></ion-icon>
                                    <span>Acceso Premium</span>
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 flex-1 flex flex-col {{ $course->is_premium ? 'relative z-10' : '' }}">
                        @if ($course->is_premium)
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold uppercase text-amber-700 dark:text-amber-400 bg-amber-200/50 dark:bg-amber-800/30 px-2 py-1 rounded-full">Premium</span>
                                <ion-icon name="diamond" class="text-amber-600 dark:text-amber-400 text-sm"></ion-icon>
                            </div>
                        @endif
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">{{ $course->title }}</h3>
                        <p class="text-slate-600 dark:text-gray-400 text-sm mb-4 flex-1 leading-relaxed">{{ $course->description }}</p>
                        @if ($course->is_premium && !Auth::user()->isPremium())
                            <a href="{{ route('pricing') }}" class="mt-4 bg-amber-400 hover:bg-amber-300 dark:bg-amber-500 dark:hover:bg-amber-400 text-amber-900 dark:text-amber-100 font-bold py-2 px-4 rounded-lg text-center transition-all duration-200 hover:scale-105 shadow-md flex items-center justify-center gap-2">
                                <ion-icon name="diamond" class="text-sm"></ion-icon>
                                <span>Acceder al Curso</span>
                            </a>
                        @else
                            <a href="{{ route('user.courses.show', $course->slug) }}" class="mt-4 bg-slate-700 text-white font-semibold py-2 px-4 rounded-lg text-center hover:bg-slate-800 transition-colors">
                                Empezar Curso
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-slate-500 dark:text-gray-400">No hay cursos disponibles en este momento.</p>
            @endforelse
        </div>
    </section>
</div>
