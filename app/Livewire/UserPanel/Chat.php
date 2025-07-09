<?php

namespace App\Livewire\UserPanel;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Chat extends Component
{
    public function render()
    {
        return view('user_panel.chat');
    }
}
