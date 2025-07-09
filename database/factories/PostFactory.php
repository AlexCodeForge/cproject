<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        $title = $this->faker->sentence;
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'post_category_id' => PostCategory::inRandomOrder()->first()->id ?? 1,
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->paragraph,
            'content' => $this->faker->paragraphs(3, true),
            'featured_image' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'is_premium' => $this->faker->boolean,
            'published_at' => now(),
        ];
    }
}
