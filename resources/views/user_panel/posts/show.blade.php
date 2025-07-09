<div>
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">{{ $post->title }}</h1>
        <div class="mt-4">
            @if ($post->is_premium && !auth()->user()?->hasRole('premium'))
                <div class="p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg">
                    <p>This is premium content. Please <a href="{{ route('pricing') }}" class="font-bold underline">subscribe</a> to view.</p>
                </div>
            @else
                <div>{!! $post->content !!}</div>
            @endif
        </div>
    </div>
</div>
