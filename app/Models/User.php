<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\CustomResetPasswordNotification;
use App\Notifications\WelcomeEmailNotification;
use App\Notifications\CustomEmailVerificationNotification;
use Laravel\Cashier\Billable;
use App\Models\Post;
use App\Models\ChatChannel;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Billable, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomEmailVerificationNotification());
    }

    /**
     * Send the welcome email notification.
     *
     * @return void
     */
    public function sendWelcomeNotification()
    {
        $this->notify(new WelcomeEmailNotification($this));
    }

    /**
     * Get the user profile.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get the posts for the user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the chat channels the user is a participant in.
     */
    public function chatChannels()
    {
        return $this->belongsToMany(ChatChannel::class, 'chat_participants');
    }

    /**
     * Check if user has premium subscription
     */
    public function isPremium(): bool
    {
        return $this->hasRole('premium');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Get user's subscription status
     */
    public function getSubscriptionStatus(): string
    {
        if ($this->hasRole('admin')) {
            return 'admin';
        }

        if ($this->hasRole('premium')) {
            return 'premium';
        }

        return 'free';
    }
}
