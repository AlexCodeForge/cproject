<?php

namespace App\Livewire\UserPanel;

use App\Events\NewChatMessage;
use App\Events\ChatMessageDeleted;
use App\Models\ChatChannel;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\VoiceNote;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Chat extends Component
{
    use WithFileUploads;

    public ?ChatChannel $activeChannel = null;
    public Collection $channels;
    public Collection $joinableChannels;
    public Collection $chatMessages;
    public string $messageText = '';
    public ?ChatMessage $replyingTo = null;
    public ?int $reactingTo = null;
    public array $availableReactions = ['👍', '❤️', '😂', '😮', '😢', '😡'];
    public int $messagesPerPage = 50;
    public int $messagesLoadedCount = 0;
    public bool $hasMoreMessages = false;
    public string $search = '';
    public $tempVoiceNoteFile;

    public function mount(): void
    {
        // Initialize all Collections as empty first to prevent null state
        $this->channels = collect();
        $this->joinableChannels = collect();
        $this->chatMessages = collect();

        $this->loadChannels();

        if ($this->channels->isNotEmpty()) {
            $this->changeChannel($this->channels->first()->id);
        }

        // Dispatch a scroll-to-bottom event after the component is mounted and channels are loaded
        $this->dispatch('scroll-chat-to-bottom');
    }

    public function loadChannels(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->channels = $user->chatChannels()
            ->where('is_active', true)
            ->orderByDesc('last_message_at')
            ->get() ?? collect();

        $this->joinableChannels = ChatChannel::where('is_active', true)
            ->whereIn('type', ['public', 'premium'])
            ->whereNotIn('id', $this->channels->pluck('id') ?? [])
            ->orderBy('sort_order')
            ->get() ?? collect();
    }

    public function changeChannel(int $channelId): void
    {
        $this->activeChannel = ChatChannel::findOrFail($channelId);
        $this->loadInitialMessages();
        $this->reactingTo = null;
        $this->replyingTo = null;

        // Dispatch event to JavaScript to update Echo listeners
        $this->dispatch('channel-changed', channelId: $channelId);
    }

    public function loadInitialMessages(): void
    {
        if (!$this->activeChannel) {
            $this->chatMessages = collect();
            $this->messagesLoadedCount = 0;
            $this->hasMoreMessages = false;
            return;
        }

        $query = $this->activeChannel->messages()->with(['user.profile', 'parentMessage.user']);
        $totalMessages = $query->count();

        $this->chatMessages = $query->latest()
            ->take($this->messagesPerPage)
            ->get()
            ->reverse()
            ->values();

        $this->messagesLoadedCount = $this->chatMessages->count();
        $this->hasMoreMessages = $this->messagesLoadedCount < $totalMessages;

        $this->dispatch('scroll-chat-to-bottom');
    }

    public function loadMoreMessages(): void
    {
        if (!$this->activeChannel || !$this->hasMoreMessages) {
            return;
        }

        $query = $this->activeChannel->messages()->with(['user.profile', 'parentMessage.user']);
        $totalMessages = $query->count();

        $newMessages = $query->latest()
            ->skip($this->messagesLoadedCount)
            ->take($this->messagesPerPage)
            ->get()
            ->reverse();

        $this->chatMessages = $newMessages->concat($this->chatMessages)->values();
        $this->messagesLoadedCount = $this->chatMessages->count();
        $this->hasMoreMessages = $this->messagesLoadedCount < $totalMessages;

        $this->dispatch('more-messages-loaded');
    }

    public function sendMessage(): void
    {
        // Ensure messages collection is properly initialized
        if (!($this->chatMessages instanceof Collection)) {
            $this->chatMessages = collect();
        }

        $this->validate(['messageText' => 'required|string']);

        if (!$this->activeChannel) {
            return;
        }

        $message = ChatMessage::create([
            'chat_channel_id' => $this->activeChannel->id,
            'user_id' => Auth::id(),
            'message' => $this->messageText,
            'parent_message_id' => $this->replyingTo?->id,
        ]);

        $message->load(['user.profile']);

        // Log broadcast attempt
        Log::info('💬 Broadcasting new message', [
            'message_id' => $message->id,
            'channel_id' => $this->activeChannel->id,
            'user_id' => Auth::id(),
        ]);

        try {
            broadcast(new NewChatMessage($message));
            Log::info('✅ Message broadcasted successfully');
        } catch (\Exception $e) {
            Log::error('❌ Message broadcast failed', [
                'error' => $e->getMessage(),
                'message_id' => $message->id,
            ]);
        }

        // Update the last message timestamp for the channel
        if ($this->activeChannel) {
            $this->activeChannel->update(['last_message_at' => now()]);
        }

        // Add to local collection to show immediately
        $this->chatMessages->push($message);

        $this->reset(['messageText', 'replyingTo']);
        $this->dispatch('scroll-chat-to-bottom');
    }

    public function sendVoiceMessage()
    {
        $this->validate([
            'tempVoiceNoteFile' => 'required|file|mimes:mp3,wav,ogg,webm|max:10240', // 10MB Max
        ]);

        if (!$this->activeChannel) {
            return;
        }

        // 1. Store the file
        $path = $this->tempVoiceNoteFile->store('voicenotes', 'public');

        // 2. Create the VoiceNote record
        $voiceNote = VoiceNote::create([
            'chat_channel_id' => $this->activeChannel->id,
            'user_id'         => Auth::id(),
            'file_path'       => $path,
            'duration'        => 0, // We can calculate this on the frontend later if needed
        ]);

        // 3. Create the ChatMessage record, linking the voice note
        $message = ChatMessage::create([
            'chat_channel_id'   => $this->activeChannel->id,
            'user_id'           => Auth::id(),
            'message'           => '', // Voice notes have no text content
            'voice_note_id'     => $voiceNote->id,
        ]);

        $message->load(['user.profile', 'voiceNote']);

        // 4. Broadcast the new message event
        try {
            broadcast(new NewChatMessage($message));
            Log::info('✅ Voice message broadcasted successfully');
        } catch (\Exception $e) {
            Log::error('❌ Voice message broadcast failed', ['error' => $e->getMessage(), 'message_id' => $message->id]);
        }

        // 5. Update channel's last message timestamp
        $this->activeChannel->update(['last_message_at' => now()]);

        // 6. Add to local collection to show immediately
        $this->chatMessages->push($message);

        // 7. Reset state and scroll
        $this->reset('tempVoiceNoteFile');
        $this->dispatch('scroll-chat-to-bottom');
    }

    public function handleMessageDeleted($event): void
    {
        $messageId = $event['messageId'];

        // Prevent echo from removing message from the user who deleted it
        if ($this->chatMessages->contains('id', $messageId)) {
            $this->chatMessages = $this->chatMessages->where('id', '!=', $messageId)->values();
        }
    }

        public function handleNewMessage($event): void
    {
        $messageData = $event['message'];

        Log::info('🎉 Received new message', [
            'message_id' => $messageData['id'],
            'user_id' => $messageData['user_id'],
            'current_user' => Auth::id(),
        ]);

        // Prevent duplicate messages
        if ($this->chatMessages->contains('id', $messageData['id'])) {
            Log::info('ℹ️ Skipping duplicate message', ['id' => $messageData['id']]);
            return;
        }

        // Fetch the actual message from database with relationships
        $message = ChatMessage::with(['user.profile', 'parentMessage.user', 'voiceNote'])
            ->find($messageData['id']);

        if (!$message) {
            Log::error('❌ Message not found in database', ['id' => $messageData['id']]);
            return;
        }

        // Add new message to collection
        $this->chatMessages->push($message);
        Log::info('✅ Added new message to collection', ['id' => $messageData['id']]);

        // Dispatch event to scroll to the new message
        $this->dispatch('scroll-chat-to-bottom');
    }

    public function toggleReaction(int $messageId, string $reaction): void
    {
        $message = $this->chatMessages->firstWhere('id', $messageId);

        if ($message) {
            $dbMessage = ChatMessage::find($messageId);
            $dbMessage->toggleReaction($reaction, Auth::id());

            $updatedMessage = ChatMessage::with(['user.profile', 'parentMessage.user'])->find($messageId);

            $this->chatMessages = $this->chatMessages->map(function ($msg) use ($messageId, $updatedMessage) {
                if ($msg->id === $messageId) {
                    return $updatedMessage;
                }
                return $msg;
            });
        }
        $this->reactingTo = null;
    }

    #[On('confirmDeleteMessage')]
    public function deleteMessage(int $messageId): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user?->isAdmin()) {
            return;
        }

        $message = ChatMessage::find($messageId);

        if ($message) {
            $channelId = $message->chat_channel_id;
            $message->delete();
            $this->chatMessages = $this->chatMessages->where('id', '!=', $messageId)->values();

            broadcast(new ChatMessageDeleted($messageId, $channelId))->toOthers();
        }
    }

    public function setReplyingTo(int $messageId): void
    {
        $this->replyingTo = ChatMessage::find($messageId);
    }

    public function cancelReply(): void
    {
        $this->replyingTo = null;
    }

    public function joinChannel(int $channelId): void
    {
        $channel = ChatChannel::find($channelId);
        if ($channel && in_array($channel->type, ['public', 'premium'])) {
            if ($channel->type === 'premium') {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                if (!$user->isPremium() && !$user->isAdmin()) {
                    // Dispatch an event to show the premium modal.
                    $this->dispatch('showPremiumModal', message: 'Debes tener una suscripción premium para unirte a este canal.');
                    return;
                }
            }

            $channel->participants()->attach(Auth::id());
            $this->loadChannels();
            $this->changeChannel($channelId);
        }
    }

    public function leaveChannel(int $channelId): void
    {
        $channel = ChatChannel::find($channelId);
        if ($channel) {
            $channel->participants()->detach(Auth::id());
            $this->activeChannel = null;
            $this->chatMessages = collect();
            $this->replyingTo = null;
            $this->reactingTo = null;
            $this->loadChannels();
        }
    }

    public function setReactingTo(?int $messageId): void
    {
        $this->reactingTo = $messageId;
    }

    public function render()
    {
        $channelsForDisplay = $this->channels;
        $joinableChannelsForDisplay = $this->joinableChannels;

        if (!empty($this->search)) {
            $searchTerm = strtolower($this->search);
            $channelsForDisplay = $this->channels->filter(function ($channel) use ($searchTerm) {
                return str_contains(strtolower($channel->name), $searchTerm);
            });
            $joinableChannelsForDisplay = $this->joinableChannels->filter(function ($channel) use ($searchTerm) {
                return str_contains(strtolower($channel->name), $searchTerm);
            });
        }

        return view('user_panel.chat', [
            'channelsForDisplay' => $channelsForDisplay,
            'joinableChannelsForDisplay' => $joinableChannelsForDisplay,
        ]);
    }

    /**
     * Override validation to handle Collections properly
     */
    protected function getValidationAttributes()
    {
        // Ensure we return an array for validation attributes
        return [];
    }

    /**
     * Override validation messages handling
     */
    protected function getValidationMessages()
    {
        // Ensure we return an array for validation messages
        return [];
    }

    /**
     * Override the validate method to handle array_merge properly
     */
    public function validate($rules = null, $messages = [], $attributes = [])
    {
        // Ensure all parameters are arrays to prevent array_merge errors
        $rules = is_array($rules) ? $rules : [];
        $messages = is_array($messages) ? $messages : [];
        $attributes = is_array($attributes) ? $attributes : [];

        return parent::validate($rules, $messages, $attributes);
    }

    public function updatedActiveChannel(): void
    {
        // This will force Livewire to re-register listeners when activeChannel changes
        $this->dispatch('$refresh');
    }
}
