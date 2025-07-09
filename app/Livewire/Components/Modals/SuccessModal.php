<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use Livewire\Attributes\On;

class SuccessModal extends Component
{
    public $showModal = false;
    public $title = '¡Éxito!';
    public $message = '';

    #[On('showSuccessModal')]
    public function show($message, $title = '¡Éxito!')
    {
        $this->title = $title;
        $this->message = $message;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.components.modals.success-modal');
    }
}
