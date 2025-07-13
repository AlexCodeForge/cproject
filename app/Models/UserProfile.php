<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'avatar',
        'bio',
        'location',
        'website',
        'twitter_handle',
        'linkedin_url',
        'trading_experience',
        'trading_interests',
        'portfolio_value',
        'public_profile',
        'show_portfolio',
        'timezone',
        'language',
        'notification_preferences',
        'last_active_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'trading_interests' => 'json',
        'notification_preferences' => 'json',
        'public_profile' => 'boolean',
        'show_portfolio' => 'boolean',
        'last_active_at' => 'datetime',
    ];

    /**
     * Get the URL for the user's avatar.
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        $name = $this->user->name;
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
