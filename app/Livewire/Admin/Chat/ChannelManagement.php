<?php

namespace App\Livewire\Admin\Chat;

use App\Models\ChatChannel;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection; // Add this import

#[Layout('layouts.admin')]
class ChannelManagement extends Component
{
    public $name;
    public $description;
    public $type = 'public';
    public $requires_premium = false;
    public ?ChatChannel $selectedChannel = null; // New property to hold the channel being edited
    public Collection $channels; // New property to hold all channels

    public function mount(): void
    {
        $this->loadChannels();
    }

    public function loadChannels(): void
    {
        $this->channels = ChatChannel::orderBy('created_at', 'desc')->get();
    }

    public function saveChannel(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:public,premium,private,direct',
            'requires_premium' => 'boolean',
        ]);

        if ($this->selectedChannel) {
            // Update existing channel
            $this->selectedChannel->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'type' => $this->type,
                'requires_premium' => $this->requires_premium,
            ]);
            session()->flash('message', 'Channel updated successfully.');
        } else {
            // Create new channel
            ChatChannel::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'type' => $this->type,
                'requires_premium' => $this->requires_premium,
                'created_by' => auth()->id(),
            ]);
            session()->flash('message', 'Channel created successfully.');
        }

        $this->resetForm();
        $this->loadChannels(); // Reload channels after save/update
    }

    public function editChannel(ChatChannel $channel): void
    {
        $this->selectedChannel = $channel;
        $this->name = $channel->name;
        $this->description = $channel->description;
        $this->type = $channel->type;
        $this->requires_premium = $channel->requires_premium;
    }

    public function deleteChannel(ChatChannel $channel): void
    {
        $channel->delete();
        session()->flash('message', 'Channel deleted successfully.');
        $this->loadChannels(); // Reload channels after deletion
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'description', 'type', 'requires_premium', 'selectedChannel']);
    }

    public function render()
    {
        return view('admin_panel.chat.channel-management');
    }
}
