<?php

namespace App\Livewire\UserPanel\Posts;

use Livewire\Component;
use App\Models\Post;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ShowPost extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function render()
    {
        $relatedPosts = Post::published()
            ->where('post_category_id', $this->post->post_category_id)
            ->where('id', '!=', $this->post->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('user_panel.posts.show', [
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
