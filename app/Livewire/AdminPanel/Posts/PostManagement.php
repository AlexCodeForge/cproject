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
    public $featured = ''; // New property for featured filter
    public $postToDeleteId = null;

    protected $listeners = ['postDeleted' => 'handlePostDeleted'];

    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'perPage' => ['except' => 10, 'as' => 'pp'],
        'category' => ['except' => '', 'as' => 'cat'], // Add to query string
        'status' => ['except' => '', 'as' => 'st'],     // Add to query string
        'featured' => ['except' => '', 'as' => 'feat'], // Add to query string
    ];

    public function mount()
    {
        Log::info('PostManagement component mounted.');
    }

    public function render()
    {
        Log::info('Rendering PostManagement component.', ['search' => $this->search, 'perPage' => $this->perPage, 'category' => $this->category, 'status' => $this->status, 'featured' => $this->featured]);

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
            ->when($this->featured !== '', function ($query) {
                $query->where('is_featured', $this->featured === 'yes');
            })
            ->latest()
            ->paginate($this->perPage);

        $categories = PostCategory::all(); // Fetch all post categories

        return view('admin_panel.posts.livewire.post-management', [
            'posts' => $posts,
            'categories' => $categories, // Pass categories to the view
            'availableStatuses' => ['draft', 'published', 'archived'], // Pass statuses to the view
            'availableFeatured' => [
                'yes' => 'Sí',
                'no' => 'No'
            ],
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

    public function showPost($postSlug)
    {
        Log::info('Navigating to ShowPost page.', ['postSlug' => $postSlug]);
        return $this->redirect(route('posts.show', $postSlug), navigate: true);
    }

    public function confirmDelete($postId)
    {
        $this->postToDeleteId = $postId;
        $this->dispatch('showConfirmationModal', [
            'title' => 'Confirm Delete',
            'message' => 'Are you sure you want to delete this post? This action cannot be undone.',
            'confirmAction' => 'deletePost'
        ]);
    }

    public function deletePost()
    {
        if ($this->postToDeleteId) {
            try {
                $post = Post::findOrFail($this->postToDeleteId);
                $post->delete();
                Log::info('Post deleted successfully.', ['postId' => $this->postToDeleteId]);
                $this->dispatch('showSuccessModal', message: 'Post deleted successfully!');
                $this->postToDeleteId = null;
                // We need to re-render the component to reflect the change
                $this->dispatch('postDeleted');
            } catch (\Exception $e) {
                Log::error('Error deleting post.', ['error' => $e->getMessage()]);
                $this->dispatch('showErrorModal', message: 'Error deleting post: ' . $e->getMessage(), title: 'Deletion Error');
                $this->postToDeleteId = null;
            }
        }
    }

    public function handlePostDeleted()
    {
        // This is just to force a re-render.
        // A simple way is to just call render() but that's not how livewire works.
        // So we just do nothing and livewire will re-render the component.
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

    public function updatingFeatured()
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
