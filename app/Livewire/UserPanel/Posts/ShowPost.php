<?php

namespace App\Livewire\UserPanel\Posts;

use Livewire\Component;
use App\Models\Post;

class ShowPost extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function render()
    {
        return view('user_panel.posts.show');
    }
}
