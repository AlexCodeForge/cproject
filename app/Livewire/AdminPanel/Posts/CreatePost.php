<?php

namespace App\Livewire\AdminPanel\Posts;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewPostPublished;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
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

    protected function messages()
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'title.min' => 'El título debe tener al menos :min caracteres.',
            'title.max' => 'El título no debe exceder los :max caracteres.',
            'excerpt.string' => 'El extracto debe ser una cadena de texto.',
            'excerpt.max' => 'El extracto no debe exceder los :max caracteres.',
            'content.required' => 'El contenido es obligatorio.',
            'content.string' => 'El contenido debe ser una cadena de texto.',
            'featured_image.image' => 'La imagen destacada debe ser una imagen.',
            'featured_image.max' => 'La imagen destacada no debe exceder los :max KB.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.',
            'is_premium.boolean' => 'El valor de premium debe ser verdadero o falso.',
            'is_featured.boolean' => 'El valor de destacado debe ser verdadero o falso.',
            'tags.array' => 'Las etiquetas deben ser un arreglo.',
            'meta_title.string' => 'El meta título debe ser una cadena de texto.',
            'meta_title.max' => 'El meta título no debe exceder los :max caracteres.',
            'meta_description.string' => 'La meta descripción debe ser una cadena de texto.',
            'meta_description.max' => 'La meta descripción no debe exceder los :max caracteres.',
            'published_at.date' => 'La fecha de publicación debe ser una fecha válida.',
            'reading_time.integer' => 'El tiempo de lectura debe ser un número entero.',
            'reading_time.min' => 'El tiempo de lectura debe ser al menos :min.',
            'difficulty_level.numeric' => 'El nivel de dificultad debe ser un valor numérico.',
            'difficulty_level.min' => 'El nivel de dificultad debe ser al menos :min.',
            'difficulty_level.max' => 'El nivel de dificultad no debe exceder los :max.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
        ];
    }

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

        try {
            $this->validate(); // Move this inside the try block

            $featuredImagePath = null;
            if ($this->featured_image) {
                $featuredImagePath = $this->featured_image->store('posts/featured', 'public');
                Log::info('Featured image uploaded.', ['path' => $featuredImagePath]);
            }

            $post = Post::create([
                'user_id' => Auth::id(),
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

            if ($post->status === 'published') {
                $usersToNotify = User::where('id', '!=', Auth::id())->get();
                if ($usersToNotify->isNotEmpty()) {
                    Notification::send($usersToNotify, new NewPostPublished($post));
                    Log::info('Sent NewPostPublished notification to ' . $usersToNotify->count() . ' users.');
                }
            }

            $this->dispatch('showSuccessModal', message: 'Post created successfully!');
            Log::info('Post created successfully.', ['title' => $this->title]);

            $this->dispatch('post-created');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Ensure $e->errors() is always a collection before flattening and converting to array
            $this->dispatch('showErrorModal', message: collect($e->errors())->flatten()->toArray(), title: 'Validation Error');
            throw $e; // Re-throw the exception so Livewire can handle individual field errors
        } catch (\Exception $e) {
            Log::error('Error creating post.', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->dispatch('showErrorModal', message: 'Error creating post: ' . $e->getMessage(), title: 'Post Creation Error');
        }
    }

    public function render()
    {
        $categories = \App\Models\PostCategory::all();
        return view('admin_panel.posts.create', compact('categories'));
    }
}
