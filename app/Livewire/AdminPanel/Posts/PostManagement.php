<?php

namespace App\Livewire\AdminPanel\Posts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use App\Models\PostCategory; // Added import for PostCategory

#[Layout('layouts.admin')]
class PostManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $category = ''; // New property for category filter
    public $status = '';   // New property for status filter

    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'perPage' => ['except' => 10, 'as' => 'pp'],
        'category' => ['except' => '', 'as' => 'cat'], // Add to query string
        'status' => ['except' => '', 'as' => 'st'],     // Add to query string
    ];

    public function mount()
    {
        Log::info('PostManagement component mounted.');
    }

    public function render()
    {
        Log::info('Rendering PostManagement component.', ['search' => $this->search, 'perPage' => $this->perPage, 'category' => $this->category, 'status' => $this->status]);

        $posts = Post::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('content', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('name', $this->category);
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate($this->perPage);

        $categories = PostCategory::all(); // Fetch all post categories

        return view('admin_panel.posts.livewire.post-management', [
            'posts' => $posts,
            'categories' => $categories, // Pass categories to the view
            'availableStatuses' => ['draft', 'published', 'archived'], // Pass statuses to the view
        ]);
    }

    public function createNewPost()
    {
        Log::info('Navigating to CreatePost page.');
        return $this->redirect(route('admin.posts.create'), navigate: true);
    }

    public function editPost($postId)
    {
        Log::info('Navigating to EditPost page.', ['postId' => $postId]);
        return $this->redirect(route('admin.posts.edit', $postId), navigate: true);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function getPublishedPostsCountProperty()
    {
        return Post::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('content', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('name', $this->category);
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->where('status', 'published')
            ->count();
    }

    public function getFeaturedPostsCountProperty()
    {
        return Post::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('content', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('name', $this->category);
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->where('is_featured', true)
            ->count();
    }

    public function getPremiumPostsCountProperty()
    {
        return Post::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('content', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('name', $this->category);
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->where('is_premium', true)
            ->count();
    }
}
