<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
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
        'parent_message_id',
        'message',
        'type',
        'attachments',
        'mentions',
        'is_edited',
        'is_pinned',
        'is_deleted',
        'edited_at',
        'deleted_at',
        'reactions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attachments' => 'json',
        'mentions' => 'json',
        'reactions' => 'json',
        'is_edited' => 'boolean',
        'is_pinned' => 'boolean',
        'is_deleted' => 'boolean',
        'edited_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who sent the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the channel the message belongs to.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(ChatChannel::class, 'chat_channel_id');
    }

    /**
     * Get the parent message for a threaded reply.
     */
    public function parentMessage(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'parent_message_id');
    }

    /**
     * Add or remove a reaction for a user.
     *
     * @param string $reaction
     * @param int $userId
     * @return void
     */
    public function toggleReaction(string $reaction, int $userId): void
    {
        // Ensure reactions is always an array, not null
        $reactions = $this->reactions ?: [];

        // Ensure we have an array for this specific reaction
        if (!isset($reactions[$reaction]) || !is_array($reactions[$reaction])) {
            $reactions[$reaction] = [];
        }

        if (in_array($userId, $reactions[$reaction])) {
            // User has already reacted, so remove their reaction
            $reactions[$reaction] = array_values(array_diff($reactions[$reaction], [$userId]));
            if (empty($reactions[$reaction])) {
                unset($reactions[$reaction]);
            }
        } else {
            // User has not reacted, so add their reaction
            $reactions[$reaction][] = $userId;
        }

        $this->update(['reactions' => $reactions]);
    }

    /**
     * Get the created_at timestamp in a human-readable format.
     *
     * @return string
     */
    public function getHumanReadableCreatedAtAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
