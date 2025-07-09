<?php

namespace App\Livewire\AdminPanel\Posts;

use Livewire\Component;
use App\Models\Post;
use App\Models\PostCategory;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class CreatePost extends Component
{
    public $title;
    public $content;
    public $post_category_id;
    public $is_premium = false;
    public $status = 'draft';

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'post_category_id' => 'required|exists:post_categories,id',
            'is_premium' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        Post::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'content' => $this->content,
            'post_category_id' => $this->post_category_id,
            'is_premium' => $this->is_premium,
            'status' => $this->status,
            'published_at' => $this->status === 'published' ? now() : null,
        ]);

        session()->flash('message', 'Post successfully created.');

        return redirect()->route('admin.posts.index');
    }

    public function render()
    {
        $categories = PostCategory::all();
        return view('admin_panel.posts.create', [
            'categories' => $categories,
        ]);
    }
}
