<?php

namespace App\Livewire\AdminPanel\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class UserManagement extends Component
{
    public function render()
    {
        return view('admin_panel.users.livewire.user_management');
    }
}
