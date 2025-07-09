<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatParticipant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chat_channel_id',
        'user_id',
        'role',
        'joined_at',
        'last_read_at',
        'is_muted',
        'is_banned',
        'banned_at',
        'ban_reason',
        'banned_by',
        'permissions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'joined_at' => 'datetime',
        'last_read_at' => 'datetime',
        'banned_at' => 'datetime',
        'is_muted' => 'boolean',
        'is_banned' => 'boolean',
        'permissions' => 'json',
    ];

    /**
     * Get the user that is a participant.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the channel that the user is a participant in.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(ChatChannel::class, 'chat_channel_id');
    }
}
