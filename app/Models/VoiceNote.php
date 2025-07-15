<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VoiceNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_channel_id',
        'user_id',
        'file_path',
        'duration',
    ];

    protected $appends = ['url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chatChannel()
    {
        return $this->belongsTo(ChatChannel::class);
    }

    public function chatMessage()
    {
        return $this->hasOne(ChatMessage::class);
    }

    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->file_path);
    }
}
