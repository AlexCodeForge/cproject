<?php

namespace App\Livewire\AdminPanel\Posts;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class EditPost extends Component
{
    use WithFileUploads;

    public Post $post;

    #[Validate('required|string|min:3|max:255')]
    public $title = '';

    #[Validate('nullable|string|max:500')]
    public $excerpt = '';

    #[Validate('required|string')]
    public $content = '';

    #[Validate('nullable|image|max:1024')]
    public $featured_image;

    public $existing_featured_image;

    #[Validate('required|in:draft,published,archived')]
    public $status = 'draft';

    #[Validate('boolean')]
    public $is_premium = false;

    #[Validate('boolean')]
    public $is_featured = false;

    #[Validate('nullable')]
    public $tags = [];

    #[Validate('nullable|string|max:255')]
    public $meta_title = '';

    #[Validate('nullable|string|max:500')]
    public $meta_description = '';

    #[Validate('nullable|date')]
    public $published_at;

    #[Validate('nullable|integer|min:1')]
    public $reading_time;

    #[Validate('nullable|numeric|min:1.0|max:5.0')]
    public $difficulty_level;

    #[Validate('nullable|exists:post_categories,id')]
    public $category_id;

    protected function messages()
    {
        return [
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a string.',
            'title.min' => 'The title must be at least :min characters.',
            'title.max' => 'The title cannot be longer than :max characters.',
            'excerpt.string' => 'The excerpt must be a string.',
            'excerpt.max' => 'The excerpt cannot be longer than :max characters.',
            'content.required' => 'The content is required.',
            'content.string' => 'The content must be a string.',
            'featured_image.image' => 'The featured image must be an image file.',
            'featured_image.max' => 'The featured image cannot be larger than :max KB.',
            'status.required' => 'The status is required.',
            'status.in' => 'The selected status is invalid.',
            'is_premium.boolean' => 'The premium value must be true or false.',
            'is_featured.boolean' => 'The featured value must be true or false.',
            'tags.array' => 'Tags must be an array.',
            'meta_title.string' => 'The meta title must be a string.',
            'meta_title.max' => 'The meta title cannot be longer than :max characters.',
            'meta_description.string' => 'The meta description must be a string.',
            'meta_description.max' => 'The meta description cannot be longer than :max characters.',
            'published_at.date' => 'The publication date must be a valid date.',
            'reading_time.integer' => 'Reading time must be an integer.',
            'reading_time.min' => 'Reading time must be at least :min.',
            'difficulty_level.numeric' => 'Difficulty level must be a numeric value.',
            'difficulty_level.min' => 'Difficulty level must be at least :min.',
            'difficulty_level.max' => 'Difficulty level cannot be greater than :max.',
            'category_id.exists' => 'The selected category is invalid.',
        ];
    }

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->excerpt = $post->excerpt;
        $this->content = $post->content;
        $this->existing_featured_image = $post->featured_image;
        $this->status = $post->status;
        $this->is_premium = $post->is_premium;
        $this->is_featured = $post->is_featured;
        $this->tags = is_array($post->tags) ? implode(', ', $post->tags) : $post->tags;
        $this->meta_title = $post->meta_title;
        $this->meta_description = $post->meta_description;
        $this->published_at = $post->published_at ? $post->published_at->format('Y-m-d') : null;
        $this->reading_time = $post->reading_time;
        $this->difficulty_level = $post->difficulty_level;
        $this->category_id = $post->post_category_id;
    }

    public function publishPost()
    {
        $this->status = 'published';
        $this->update();
    }

    public function saveAsDraft()
    {
        $this->status = 'draft';
        $this->update();
    }

    public function update()
    {
        Log::info('Attempting to update post.', ['post_id' => $this->post->id, 'title' => $this->title]);

        if (is_string($this->tags)) {
            $this->tags = array_filter(array_map('trim', explode(',', $this->tags)));
        }

        $this->validate();

        try {
            $featuredImagePath = $this->post->featured_image;
            if ($this->featured_image) {
                $featuredImagePath = $this->featured_image->store('posts/featured', 'public');
                Log::info('New featured image uploaded.', ['path' => $featuredImagePath]);
            }

            $this->post->update([
                'title' => $this->title,
                'excerpt' => $this->excerpt,
                'content' => $this->content,
                'featured_image' => $featuredImagePath,
                'status' => $this->status,
                'is_premium' => $this->is_premium,
                'is_featured' => $this->is_featured,
                'tags' => $this->tags,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'published_at' => $this->published_at,
                'reading_time' => $this->reading_time,
                'difficulty_level' => $this->difficulty_level,
                'post_category_id' => $this->category_id,
            ]);

            $this->dispatch('showSuccessModal', message: 'Post updated successfully!');
            Log::info('Post updated successfully.', ['post_id' => $this->post->id]);

            $this->dispatch('post-updated');
        } catch (\Exception $e) {
            Log::error('Error updating post.', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->dispatch('showErrorModal', message: 'Error updating post: ' . $e->getMessage(), title: 'Post Update Error');
        }
    }

    public function render()
    {
        $categories = PostCategory::all();
        return view('admin_panel.posts.edit', compact('categories'));
    }
}
