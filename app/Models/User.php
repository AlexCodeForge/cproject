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
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, Billable, HasRoles;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'subscription_ends_at',
    ];

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
     * Get the user's subscription end date.
     *
     * @return \Carbon\Carbon|null
     */
    public function getSubscriptionEndsAtAttribute()
    {
        $subscription = $this->subscription('default');

        if (!$subscription) {
            return null;
        }

        // For a canceled subscription on a grace period, return the ends_at date.
        if ($subscription->onGracePeriod()) {
            return $subscription->ends_at;
        }

        // For an active, non-canceled subscription, fetch the current period end from Stripe.
        if ($subscription->active() && !$subscription->canceled()) {
            // Note: This makes an API call to Stripe.
            try {
                $stripeSubscription = $subscription->asStripeSubscription();
                return Carbon::createFromTimestamp($stripeSubscription->current_period_end);
            } catch (\Exception $e) {
                // Handle cases where the Stripe API call might fail
                return null;
            }
        }

        // Return null if there's no active subscription.
        return null;
    }

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

    /**
     * Get the URL for the user's avatar.
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->profile && $this->profile->avatar) {
            return asset('storage/' . $this->profile->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}
