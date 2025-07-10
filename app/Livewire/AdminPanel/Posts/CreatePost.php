<?php

namespace App\Livewire\AdminPanel\Posts;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;

#[Layout('layouts.admin')]
class CreatePost extends Component
{
    use WithFileUploads;

    #[Validate('required|string|min:3|max:255')]
    public $title = '';

    #[Validate('nullable|string|max:500')]
    public $excerpt = '';

    #[Validate('required|string')]
    public $content = '';

    #[Validate('nullable|image|max:1024')]
    public $featured_image;

    #[Validate('required|in:draft,published,archived')]
    public $status = 'draft';

    #[Validate('boolean')]
    public $is_premium = false;

    #[Validate('boolean')]
    public $is_featured = false;

    #[Validate('nullable|array')]
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

    public function mount()
    {
        Log::info('CreatePost component mounted.');
        // Set default published_at to today if not set
        if (empty($this->published_at)) {
            $this->published_at = now()->format('Y-m-d');
        }
    }

    public function publishPost()
    {
        $this->status = 'published';
        $this->save();
    }

    public function saveAsDraft()
    {
        $this->status = 'draft';
        $this->save();
    }

    public function save()
    {
        Log::info('Attempting to save new post.', ['title' => $this->title, 'status' => $this->status]);

        // Convert comma-separated tags string to an array
        if (!empty($this->tags) && is_string($this->tags)) {
            $this->tags = collect(explode(',', $this->tags))
                            ->map(fn($tag) => trim($tag))
                            ->filter() // Remove any empty strings resulting from extra commas
                            ->unique() // Ensure unique tags
                            ->values() // Reset array keys
                            ->toArray();
        } else {
            $this->tags = []; // Ensure it's an empty array if input is empty or not a string
        }

        $this->validate();

        $featuredImagePath = null;
        if ($this->featured_image) {
            $featuredImagePath = $this->featured_image->store('posts/featured', 'public');
            Log::info('Featured image uploaded.', ['path' => $featuredImagePath]);
        }

        try {
            Post::create([
                'user_id' => auth()->id(),
                'title' => $this->title,
                'slug' => Str::slug($this->title), // Slug is generated in model, but can be set here too
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

            session()->flash('message', 'Post created successfully!');
            Log::info('Post created successfully.', ['title' => $this->title]);

            return $this->redirect(route('admin.posts.index'), navigate: true);
        } catch (\Exception $e) {
            Log::error('Error creating post.', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Error creating post: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $categories = \App\Models\PostCategory::all();
        return view('admin_panel.posts.create', compact('categories'));
    }
}
