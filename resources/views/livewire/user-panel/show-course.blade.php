<div>
    <section id="course-detail" class="section active py-12">
        <div class="container mx-auto px-4">
            @if (session()->has('message'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-8">{{ $course->title }}</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content Area (Video Player & Lesson Content) -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-hidden mb-6">
                        @if ($currentLesson)
                            <div class="aspect-video bg-black flex items-center justify-center">
                                <video controls class="w-full h-full object-contain" src="{{ $this->lessonVideoUrl }}" key="{{ $currentLesson->id }}">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <div class="p-6">
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">{{ $currentLesson->title }}</h2>
                                <div class="prose dark:prose-invert text-slate-600 dark:text-gray-300 mt-4">
                                    {!! $currentLesson->content !!}
                                </div>
                                <div class="mt-6 flex justify-end">
                                    @if (Auth::check() && !isset($progress[$currentLesson->id]))
                                        <button wire:click="markLessonAsComplete({{ $currentLesson->id }})" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                                            <x-ionicon-checkmark-circle-outline class="w-5 h-5"/>
                                            Marcar como Completada
                                        </button>
                                    @else
                                        <span class="text-green-600 dark:text-green-400 font-semibold flex items-center gap-2">
                                            <x-ionicon-checkmark-circle class="w-5 h-5"/>
                                            Completada
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="p-6 text-center text-slate-500 dark:text-gray-400">
                                <p class="mb-4">Selecciona una lección para empezar.</p>
                                @if ($course->is_premium && !$hasPremiumAccess)
                                    <p>Este es un curso premium. <a href="{{ route('premium') }}" class="text-blue-600 hover:underline">Obtén acceso premium aquí.</a></p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar (Course Sections & Lessons) -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-white mb-4">Contenido del Curso</h3>
                        <ul class="space-y-4">
                            @forelse ($sections as $section)
                                <li class="bg-stone-50 dark:bg-gray-700 rounded-lg p-4 border border-stone-200 dark:border-gray-600">
                                    <h4 class="text-lg font-semibold text-slate-800 dark:text-white mb-2">{{ $section['title'] }}</h4>
                                    <ul class="space-y-2 ml-4">
                                        @forelse ($section['lessons'] as $lesson)
                                            <li class="flex items-center justify-between py-1">
                                                <button wire:click="selectLesson({{ $lesson['id'] }})" class="text-left flex-grow text-slate-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 {{ $currentLesson && $currentLesson->id === $lesson['id'] ? 'font-semibold text-blue-600 dark:text-blue-400' : '' }}">
                                                    Lección {{ $lesson['order'] }}: {{ $lesson['title'] }}
                                                </button>
                                                @if (isset($progress[$lesson['id']]))
                                                    <x-ionicon-checkmark-circle class="w-5 h-5 text-green-500 dark:text-green-400"/>
                                                @elseif ($course->is_premium && !Auth::user()->isPremium())
                                                    <x-ionicon-lock-closed class="w-5 h-5 text-amber-500 dark:text-amber-400"/>
                                                @endif
                                            </li>
                                        @empty
                                            <li class="text-slate-500 dark:text-gray-400">No hay lecciones en esta sección.</li>
                                        @endforelse
                                    </ul>
                                </li>
                            @empty
                                <li class="text-slate-500 dark:text-gray-400">No hay secciones en este curso.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
