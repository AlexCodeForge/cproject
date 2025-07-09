<div>
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Manage Posts</h1>
            <div class="flex items-center space-x-4">
                <div>
                    <button wire:click="$set('view', 'list')" class="{{ $view === 'list' ? 'bg-slate-700 text-white' : 'bg-white text-slate-700' }} px-3 py-2 rounded-md text-sm font-medium">List</button>
                    <button wire:click="$set('view', 'grid')" class="{{ $view === 'grid' ? 'bg-slate-700 text-white' : 'bg-white text-slate-700' }} px-3 py-2 rounded-md text-sm font-medium">Grid</button>
                </div>
                <div class="w-64">
                    <select wire:model.live="selectedCategory" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route('admin.posts.create') }}" class="bg-slate-700 dark:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-slate-800 dark:hover:bg-gray-600">New Post</a>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        @if ($view === 'list')
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-stone-50 dark:bg-gray-700/50 text-xs text-slate-600 dark:text-gray-400 uppercase">
                        <tr>
                            <th class="p-4">Title</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">Author</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Date</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr class="border-b border-stone-200 dark:border-gray-700 hover:bg-stone-50 dark:hover:bg-gray-700/50">
                                <td class="p-4 font-semibold text-slate-900 dark:text-white">
                                    <a href="{{ route('posts.show', $post) }}" class="hover:underline">
                                        {{ $post->title }}
                                    </a>
                                    @if ($post->is_premium)
                                        <span class="ml-2 bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 text-xs font-semibold px-2 py-1 rounded-full">Premium</span>
                                    @endif
                                </td>
                                <td class="p-4 text-slate-900 dark:text-gray-300">{{ $post->category->name ?? 'N/A' }}</td>
                                <td class="p-4 text-slate-900 dark:text-gray-300">{{ $post->user->name }}</td>
                                <td class="p-4 text-slate-900 dark:text-gray-300">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $post->status }}
                                    </span>
                                </td>
                                <td class="p-4 text-slate-900 dark:text-gray-300">{{ $post->created_at->format('d M, Y') }}</td>
                                <td class="p-4 space-x-4">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white">Edit</a>
                                    <button wire:click="delete({{ $post->id }})" wire:confirm="Are you sure you want to delete this post?" class="text-red-500 hover:text-red-700 dark:hover:text-red-400">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($posts as $post)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
                        <a href="{{ route('posts.show', $post) }}" class="hover:underline">
                            <h3 class="font-bold text-lg">{{ $post->title }}</h3>
                        </a>
                        <p class="text-sm text-gray-500">{{ $post->category->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">by {{ $post->user->name }}</p>
                        <div class="mt-4 flex justify-end space-x-2">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white">Edit</a>
                            <button wire:click="delete({{ $post->id }})" wire:confirm="Are you sure you want to delete this post?" class="text-red-500 hover:text-red-700 dark:hover:text-red-400">Delete</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>
