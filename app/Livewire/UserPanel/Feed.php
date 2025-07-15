<?php

namespace App\Livewire\UserPanel;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Post;
use App\Models\PostCategory;
use Livewire\Attributes\Url;

#[Layout('layouts.app')]
class Feed extends Component
{
    #[Url(as: 'q', except: '')]
    public $searchTerm = '';

    #[Url(except: 'all')]
    public string $activeCategory = 'all';

    public int $perPage = 9;

    public function loadMore()
    {
        $this->perPage += 9;
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['searchTerm', 'activeCategory'])) {
            $this->perPage = 9;
        }
    }

    public function filterByCategory(string $categoryName)
    {
        $this->activeCategory = $categoryName;
    }

    public function render()
    {
        $postsQuery = Post::published()
            ->with('category')
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('excerpt', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('content', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->activeCategory !== 'all', function ($query) {
                if ($this->activeCategory === 'premium') {
                    $query->where('is_premium', true);
                } else {
                    $query->whereHas('category', function ($q) {
                        $q->where('name', $this->activeCategory);
                    });
                }
            })
            ->orderBy('published_at', 'desc');

        $totalPosts = $postsQuery->clone()->count();
        $posts = $postsQuery->take($this->perPage)->get();

        $categories = PostCategory::whereHas('posts', function($query) {
            $query->published();
        })->get();

        return view('user_panel.feed', [
            'posts' => $posts,
            'categories' => $categories,
            'totalPosts' => $totalPosts
        ]);
    }
}
