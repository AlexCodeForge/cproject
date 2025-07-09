<?php

namespace App\Livewire\UserPanel;

use App\Events\NewChatMessage;
use App\Models\ChatChannel;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Chat extends Component
{
    public ?ChatChannel $activeChannel = null;
    public Collection $channels;
    public Collection $joinableChannels;
    public Collection $chatMessages;
    public string $messageText = '';
    public ?ChatMessage $replyingTo = null;
    public ?int $reactingTo = null;
    public array $availableReactions = ['👍', '❤️', '😂', '😮', '😢', '😡'];

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
        $this->dispatch('scroll-to-bottom');
    }

    public function loadChannels(): void
    {
        $this->channels = Auth::user()->chatChannels()
            ->where('is_active', true)
            ->orderByDesc('last_message_at')
            ->get() ?? collect();

        $this->joinableChannels = ChatChannel::where('is_active', true)
            ->where('type', 'public')
            ->whereNotIn('id', $this->channels->pluck('id') ?? [])
            ->orderBy('sort_order')
            ->get() ?? collect();
    }

    public function changeChannel(int $channelId): void
    {
        $this->activeChannel = ChatChannel::findOrFail($channelId);
        $this->loadMessages();
        $this->reactingTo = null;
        $this->replyingTo = null;

        // Dispatch event to JavaScript to update Echo listeners
        $this->dispatch('channel-changed', channelId: $channelId);
    }

    public function loadMessages(): void
    {
        if ($this->activeChannel) {
            $this->chatMessages = $this->activeChannel->messages()
                ->with(['user.profile', 'parentMessage.user'])
                ->latest()
                ->take(50)
                ->get()
                ->reverse()
                ->values();
        } else {
            $this->chatMessages = collect();
        }
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
        $this->dispatch('new-message-added');
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
        $message = ChatMessage::with(['user.profile', 'parentMessage.user'])
            ->find($messageData['id']);

        if (!$message) {
            Log::error('❌ Message not found in database', ['id' => $messageData['id']]);
            return;
        }

        // Add new message to collection
        $this->chatMessages->push($message);
        Log::info('✅ Added new message to collection', ['id' => $messageData['id']]);

        // Dispatch event to scroll to the new message
        $this->dispatch('new-message-added');
    }

    public function toggleReaction(int $messageId, string $reaction): void
    {
        $message = ChatMessage::find($messageId);
        if ($message) {
            $message->toggleReaction($reaction, Auth::id());
            $this->loadMessages(); // Reload to show updated reactions
        }
        $this->reactingTo = null;
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
        if ($channel && $channel->type === 'public') {
            $channel->participants()->attach(Auth::id());
            $this->loadChannels();
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
        return view('user_panel.chat');
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
