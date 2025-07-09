<?php

namespace App\Livewire\AdminPanel\Posts;

use Livewire\Component;
use App\Models\Post;
use App\Models\PostCategory;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class EditPost extends Component
{
    public Post $post;

    public $title;
    public $content;
    public $post_category_id;
    public $is_premium;
    public $status;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->post_category_id = $post->post_category_id;
        $this->is_premium = $post->is_premium;
        $this->status = $post->status;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'post_category_id' => 'required|exists:post_categories,id',
            'is_premium' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        $this->post->update([
            'title' => $this->title,
            'content' => $this->content,
            'post_category_id' => $this->post_category_id,
            'is_premium' => $this->is_premium,
            'status' => $this->status,
            'published_at' => $this->status === 'published' ? ($this->post->published_at ?? now()) : null,
        ]);

        session()->flash('message', 'Post successfully updated.');

        return redirect()->route('admin.posts.index');
    }

    public function render()
    {
        $categories = PostCategory::all();
        return view('admin_panel.posts.edit', [
            'categories' => $categories,
        ]);
    }
}
