<?php

namespace App\Livewire\UserPanel;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Post; // Import the Post model

#[Layout('layouts.app')]
class Feed extends Component
{
    public $posts; // Declare a public property to hold the posts

    public function mount()
    {
        // Fetch all published posts and eager load their categories
        $this->posts = Post::published()->with('category')->get();
    }

    public function render()
    {
        return view('user_panel.feed');
    }
}
