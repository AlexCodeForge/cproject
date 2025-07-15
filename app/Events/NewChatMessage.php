<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewChatMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The chat message instance.
     *
     * @var \App\Models\ChatMessage
     */
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.'.$this->message->chat_channel_id),
        ];
    }

    /**
     * The event's broadcast name.
     * Temporarily commented out to use default Laravel naming
     */
    /*
    public function broadcastAs(): string
    {
        return 'new.chat.message';
    }
    */

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        // We only need to send the ID. The client component will fetch the full message.
        // This ensures data consistency and avoids serialization issues with complex models.
        $data = [
            'message' => [
                'id' => $this->message->id
            ]
        ];

        Log::info('🎯 Broadcasting data:', $data);

        return $data;
    }
}
