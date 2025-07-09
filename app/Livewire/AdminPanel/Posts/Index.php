<?php

namespace App\Livewire\AdminPanel\Posts;

use Livewire\Component;
use App\Models\Post;
use App\Models\PostCategory;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public $selectedCategory = null;
    public $view = 'list'; // 'list' or 'grid'

    public function delete(Post $post)
    {
        $post->delete();
        session()->flash('message', 'Post successfully deleted.');
    }

    public function render()
    {
        $posts = Post::when($this->selectedCategory, function ($query) {
            $query->where('post_category_id', $this->selectedCategory);
        })->latest()->paginate(10);

        $categories = PostCategory::all();

        return view('admin_panel.posts.index', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }
}
