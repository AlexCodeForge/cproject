<?php

namespace App\Livewire\AdminPanel\Lms;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class CourseManagement extends Component
{
    public function render()
    {
        return view('livewire.admin-panel.lms.course-management');
    }
}
