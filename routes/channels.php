<?php

use App\Models\ChatChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{channelId}', function ($user, $channelId) {
    $channel = ChatChannel::find($channelId);

    if (! $channel) {
        return false;
    }

    return $channel->participants()->where('user_id', $user->id)->exists();
});
