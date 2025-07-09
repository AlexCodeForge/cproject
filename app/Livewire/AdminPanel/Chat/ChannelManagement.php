<?php

namespace App\Livewire\AdminPanel\Chat;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ChannelManagement extends Component
{
    public function render()
    {
        return view('admin_panel.chat.channel-management');
    }
}
