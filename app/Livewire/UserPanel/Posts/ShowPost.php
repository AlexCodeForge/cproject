<?php

namespace App\Livewire\UserPanel\Posts;

use Livewire\Component;
use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class ShowPost extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function confirmDelete()
    {
        $this->dispatch('showConfirmationModal', 'Eliminar Post', '¿Estás seguro que quieres eliminar este post?', 'deletePost');
    }

    #[On('deletePost')]
    public function deletePost()
    {
        $this->post->delete();
        session()->flash('message', 'Post eliminado exitosamente.');
        return redirect()->route('user.feed');
    }

    public function toggleFeatured()
    {
        $this->post->is_featured = !$this->post->is_featured;
        $this->post->save();
        $this->dispatch('showSuccessModal', 'Éxito', 'El estado de destacado del post ha sido actualizado.');
    }

    public function toggleArchived()
    {
        $this->post->status = $this->post->status === 'archived' ? 'published' : 'archived';
        $this->post->save();
        $this->dispatch('showSuccessModal', 'Éxito', 'El estado de archivado del post ha sido actualizado.');
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
