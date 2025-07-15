<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatChannel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'color',
        'icon',
        'is_active',
        'requires_premium',
        'max_members',
        'created_by',
        'moderators',
        'settings',
        'last_message_at',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'requires_premium' => 'boolean',
        'moderators' => 'json',
        'settings' => 'json',
        'last_message_at' => 'datetime',
    ];

    /**
     * Get the user who created the channel.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the messages for the chat channel.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->latest();
    }

    /**
     * Get the participants for the chat channel.
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'chat_participants');
    }

    /**
     * The chat participants that belong to the channel.
     */
    public function chatParticipants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class, 'chat_channel_id');
    }
}
