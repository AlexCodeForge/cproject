<?php

namespace App\Livewire\UserPanel;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Course;

#[Layout('layouts.app')]
class Courses extends Component
{
    public $courses;

    public function mount()
    {
        $this->courses = Course::where('status', 'published')->get();
    }

    public function render()
    {
        return view('livewire.user-panel.courses');
    }
}
