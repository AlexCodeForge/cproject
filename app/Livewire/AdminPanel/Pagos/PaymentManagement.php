<?php

namespace App\Livewire\AdminPanel\Pagos;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class PaymentManagement extends Component
{
    public function render()
    {
        return view('admin_panel.pagos.payment-management');
    }
}
