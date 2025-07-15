<?php

use App\Models\ChatChannel;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{channelId}', function ($user, $channelId) {
    Log::info("Broadcasting auth attempt for channel: chat.{$channelId}", ['user_id' => $user->id]);
    try {
        $channel = ChatChannel::find($channelId);

        if ($channel && $channel->participants()->where('user_id', $user->id)->exists()) {
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name)
            ];
            Log::info("User {$user->id} authorized for channel {$channelId}.", ['data' => $userData]);
            return $userData;
        }

        Log::warning("User {$user->id} failed authorization for channel {$channelId}.");
        return false;
    } catch (\Exception $e) {
        Log::error("Error during channel authorization for user {$user->id} on channel {$channelId}", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return false;
    }
});
