<?php

namespace App\Livewire\AdminPanel\Categories;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class CategoryManagement extends Component
{
    public function render()
    {
        return view('admin_panel.categories.category-management');
    }
}
