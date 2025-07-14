<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'images',
        'status',
        'is_premium',
        'is_featured',
        'tags',
        'meta_title',
        'meta_description',
        'views_count',
        'likes_count',
        'comments_count',
        'shares_count',
        'published_at',
        'reading_time',
        'difficulty_level',
    ];

    protected $casts = [
        'images' => 'json',
        'tags' => 'json',
        'is_premium' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        $createSlug = function ($post) {
            $slug = Str::slug($post->title);
            $originalSlug = $slug;
            $count = 1;
            while (static::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }
            $post->slug = $slug;
        };

        static::creating($createSlug);
        static::updating($createSlug);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            });
    }
}
