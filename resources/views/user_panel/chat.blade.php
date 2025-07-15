<div x-data="chatComponent()" x-init="init($wire, {{ $activeChannel?->id ?? 'null' }})">
    <div class="flex h-[calc(100vh-theme(space.16))]">
        {{-- Left Sidebar --}}
        <aside class="w-80 flex-shrink-0 border-r border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50 flex flex-col">
            {{-- Channels Header --}}
            <div class="p-4 border-b border-gray-200 dark:border-gray-800 space-y-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Canales</h2>
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar canal..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <x-ionicon-search-outline class="w-5 h-5 text-gray-400"/>
                    </div>
                </div>
            </div>

            {{-- Channels List --}}
            <div class="flex-grow overflow-y-auto">
                {{-- User's Channels --}}
                <div class="p-2 space-y-1">
                    <h3 class="px-3 text-sm font-semibold text-gray-500 uppercase tracking-wider">Tus Canales</h3>
                    <ul class="mt-1 space-y-1">
                        @forelse($channelsForDisplay as $channel)
                            <li>
                                <a href="#" wire:click.prevent="changeChannel({{ $channel->id }})"
                                   class="flex items-center justify-between px-3 py-2.5 rounded-lg text-base font-medium transition-colors duration-150
                                          @if($activeChannel && $activeChannel->id == $channel->id)
                                              @if($channel->type === 'premium')
                                                  bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold shadow-lg
                                              @else
                                                  bg-blue-500 text-white shadow-md
                                              @endif
                                          @else
                                              text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800
                                          @endif">
                                    <div class="flex items-center">
                                        <span>{{ $channel->name }}</span>
                                        @if($channel->type === 'premium')
                                            <x-ionicon-rocket class="w-4 h-4 ml-2 {{ ($activeChannel && $activeChannel->id == $channel->id) ? 'text-white' : 'text-yellow-500' }}"/>
                                        @endif
                                    </div>
                                    {{-- Maybe show unread count later --}}
                                </a>
                            </li>
                        @empty
                             <li class="px-3 py-2 text-sm text-gray-500">
                                @if(empty($search))
                                    No estás en ningún canal.
                                @else
                                    No se encontraron canales.
                                @endif
                            </li>
                        @endforelse
                    </ul>
                </div>

                {{-- Joinable Channels --}}
                <div class="p-2 mt-4 space-y-1">
                    <h3 class="px-3 text-sm font-semibold text-gray-500 uppercase tracking-wider">Canales para unirse</h3>
                    <ul class="mt-1 space-y-1">
                        @forelse($joinableChannelsForDisplay as $channel)
                            <li>
                                <a href="#" wire:click.prevent="joinChannel({{ $channel->id }})" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors duration-150">
                                    <div class="flex items-center">
                                        <span>{{ $channel->name }}</span>
                                        @if($channel->type === 'premium')
                                            <x-ionicon-rocket class="w-4 h-4 text-yellow-500 ml-2"/>
                                        @endif
                                    </div>
                                    <x-ionicon-add-circle-outline class="w-6 h-6 text-gray-400"/>
                                </a>
                            </li>
                        @empty
                            <li class="px-3 py-2 text-sm text-gray-500">No hay más canales para unirse.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </aside>

        {{-- Main Chat Area --}}
        <main class="flex-1 flex flex-col bg-white dark:bg-gray-800 relative">
             <div wire:loading.flex wire:target="changeChannel, joinChannel, leaveChannel" class="absolute inset-0 bg-white/80 dark:bg-gray-900/80 items-center justify-center z-20 backdrop-blur-sm">
                <x-ionicon-sync class="w-16 h-16 text-blue-500 animate-spin"/>
            </div>

            @if($activeChannel)
                {{-- Chat Header --}}
                <header class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between p-4">
                    <div class="flex-grow">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">#{{ $activeChannel->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $activeChannel->description }}</p>
                    </div>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                            <x-ionicon-ellipsis-vertical class="w-6 h-6 text-gray-500 dark:text-gray-400"/>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute top-full right-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg z-10"
                             style="display: none;">
                            <a href="#" wire:click.prevent="leaveChannel({{ $activeChannel->id }})" class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <x-ionicon-log-out-outline class="w-5 h-5 mr-2"/>
                                Salir del Canal
                            </a>
                        </div>
                    </div>
                </header>

                {{-- Messages --}}
                <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-100 dark:bg-gray-900">
                    @if($hasMoreMessages)
                        <div class="text-center">
                            <button wire:click="loadMoreMessages" wire:loading.attr="disabled" class="text-sm font-medium text-blue-600 hover:underline">
                                Cargar mensajes anteriores
                            </button>
                        </div>
                    @endif

                    @forelse($chatMessages as $message)
                        {{-- Message item --}}
                        <div class="flex items-start gap-4 group @if($message->user_id == auth()->id()) flex-row-reverse @endif">
                             {{-- Avatar --}}
                            <img src="{{ $message->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($message->user->name) }}" alt="{{ $message->user->name }}" class="w-10 h-10 rounded-full shadow-md object-cover">

                            <div class="flex flex-col @if($message->user_id == auth()->id()) items-end @else items-start @endif">
                                {{-- User Name & Timestamp --}}
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ $message->user->name }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->format('g:i A') }}</span>
                                </div>

                                {{-- Reply --}}
                                @if($message->parentMessage)
                                    <div class="text-xs text-gray-600 dark:text-gray-400 bg-gray-200 dark:bg-gray-700 p-1.5 rounded-t-lg mt-1 border-l-2 border-blue-500">
                                        <p class="font-bold">{{ $message->parentMessage->user->name }}</p>
                                        <p class="pl-1">{{ Str::limit($message->parentMessage->message, 50) }}</p>
                                    </div>
                                @endif

                                {{-- Message Bubble --}}
                                <div class="relative bg-white dark:bg-gray-700 p-3 rounded-lg shadow-sm @if($message->user_id == auth()->id()) rounded-tr-none @else rounded-tl-none @endif @if($message->voiceNote) w-72 @else max-w-xl @endif">
                                    @if ($message->voiceNote)
                                        <audio controls src="{{ $message->voiceNote->url }}" class="w-full"></audio>
                                    @else
                                        <p class="text-base text-gray-800 dark:text-gray-200" style="white-space: pre-wrap;">{!! nl2br(e($message->message)) !!}</p>
                                    @endif
                                </div>

                                {{-- Reactions on message --}}
                                @if ($message->reactions && count($message->reactions) > 0)
                                    <div class="mt-2 flex gap-1">
                                        @foreach ($message->reactions as $reaction => $users)
                                            <button wire:click="toggleReaction({{ $message->id }}, '{{ $reaction }}')"
                                                    class="px-2 py-0.5 border rounded-full text-xs flex items-center gap-1 transition-colors
                                                           @if(in_array(auth()->id(), $users)) bg-blue-100 border-blue-300 dark:bg-blue-800 dark:border-blue-600 @else bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500 @endif">
                                                <span class="text-sm">{{ $reaction }}</span>
                                                <span class="text-xs font-semibold">{{ count($users) }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                             {{-- Message Actions --}}
                            <div class="relative opacity-0 group-hover:opacity-100 transition-opacity self-center">
                                <div class="flex items-center gap-1 bg-gray-200 dark:bg-gray-800 p-1 rounded-full shadow-md">
                                    <button @click="$wire.setReactingTo({{ $message->id }})" class="p-1 rounded-full hover:bg-gray-300 dark:hover:bg-gray-700">
                                        <x-ionicon-happy class="w-5 h-5 text-gray-600 dark:text-gray-400"/>
                                    </button>
                                    <button wire:click="setReplyingTo({{ $message->id }})" class="p-1 rounded-full hover:bg-gray-300 dark:hover:bg-gray-700">
                                        <x-ionicon-arrow-undo-outline class="w-5 h-5 text-gray-600 dark:text-gray-400"/>
                                    </button>
                                    @if(auth()->user()->isAdmin())
                                        <button wire:click="$dispatch('showConfirmationModal', {
                                            title: 'Eliminar Mensaje',
                                            message: '¿Estás seguro de que quieres eliminar este mensaje? Esta acción no se puede deshacer.',
                                            confirmAction: 'confirmDeleteMessage',
                                            params: { messageId: {{ $message->id }} }
                                        })" class="p-1 rounded-full hover:bg-gray-300 dark:hover:bg-gray-700">
                                            <x-ionicon-trash-outline class="w-5 h-5 text-red-500"/>
                                        </button>
                                     @endif
                                </div>
                                {{-- Reaction palette --}}
                                @if($reactingTo === $message->id)
                                    <div x-show="$wire.reactingTo === {{ $message->id }}" @click.away="$wire.setReactingTo(null)" class="absolute z-10 bottom-full mb-1 flex gap-1 bg-white dark:bg-gray-800 border dark:border-gray-600 rounded-full p-1 shadow-lg">
                                        @foreach($availableReactions as $reaction)
                                            <button wire:click="toggleReaction({{ $message->id }}, '{{ $reaction }}')" class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 text-lg">{{ $reaction }}</button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8">
                            No hay mensajes en este canal. ¡Sé el primero en saludar!
                        </div>
                    @endforelse
                </div>

                {{-- Message Input --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                    {{-- Replying to state --}}
                    @if($replyingTo)
                        <div class="relative bg-gray-100 dark:bg-gray-700 p-2 rounded-t-lg text-sm mb-2">
                            <button wire:click="cancelReply" class="absolute top-1 right-1 p-1">
                                <x-ionicon-close class="w-4 h-4 text-gray-500"/>
                            </button>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">Respondiendo a {{ $replyingTo->user->name }}</p>
                            <p class="text-gray-600 dark:text-gray-400 truncate">{{ $replyingTo->message }}</p>
                        </div>
                    @endif
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                        {{-- Replying to state --}}
                        @if($replyingTo)
                            <div class="relative bg-gray-100 dark:bg-gray-700 p-2 rounded-t-lg text-sm mb-2">
                                <button wire:click="cancelReply" class="absolute top-1 right-1 p-1">
                                    <x-ionicon-close class="w-4 h-4 text-gray-500"/>
                                </button>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">Respondiendo a {{ $replyingTo->user->name }}</p>
                                <p class="text-gray-600 dark:text-gray-400 truncate">{{ $replyingTo->message }}</p>
                            </div>
                        @endif
                        <form wire:submit.prevent="sendMessage" @submit.prevent="if($wire.messageText.trim() !== '') { $wire.sendMessage() }" class="flex items-end gap-3">
                            <div class="relative flex-grow">
                                <div wire:loading.flex wire:target="sendMessage" class="absolute inset-0 bg-white/50 dark:bg-black/50 items-center justify-center rounded-lg z-10">
                                    <x-ionicon-sync class="w-8 h-8 text-blue-500 animate-spin"/>
                                </div>
                                <textarea
                                    wire:model="messageText"
                                    id="message-input"
                                    rows="1"
                                    class="block w-full resize-none border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 rounded-lg shadow-sm scrollbar-hide p-3"
                                    placeholder="Escribe tu mensaje..."
                                    @keydown.enter.prevent="if (!$event.shiftKey && $wire.messageText.trim() !== '') { $wire.sendMessage() }"
                                    x-data="{}"
                                    x-init="
                                        $el.style.height = 'auto';
                                        $el.style.height = $el.scrollHeight + 'px';
                                        $watch('$wire.messageText', (value) => {
                                            $el.style.height = 'auto';
                                            $nextTick(() => { $el.style.height = $el.scrollHeight + 'px' });
                                        });
                                    "
                                    @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px';"
                                ></textarea>
                            </div>

                            <div class="flex-shrink-0 flex items-center gap-2">
                                <!-- Record Button Container -->
                                <div class="relative">
                                    <!-- Record Button -->
                                    <button type="button"
                                            x-show="!isUploading"
                                            @mousedown.prevent="startRecording()"
                                            @mouseup.prevent="stopRecording()"
                                            @touchstart.passive.prevent="startRecording()"
                                            @touchend.passive.prevent="stopRecording()"
                                            :class="{ 'bg-red-500 text-white animate-pulse': isRecording }"
                                            class="p-3 rounded-full bg-green-500 text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors disabled:opacity-50">
                                        <x-ionicon-mic-outline class="w-6 h-6"/>
                                    </button>

                                    <!-- Uploading Spinner -->
                                    <div x-show="isUploading" style="display: none;" class="p-3 rounded-full bg-yellow-500 text-white">
                                         <x-ionicon-sync class="w-6 h-6 animate-spin"/>
                                    </div>

                                    <!-- Recording Tooltip -->
                                    <div x-show="isRecording" x-transition style="display: none;" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded-md whitespace-nowrap">
                                        Grabando...
                                    </div>
                                </div>

                                <!-- Send Button -->
                                <button type="submit"
                                        :disabled="$wire.messageText.trim() === ''"
                                        class="p-3 rounded-full bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 transition-colors">
                                     <x-ionicon-rocket-outline class="w-6 h-6"/>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="flex-1 flex items-center justify-center text-gray-500 dark:text-gray-400">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">Selecciona un canal</h3>
                        <p class="mt-1 text-sm text-gray-500">O únete a uno para empezar a chatear.</p>
                    </div>
                </div>
            @endif
        </main>
    </div>

    @push('scripts')
    <script>
        function chatComponent() {
            return {
                wire: null,
                activeChannelId: null,
                isRecording: false,
                mediaRecorder: null,
                audioChunks: [],
                isUploading: false,

                init(wire, activeChannelId) {
                    this.wire = wire;
                    this.activeChannelId = activeChannelId;
                    this.initEcho();
                    this.setupScroll();

                    document.addEventListener('livewire:navigated', () => {
                         this.leaveEchoChannel();
                         this.initEcho();
                         this.setupScroll();
                    });

                    this.$wire.on('channel-changed', (event) => {
                        this.leaveEchoChannel();
                        this.activeChannelId = event.channelId;
                        this.initEcho();
                        this.scrollToBottom();
                    });

                    this.$wire.on('scroll-chat-to-bottom', () => {
                        this.scrollToBottom();
                    });

                    this.$wire.on('more-messages-loaded', () => {
                        const chatMessages = document.getElementById('chat-messages');
                        if(chatMessages) {
                            const oldScrollHeight = chatMessages.scrollHeight;
                            // Wait for DOM to update
                            this.$nextTick(() => {
                                chatMessages.scrollTop = chatMessages.scrollHeight - oldScrollHeight;
                            });
                        }
                    });

                },
                initEcho() {
                    if (window.Echo && this.activeChannelId) {
                        console.log(`Joining channel: chat.${this.activeChannelId}`);
                        window.Echo.private(`chat.${this.activeChannelId}`)
                            .listen('NewChatMessage', (e) => {
                                console.log('Received NewChatMessage:', e);
                                this.wire.call('handleNewMessage', e);
                            })
                            .listen('ChatMessageDeleted', (e) => {
                                console.log('Received ChatMessageDeleted:', e);
                                this.wire.call('handleMessageDeleted', e);
                            });
                    }
                },
                leaveEchoChannel() {
                     if (window.Echo && this.activeChannelId) {
                        window.Echo.leave(`chat.${this.activeChannelId}`);
                        console.log(`Left channel: chat.${this.activeChannelId}`);
                    }
                },
                setupScroll() {
                    const chatMessages = document.getElementById('chat-messages');
                    if (chatMessages) {
                        this.scrollToBottom();
                    }
                },
                scrollToBottom() {
                    const chatMessages = document.getElementById('chat-messages');
                    if (chatMessages) {
                        this.$nextTick(() => {
                           chatMessages.scrollTop = chatMessages.scrollHeight;
                        });
                    }
                },

                startRecording() {
                    if (this.isRecording) return; // Prevent multiple recordings

                    navigator.mediaDevices.getUserMedia({ audio: true })
                        .then(stream => {
                            this.isRecording = true; // Set to true when recording starts
                            this.audioChunks = [];

                            this.mediaRecorder = new MediaRecorder(stream, { mimeType: 'audio/webm' });

                            this.mediaRecorder.addEventListener("dataavailable", event => {
                                this.audioChunks.push(event.data);
                            });

                            // This event fires when mediaRecorder.stop() is called
                            this.mediaRecorder.addEventListener("stop", () => {
                                // The `isRecording` state is handled immediately in `stopRecording()`
                                this.isUploading = true;  // Now uploading

                                const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                                const audioFile = new File([audioBlob], `voice-note-${Date.now()}.webm`, { type: "audio/webm" });

                                console.log('Recording stopped, uploading...');

                                @this.upload('tempVoiceNoteFile', audioFile,
                                    (uploadedFilename) => {
                                        console.log('Upload successful. Sending message.');
                                        @this.sendVoiceMessage().finally(() => {
                                            this.isUploading = false; // Hide spinner after message sent
                                        });
                                    },
                                    (error) => {
                                        console.error('Upload failed.', error);
                                        alert('La subida de la nota de voz falló. Inténtalo de nuevo.');
                                        this.isUploading = false; // Hide spinner on error
                                    }
                                );
                            });

                            // This event fires when the stream is completely inactive
                            this.mediaRecorder.onstop = () => {
                                stream.getTracks().forEach(track => track.stop());
                            };

                            this.mediaRecorder.start();
                            console.log('Recording started...');
                        })
                        .catch(err => {
                            console.error("Error accessing microphone:", err);
                            alert('No se pudo acceder al micrófono. Asegúrate de dar permiso en tu navegador.');
                            this.isRecording = false; // Reset if microphone access failed
                        });
                },

                stopRecording() {
                    // Immediately set to false for UI feedback
                    this.isRecording = false;

                    if (!this.mediaRecorder || this.mediaRecorder.state === 'inactive') {
                        // If not recording or already inactive, do nothing
                        return;
                    }

                    try {
                        this.mediaRecorder.stop(); // This will trigger the "stop" event listener
                    } catch (e) {
                        console.error("Error stopping media recorder:", e);
                    }
                },
            }
        }
    </script>
    @endpush
</div>
