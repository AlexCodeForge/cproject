<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Cookie;

class ThemeToggle extends Component
{
    public $theme;

    public function mount()
    {
        $this->theme = Cookie::get('theme', 'light');
        $this->dispatch('theme-toggled', $this->theme);
    }

    public function toggleTheme()
    {
        $this->theme = $this->theme === 'dark' ? 'light' : 'dark';
        Cookie::queue('theme', $this->theme, 60 * 24 * 365); // 1 year
        $this->dispatch('theme-toggled', $this->theme);
    }

    public function render()
    {
        return view('livewire.components.theme-toggle');
    }
}
