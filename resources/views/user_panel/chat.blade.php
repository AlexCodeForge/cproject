<div x-data="{ sidebarOpen: false }">
    <section id="chat" class="section active">
        <div class="h-full flex flex-col max-w-7xl mx-auto">
            <div class="flex-1 flex bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm min-h-[calc(100vh-10rem)] relative">
                <!-- Backdrop for mobile -->
                <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-10 sm:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                <!-- Sidebar with channels -->
                <div
                    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
                    class="fixed inset-y-0 left-0 w-64 bg-stone-50/95 dark:bg-gray-700/95 backdrop-blur-sm p-4 border-r border-stone-200 dark:border-gray-600 flex-col flex z-20 sm:relative sm:translate-x-0 sm:flex sm:bg-stone-50/50 dark:sm:bg-gray-700/50 sm:backdrop-blur-none transition-transform duration-300 ease-in-out"
                >
                    <div class="flex justify-between items-center sm:hidden mb-4">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Channels</h2>
                        <button @click="sidebarOpen = false">
                            <x-ionicon-close-outline class="w-6 h-6 text-slate-500 dark:text-gray-400" />
                        </button>
                    </div>

                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 hidden sm:block">Canales</h2>
                    <nav class="space-y-2 h-48 overflow-y-auto scrollbar-hide">
                        @foreach($channels as $channel)
                            <a href="#" wire:click.prevent="changeChannel({{ $channel->id }}); sidebarOpen = false"
                               @class([
                                   'flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors',
                                   'bg-slate-700 dark:bg-slate-600' => $activeChannel && $activeChannel->id === $channel->id,
                                   'text-amber-500 dark:text-amber-400 font-semibold' => $channel->type === 'premium',
                                   'text-white font-semibold' => $activeChannel && $activeChannel->id === $channel->id && $channel->type !== 'premium',
                                   'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-600 hover:text-slate-800 dark:hover:text-gray-200' => !($activeChannel && $activeChannel->id === $channel->id) && $channel->type !== 'premium',
                                   'hover:bg-stone-200 dark:hover:bg-gray-600 hover:text-amber-700 dark:hover:text-amber-300' => !($activeChannel && $activeChannel->id === $channel->id) && $channel->type === 'premium'
                               ])
                            >
                                <ion-icon name="{{ $channel->type === 'premium' ? 'diamond' : ($channel->icon ?? 'chatbox-ellipses-outline') }}" class="w-6 h-6"></ion-icon>
                                <span># {{ $channel->name }}</span>
                            </a>
                        @endforeach
                    </nav>

                    <hr class="my-4 border-stone-200 dark:border-gray-600">

                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Joinable Channels</h2>
                    <nav class="space-y-2 h-48 overflow-y-auto scrollbar-hide">
                        @foreach($joinableChannels as $channel)
                            <a href="#"
                               wire:click.prevent="joinChannel({{ $channel->id }}); sidebarOpen = false"
                               wire:loading.attr="disabled"
                               wire:loading.class="opacity-75 cursor-wait"
                               wire:target="joinChannel({{ $channel->id }})"
                               @class([
                                   'flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors',
                                   'font-semibold text-amber-600 dark:text-amber-400 hover:bg-stone-200 dark:hover:bg-gray-600 hover:text-amber-700 dark:hover:text-amber-300' => $channel->type === 'premium',
                                   'text-slate-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-gray-600 hover:text-slate-800 dark:hover:text-gray-200' => $channel->type !== 'premium',
                               ])
                            >
                                <div wire:loading.remove wire:target="joinChannel({{ $channel->id }})">
                                    <ion-icon name="{{ $channel->type === 'premium' ? 'diamond' : 'add-circle-outline' }}" class="w-6 h-6"></ion-icon>
                                </div>
                                <div wire:loading wire:target="joinChannel({{ $channel->id }})">
                                    <svg class="animate-spin h-6 w-6 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                <span># {{ $channel->name }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                <!-- Main chat area -->
                <div class="flex-1 flex flex-col">
                    @if($activeChannel)
                        <!-- Chat header -->
                        <div class="p-4 border-b border-stone-200 dark:border-gray-600 flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <button @click="sidebarOpen = true" class="sm:hidden text-slate-500 dark:text-gray-400">
                                    <x-ionicon-menu-outline class="w-6 h-6" />
                                </button>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white"># {{ $activeChannel->name }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-gray-400">{{ $activeChannel->description }}</p>
                                </div>
                            </div>
                            <div>
                                <button wire:click="leaveChannel({{ $activeChannel->id }})" class="text-red-500 hover:text-red-700">Leave Channel</button>
                            </div>
                        </div>

                        <!-- Messages area -->
                        <div id="chat-container" class="flex-1 p-6 space-y-6 overflow-y-auto bg-stone-50/30 dark:bg-gray-800/30  max-h-[calc(100vh-20rem)]">
                           @forelse($chatMessages as $message)
                                <div class="flex items-start space-x-4 {{ $message->user_id === auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                    <img src="{{ $message->user->profile?->avatar_url }}" class="w-10 h-10 rounded-full" alt="{{ $message->user->name }}">
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-white {{ $message->user_id === auth()->id() ? 'text-right' : '' }}">
                                            {{ $message->user->name }}
                                            <span class="text-xs text-slate-500 dark:text-gray-400 ml-2" title="{{ $message->created_at ? $message->created_at->format('Y-m-d g:i a') : 'Unknown time' }}">
                                                {{ $message->human_readable_created_at ?? 'Just now' }}
                                            </span>
                                        </p>
                                        @if($message->parentMessage)
                                            <div class="text-xs text-slate-500 dark:text-gray-400 border-l-2 border-slate-300 dark:border-gray-500 pl-2 ml-2 mb-1">
                                                Replying to <strong>{{ $message->parentMessage->user->name }}</strong>:
                                                <em>{{ Str::limit($message->parentMessage->message, 40) }}</em>
                                            </div>
                                        @endif
                                        <div class="mt-1 {{ $message->user_id === auth()->id() ? 'bg-slate-700 dark:bg-slate-600 text-white rounded-tr-none' : 'bg-white dark:bg-gray-700 border border-stone-200 dark:border-gray-600 rounded-tl-none' }} p-3 rounded-lg shadow-sm">
                                            <p class="{{ $message->user_id === auth()->id() ? 'text-white' : 'text-slate-800 dark:text-gray-200' }}">{{ $message->message }}</p>
                                        </div>
                                        <div class="mt-1 flex items-center {{ $message->user_id === auth()->id() ? 'justify-end' : '' }}">
                                            <button wire:click="setReplyingTo({{ $message->id }})" class="text-xs text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200">Reply</button>
                                            <button wire:click="setReactingTo({{ $message->id }})" class="ml-2 text-xs text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200">React</button>
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
                                            <div class="mt-2 flex space-x-2">
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
                        <div class="p-4 border-t border-stone-200 dark:border-gray-600">
                            @if($replyingTo)
                                <div class="mb-2 p-2 bg-stone-200 dark:bg-gray-600 rounded-lg text-sm text-slate-700 dark:text-gray-300">
                                    Replying to <strong>{{ $replyingTo->user->name }}</strong>: <em>{{ Str::limit($replyingTo->message, 50) }}</em>
                                    <button wire:click="cancelReply" class="ml-2 text-red-500 hover:text-red-700 font-bold">[x]</button>
                                </div>
                            @endif
                            <form wire:submit.prevent="sendMessage">
                                <div class="bg-stone-100 dark:bg-gray-700 flex items-center rounded-lg px-4 py-2 border border-stone-300 dark:border-gray-600 focus-within:border-slate-500 dark:focus-within:border-slate-400 transition-colors">
                                    <input type="text" wire:model="messageText" placeholder="Escribe un mensaje..." class="bg-transparent w-full focus:outline-none focus:ring-0 text-slate-800 dark:text-gray-200 placeholder-slate-500 dark:placeholder-gray-400 border-none">
                                    <button type="submit" class="text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200 transition-colors">
                                        <x-ionicon-paper-plane-outline class="w-6 h-6 rotate-45" />
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="flex-1 flex items-center justify-center text-slate-500 dark:text-gray-400">
                            <div class="text-center">
                                <button @click="sidebarOpen = true" class="sm:hidden text-slate-500 dark:text-gray-400 mb-4">
                                    <x-ionicon-menu-outline class="w-8 h-8 mx-auto" />
                                    <span class="text-sm">View Channels</span>
                                </button>
                                <p>Select a channel to start chatting.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('livewire:navigated', function() {
    const chatContainer = document.getElementById('chat-container');

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
        Livewire.on('new-message-added', () => {
            // A little delay to allow DOM to update before scrolling
            setTimeout(scrollToBottom, 100);
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

    // Listen for channel changes from Livewire
    if (typeof Livewire !== 'undefined') {
        Livewire.on('channel-changed', (event) => {
            console.log('🔄 Channel changed to:', event.channelId);
            subscribeToChannel(event.channelId);
            setTimeout(scrollToBottom, 200); // Scroll down after loading new messages
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
