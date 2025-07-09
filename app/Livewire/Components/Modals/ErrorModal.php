<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use Livewire\Attributes\On;

class ErrorModal extends Component
{
    public $showModal = false;
    public $title = 'Error';
    public $message = 'Ha ocurrido un error inesperado.';

    #[On('showErrorModal')]
    public function show($message, $title = 'Error')
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
        return view('livewire.components.modals.error-modal');
    }
}
