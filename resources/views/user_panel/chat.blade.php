<div x-data="{ sidebarOpen: false, rightSidebarOpen: false }">
    <section id="chat" class="section active">
        <div class="h-full flex flex-col max-w-full mx-auto sm:px-0">
            <div class="flex-1 lg:grid lg:grid-cols-[auto_1fr_auto] bg-white dark:bg-gray-900 shadow-sm min-h-[calc(100vh-10rem)] relative overflow-hidden">
                <!-- Backdrop for mobile -->
                <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                <!-- Left Sidebar with channels -->
                <div
                    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
                    class="fixed inset-y-0 left-0 w-80 bg-white dark:bg-gray-800 p-6 border-r border-stone-200 dark:border-gray-700 flex flex-col z-40 lg:relative lg:translate-x-0 transition-transform duration-300 ease-in-out"
                >
                    <div class="flex justify-between items-center lg:hidden mb-4">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Chats</h2>
                        <button @click="sidebarOpen = false">
                            <x-ionicon-close-outline class="w-6 h-6 text-slate-500 dark:text-gray-400" />
                        </button>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Chat</h2>
                        <button class="text-slate-500 dark:text-gray-400 hover:text-slate-700 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative mb-4">
                        <input type="text" placeholder="Search Contact" class="w-full pl-10 pr-4 py-2 rounded-lg bg-stone-100 dark:bg-gray-700 text-slate-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-slate-500 border border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col overflow-y-auto -mr-6 pr-6">
                        <h3 class="text-xs font-bold uppercase text-slate-500 dark:text-gray-400 mb-2">Recent Chats</h3>
                        <nav class="flex-1 space-y-1">
                            @foreach($channels as $channel)
                                <a href="#" wire:click.prevent="changeChannel({{ $channel->id }}); sidebarOpen = false"
                                   class="flex items-center space-x-3 p-3 rounded-lg transition-colors cursor-pointer {{ $activeChannel && $activeChannel->id === $channel->id ? 'bg-blue-500 text-white' : 'hover:bg-stone-100 dark:hover:bg-gray-700' }}">
                                    <div class="relative">
                                        <div class="w-12 h-12 rounded-full bg-slate-200 dark:bg-gray-600 flex items-center justify-center">
                                            <ion-icon name="{{ $channel->type === 'premium' ? 'diamond' : ($channel->icon ?? 'chatbox-ellipses-outline') }}" class="w-6 h-6 {{ $activeChannel && $activeChannel->id === $channel->id ? 'text-white' : 'text-slate-500 dark:text-gray-400' }}"></ion-icon>
                                        </div>
                                        <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-green-500 border-2 border-white dark:border-gray-800"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-center">
                                            <p class="font-semibold text-sm truncate {{ $activeChannel && $activeChannel->id === $channel->id ? 'text-white' : 'text-slate-900 dark:text-white' }}">{{ $channel->name }}</p>
                                            <span class="text-xs {{ $activeChannel && $activeChannel->id === $channel->id ? 'text-blue-200' : 'text-slate-400 dark:text-gray-500' }}"></span>
                                        </div>
                                        <p class="text-sm truncate {{ $activeChannel && $activeChannel->id === $channel->id ? 'text-blue-100' : 'text-slate-500 dark:text-gray-400' }}">{{ $channel->description ?? 'Channel' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </nav>

                        <hr class="my-4 border-stone-200 dark:border-gray-600">

                        <h3 class="text-xs font-bold uppercase text-slate-500 dark:text-gray-400 mb-2">Joinable Channels</h3>
                        <nav class="flex-1 space-y-1">
                             @foreach($joinableChannels as $channel)
                                <a href="#"
                                   wire:click.prevent="joinChannel({{ $channel->id }}); sidebarOpen = false"
                                   wire:loading.attr="disabled"
                                   wire:loading.class="opacity-75 cursor-wait"
                                   wire:target="joinChannel({{ $channel->id }})"
                                   class="flex items-center space-x-3 p-3 rounded-lg transition-colors cursor-pointer hover:bg-stone-100 dark:hover:bg-gray-700">
                                    <div class="w-12 h-12 rounded-full bg-slate-200 dark:bg-gray-600 flex items-center justify-center">
                                        <div wire:loading.remove wire:target="joinChannel({{ $channel->id }})">
                                            <ion-icon name="{{ $channel->type === 'premium' ? 'diamond' : 'add-circle-outline' }}" class="w-6 h-6 text-slate-500 dark:text-gray-400"></ion-icon>
                                        </div>
                                        <div wire:loading wire:target="joinChannel({{ $channel->id }})">
                                            <svg class="animate-spin h-6 w-6 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-sm truncate {{ $channel->type === 'premium' ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white' }}">{{ $channel->name }}</p>
                                        <p class="text-sm truncate text-slate-500 dark:text-gray-400">{{ $channel->description ?? 'Join this channel' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </div>

                <!-- Main chat area -->
                <div class="flex flex-col bg-stone-50 dark:bg-gray-800/50">
                    @if($activeChannel)
                        <!-- Chat header -->
                        <div class="p-4 border-b border-stone-200 dark:border-gray-700 flex justify-between items-center bg-white dark:bg-gray-800">
                            <div class="flex items-center space-x-3">
                                <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 dark:text-gray-400">
                                    <x-ionicon-menu-outline class="w-6 h-6" />
                                </button>
                                <div class="relative">
                                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-gray-600 flex items-center justify-center">
                                       <ion-icon name="{{ $activeChannel->type === 'premium' ? 'diamond' : ($activeChannel->icon ?? 'chatbox-ellipses-outline') }}" class="w-5 h-5 text-slate-500 dark:text-gray-400"></ion-icon>
                                    </div>
                                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 border-2 border-white dark:border-gray-800"></span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 dark:text-white"># {{ $activeChannel->name }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-gray-400">{{ $activeChannel->description }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Messages area -->
                        <div id="chat-container" class="flex-3 p-6 space-y-6 overflow-y-auto max-h-[calc(100vh-20rem)]">
                           @if($hasMoreMessages)
                               <div class="text-center">
                                   <button
                                       wire:click="loadMoreMessages"
                                       @click="scrollTopBeforeLoad = document.getElementById('chat-container').scrollTop"
                                       wire:loading.attr="disabled"
                                       class="text-sm font-semibold text-slate-600 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200 transition-colors">
                                       <span wire:loading.remove wire:target="loadMoreMessages">Cargar más mensajes</span>
                                       <span wire:loading wire:target="loadMoreMessages">Cargando...</span>
                                   </button>
                               </div>
                           @endif
                           @forelse($chatMessages as $message)
                                <div class="flex items-start gap-3 {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                    <img src="{{ $message->user->profile?->avatar_url }}" class="w-10 h-10 rounded-full" alt="{{ $message->user->name }}">
                                    <div class="flex flex-col gap-1 w-full max-w-md">
                                        <div class="flex items-center gap-2 {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                            <p class="font-semibold text-sm text-slate-900 dark:text-white">
                                                {{ $message->user->name }}
                                            </p>
                                            <span class="text-xs text-slate-400 dark:text-gray-500" title="{{ $message->created_at ? $message->created_at->format('Y-m-d g:i a') : 'Unknown time' }}">
                                                {{ $message->human_readable_created_at ?? 'Just now' }}
                                            </span>
                                        </div>
                                        @if($message->parentMessage)
                                            <div class="text-xs text-slate-500 dark:text-gray-400 border-l-2 border-slate-300 dark:border-gray-500 pl-2 ml-2 mb-1 {{ $message->user_id === auth()->id() ? 'text-right border-l-0 border-r-2' : '' }}">
                                                Replying to <strong>{{ $message->parentMessage->user->name }}</strong>:
                                                <em>{{ Str::limit($message->parentMessage->message, 40) }}</em>
                                            </div>
                                        @endif
                                        <div class="p-3 rounded-lg shadow-sm {{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white rounded-br-none' : 'bg-white dark:bg-gray-700 rounded-bl-none border border-stone-200 dark:border-gray-600' }}">
                                            <p>{{ $message->message }}</p>
                                        </div>
                                         <div class="mt-1 flex items-center gap-2 {{ $message->user_id === auth()->id() ? 'justify-end' : '' }}">
                                            <button wire:click="setReplyingTo({{ $message->id }})" class="text-xs text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200">Reply</button>
                                            <button wire:click="setReactingTo({{ $message->id }})" class="text-xs text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200">React</button>
                                            @if(auth()->user()->isAdmin())
                                                <button
                                                    wire:click.prevent="$dispatch('showConfirmationModal', {
                                                        title: 'Eliminar Mensaje',
                                                        message: '¿Estás seguro de que quieres eliminar este mensaje? Esta acción no se puede deshacer.',
                                                        confirmAction: 'confirmDeleteMessage',
                                                        params: { messageId: {{ $message->id }} }
                                                    })"
                                                    class="text-xs text-red-500 hover:text-red-700">
                                                    Delete
                                                </button>
                                            @endif
                                        </div>

                                        @if($reactingTo === $message->id)
                                            <div class="mt-2 flex space-x-2">
                                                @foreach($availableReactions as $reaction)
                                                    <button wire:click="toggleReaction({{ $message->id }}, '{{ $reaction }}')" class="p-1 rounded-full hover:bg-stone-200 dark:hover:bg-gray-600">{{ $reaction }}</button>
                                                @endforeach
                                                <button wire:click="setReactingTo(null)" class="text-red-500 hover:text-red-700 font-bold">[x]</button>
                                            </div>
                                        @endif

                                        @if($message->reactions)
                                            <div class="mt-2 flex space-x-2 {{ $message->user_id === auth()->id() ? 'justify-end' : '' }}">
                                                @foreach($message->reactions as $reaction => $userIds)
                                                    @if(count($userIds) > 0)
                                                        <div class="bg-stone-200 dark:bg-gray-600 rounded-full px-2 py-1 text-xs flex items-center">
                                                            <span>{{ $reaction }}</span>
                                                            <span class="ml-1 font-bold">{{ count($userIds) }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-slate-500 dark:text-gray-400 py-8">
                                    <p>No messages in this channel yet.</p>
                                    <p>Be the first to say something!</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Message input -->
                        <div class="p-4 bg-white dark:bg-gray-800 border-t border-stone-200 dark:border-gray-700">
                            @if($replyingTo)
                                <div class="mb-2 p-2 bg-stone-200 dark:bg-gray-600 rounded-lg text-sm text-slate-700 dark:text-gray-300">
                                    Replying to <strong>{{ $replyingTo->user->name }}</strong>: <em>{{ Str::limit($replyingTo->message, 50) }}</em>
                                    <button wire:click="cancelReply" class="ml-2 text-red-500 hover:text-red-700 font-bold">[x]</button>
                                </div>
                            @endif
                            <form wire:submit.prevent="sendMessage">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-1">
                                        <div class="relative">
                                            <input type="text" wire:model="messageText" placeholder="Type a message" class="w-full bg-stone-100 dark:bg-gray-700 border-transparent rounded-lg pl-4 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                                        </div>
                                    </div>
                                    <button type="submit" class="p-2 bg-blue-500 text-white rounded-full hover:bg-blue-600">
                                        <x-ionicon-paper-plane-outline class="w-5 h-5" />
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="flex-1 flex items-center justify-center text-slate-500 dark:text-gray-400">
                            <div class="text-center">
                                <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 dark:text-gray-400 mb-4">
                                    <x-ionicon-menu-outline class="w-8 h-8 mx-auto" />
                                    <span class="text-sm">View Channels</span>
                                </button>
                                <p class="text-lg">Select a channel to start chatting.</p>
                            </div>
                        </div>
                    @endif
                </div>
                 <!-- Right Sidebar for Media/Files -->
                <div x-show="rightSidebarOpen" @click.away="rightSidebarOpen = false"
                     x-transition:enter="transition ease-in-out duration-300"
                     x-transition:enter-start="translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="translate-x-full"
                     class="fixed inset-y-0 right-0 w-80 bg-white dark:bg-gray-800 border-l border-stone-200 dark:border-gray-700 p-6 flex-col z-30 transform lg:relative lg:inset-auto lg:transform-none"
                     style="display: none;"
                >
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Media</h3>
                        <button @click="rightSidebarOpen = false" class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200">
                            <x-ionicon-close-outline class="w-6 h-6"/>
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto -mr-6 pr-6">
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-semibold text-slate-800 dark:text-gray-200">Media (36)</h4>
                                <a href="#" class="text-sm text-blue-500 font-semibold">Show All</a>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <!-- Placeholder Images -->
                                <img src="https://via.placeholder.com/150" class="rounded-lg aspect-square object-cover">
                                <img src="https://via.placeholder.com/150" class="rounded-lg aspect-square object-cover">
                                <img src="https://via.placeholder.com/150" class="rounded-lg aspect-square object-cover">
                                <img src="https://via.placeholder.com/150" class="rounded-lg aspect-square object-cover">
                                <img src="https://via.placeholder.com/150" class="rounded-lg aspect-square object-cover">
                                <img src="https://via.placeholder.com/150" class="rounded-lg aspect-square object-cover">
                            </div>
                        </div>
                        <div>
                             <h4 class="font-semibold text-slate-800 dark:text-gray-200 mb-2">Files (12)</h4>
                            <ul class="space-y-3">
                                <!-- Placeholder Files -->
                                <li class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/50 flex items-center justify-center">
                                        <x-ionicon-document-text-outline class="w-6 h-6 text-red-500"/>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-sm text-slate-800 dark:text-gray-200">service-task.pdf</p>
                                        <p class="text-xs text-slate-500 dark:text-gray-400">2MB, 2 Dec 2024</p>
                                    </div>
                                </li>
                                <li class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/50 flex items-center justify-center">
                                        <x-ionicon-image-outline class="w-6 h-6 text-green-500"/>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-sm text-slate-800 dark:text-gray-200">homepage-design.fig</p>
                                        <p class="text-xs text-slate-500 dark:text-gray-400">3MB, 2 Dec 2024</p>
                                    </div>
                                </li>
                                <li class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                        <x-ionicon-code-slash-outline class="w-6 h-6 text-blue-500"/>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-sm text-slate-800 dark:text-gray-200">about-us.html</p>
                                        <p class="text-xs text-slate-500 dark:text-gray-400">1MB, 2 Dec 2024</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('livewire:navigated', function() {
    const chatContainer = document.getElementById('chat-container');
    let previousScrollHeight = 0;
    let scrollTopBeforeLoad = 0;

    // If we are not on the chat page, do nothing.
    if (!chatContainer) {
        return;
    }

    function scrollToBottom() {
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    }

    // Scroll to bottom on initial load or navigation
    scrollToBottom();

    // Listen for events from Livewire
    if (typeof Livewire !== 'undefined') {
        // Unified event to scroll chat to the bottom
        Livewire.on('scroll-chat-to-bottom', () => {
            // Using Alpine.nextTick ensures we wait for Livewire's DOM updates to complete
            if (typeof Alpine !== 'undefined') {
                Alpine.nextTick(() => scrollToBottom());
            } else {
                // Fallback for safety, in case Alpine isn't initialized
                setTimeout(scrollToBottom, 150);
            }
        });

        Livewire.on('more-messages-loaded', () => {
            if (typeof Alpine !== 'undefined') {
                Alpine.nextTick(() => {
                    const newScrollHeight = chatContainer.scrollHeight;
                    const scrollDifference = newScrollHeight - previousScrollHeight;

                    if (scrollDifference > 0) {
                        chatContainer.scrollTop = scrollTopBeforeLoad + scrollDifference;
                    }

                    previousScrollHeight = newScrollHeight;
                });
            }
        });
    }

    let currentChannelId = @json($activeChannel?->id);
    let currentEchoChannel = null;

    // Function to subscribe to a channel
    function subscribeToChannel(channelId) {
        if (!channelId) {
            console.log('❌ No channel ID provided');
            return;
        }

        // Leave current channel if exists
        if (currentEchoChannel) {
            console.log(`📤 Leaving channel: chat.${currentChannelId}`);
            try {
                window.Echo.leave(`chat.${currentChannelId}`);
            } catch (error) {
                console.error('❌ Error leaving channel:', error);
            }
        }

        // Subscribe to new channel
        currentChannelId = channelId;
        console.log(`📥 Subscribing to channel: chat.${channelId}`);

        try {
            currentEchoChannel = window.Echo.private(`chat.${channelId}`)
                .listen('NewChatMessage', (event) => {
                    console.log('📨 New message received:', event.message?.message);

                    // Call Livewire method to handle the message
                    try {
                        @this.call('handleNewMessage', event);
                        console.log('✅ Message processed successfully');
                    } catch (error) {
                        console.error('❌ Error processing message:', error);
                    }
                })
                .listen('ChatMessageDeleted', (event) => {
                    console.log('🗑️ Message deleted event received:', event);

                    // Call Livewire method to handle the deleted message
                    try {
                        @this.call('handleMessageDeleted', event);
                        console.log('✅ Deleted message processed successfully');
                    } catch (error) {
                        console.error('❌ Error processing deleted message:', error);
                    }
                })
                .error((error) => {
                    console.error('❌ Echo channel error:', error);
                });

            console.log(`✅ Successfully subscribed to channel: chat.${channelId}`);
        } catch (error) {
            console.error('❌ Failed to subscribe to channel:', error);
        }
    }

    // Subscribe to initial channel
    if (currentChannelId) {
        subscribeToChannel(currentChannelId);
    }

    // Listen for channel changes from Livewire to update Echo subscription
    if (typeof Livewire !== 'undefined') {
        Livewire.on('channel-changed', (event) => {
            console.log('🔄 Channel changed to:', event.channelId);
            subscribeToChannel(event.channelId);
            previousScrollHeight = chatContainer.scrollHeight; // Reset on channel change
            scrollTopBeforeLoad = 0;
        });
    }

    // This function will be called when we navigate away from the page
    const cleanup = () => {
        if (currentEchoChannel && currentChannelId) {
            console.log(`📤 Leaving channel on navigation: chat.${currentChannelId}`);
            try {
                window.Echo.leave(`chat.${currentChannelId}`);
            } catch (error) {
                console.error('❌ Error leaving channel on navigation:', error);
            }
        }
        // Important: remove the listener to avoid memory leaks
        document.removeEventListener('livewire:navigating', cleanup);
    };

    // Add the cleanup listener for when we navigate away
    document.addEventListener('livewire:navigating', cleanup);
});
</script>
