<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use Livewire\Attributes\On;

class ConfirmationModal extends Component
{
    public $showModal = false;
    public $title = 'Confirmar Acción';
    public $message = '¿Estás seguro?';
    public $confirmAction = '';
    public $confirmParams = [];

    #[On('showConfirmationModal')]
    public function show($title, $message, $confirmAction, $params = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->confirmAction = $confirmAction;
        $this->confirmParams = $params;
        $this->showModal = true;
    }

    public function confirm()
    {
        $this->dispatch($this->confirmAction, $this->confirmParams['channelId']);
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.components.modals.confirmation-modal');
    }
}
