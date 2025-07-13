<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use Livewire\Attributes\On;

class PremiumModal extends Component
{
    public $showModal = false;
    public $title = 'Acceso Premium Requerido';
    public $message = 'Esta es una función exclusiva para miembros premium.';

    #[On('showPremiumModal')]
    public function show($message = null, $title = null)
    {
        $this->title = $title ?? 'Acceso Premium Requerido';
        $this->message = $message ?? 'Esta es una función exclusiva para miembros premium.';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function goToPricing()
    {
        return redirect()->route('pricing');
    }

    public function render()
    {
        return view('livewire.components.modals.premium-modal');
    }
}
