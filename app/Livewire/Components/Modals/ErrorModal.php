<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use Livewire\Attributes\On;

class ErrorModal extends Component
{
    public $showModal = false;
    public $title = 'Error';
    public $message = []; // Change to an array to hold multiple messages

    #[On('showErrorModal')]
    public function show($message, $title = 'Error')
    {
        $this->title = $title;
        // Ensure message is always an array
        $this->message = is_array($message) ? $message : [$message];
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.components.modals.error-modal');
    }
}
