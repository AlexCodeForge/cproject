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

        static::creating(function ($post) {
            $post->slug = Str::slug($post->title);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }
}
