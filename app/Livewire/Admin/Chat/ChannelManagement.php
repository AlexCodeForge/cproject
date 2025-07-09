<?php

namespace App\Livewire\Admin\Chat;

use App\Models\ChatChannel;
use Livewire\Component;
use Illuminate\Support\Str;

class ChannelManagement extends Component
{
    public $name;
    public $description;
    public $type = 'public';
    public $requires_premium = false;

    public function saveChannel()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:public,premium,private,direct',
            'requires_premium' => 'boolean',
        ]);

        ChatChannel::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'type' => $this->type,
            'requires_premium' => $this->requires_premium,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Channel created successfully.');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.chat.channel-management');
    }
}
