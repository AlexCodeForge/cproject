<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ChatMessageDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $messageId;
    public int $channelId;

    /**
     * Create a new event instance.
     */
    public function __construct(int $messageId, int $channelId)
    {
        $this->messageId = $messageId;
        $this->channelId = $channelId;
        Log::info('🗑️ ChatMessageDeleted event created.', ['messageId' => $this->messageId, 'channelId' => $this->channelId]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->channelId),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $payload = ['messageId' => $this->messageId];
        Log::info('🎯 Broadcasting ChatMessageDeleted data:', $payload);
        return $payload;
    }
}
