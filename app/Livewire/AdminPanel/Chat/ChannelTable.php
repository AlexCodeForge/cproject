<?php

namespace App\Livewire\AdminPanel\Chat;

use App\Models\ChatChannel;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class ChannelTable extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    public $selectedChannel;
    public $showModal = false;

    // Properties for Create/Edit Modal
    public $showChannelModal = false;
    public ?ChatChannel $editingChannel;
    public $name = '';
    public $description = '';
    public $type = 'public';
    public $requires_premium = false;
    public $is_active = true;
    public $max_members;

    #[On('channel-deleted')]
    public function refreshComponent()
    {
        // This method can be empty. Its purpose is to be a target for the event,
        // which forces Livewire to re-render the component.
        // The render() method will then be called again with fresh data.
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:public,premium,private,direct',
            'requires_premium' => 'boolean',
            'is_active' => 'boolean',
            'max_members' => 'nullable|integer|min:0',
        ];
    }

    public function create()
    {
        $this->reset(['name', 'description', 'type', 'requires_premium', 'is_active', 'max_members']);
        $this->editingChannel = new ChatChannel(['is_active' => true]);
        $this->showChannelModal = true;
    }

    public function edit(ChatChannel $channel)
    {
        $this->editingChannel = $channel;
        $this->name = $channel->name;
        $this->description = $channel->description;
        $this->type = $channel->type;
        $this->requires_premium = $channel->requires_premium;
        $this->is_active = $channel->is_active;
        $this->max_members = $channel->max_members;
        $this->showChannelModal = true;
    }

    public function save()
    {
        $this->validate();

        $this->editingChannel->fill([
            'name' => $this->name,
            'slug' => \Illuminate\Support\Str::slug($this->name),
            'description' => $this->description,
            'type' => $this->type,
            'requires_premium' => $this->requires_premium,
            'is_active' => $this->is_active,
            'max_members' => $this->max_members,
        ]);

        if (!$this->editingChannel->created_by) {
            $this->editingChannel->created_by = auth()->id();
        }

        $this->editingChannel->save();

        $this->showChannelModal = false;
        $this->dispatch('showSuccessModal', 'El canal ha sido guardado correctamente.');

        $this->reset('name', 'description', 'type', 'requires_premium', 'is_active', 'max_members', 'editingChannel');
    }

    public function render()
    {
        $channels = ChatChannel::withCount('participants')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->paginate(10);

        $totalChannels = ChatChannel::count();
        $activeChannels = ChatChannel::whereHas('participants')->count(); // Example: channels with at least one participant
        $newChannelsToday = ChatChannel::whereDate('created_at', today())->count();
        $emptyChannels = ChatChannel::doesntHave('participants')->count();

        return view('admin_panel.chat.livewire.channel-table', [
            'channels' => $channels,
            'totalChannels' => $totalChannels,
            'activeChannels' => $activeChannels,
            'newChannelsToday' => $newChannelsToday,
            'emptyChannels' => $emptyChannels,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function viewChannel($channelId)
    {
        $this->selectedChannel = ChatChannel::with('participants')->find($channelId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->selectedChannel = null;
        $this->showModal = false;
    }

    public function confirmChannelDeletion($channelId)
    {
        $this->dispatch('showConfirmationModal',
            title: 'Eliminar Canal',
            message: '¿Estás seguro de que quieres eliminar este canal? Esta acción no se puede deshacer.',
            confirmAction: 'delete-channel',
            params: ['channelId' => $channelId]
        );
    }

    #[On('delete-channel')]
    public function deleteChannel(int $channelId)
    {
        $channel = ChatChannel::find($channelId);
        if ($channel) {
            $channel->delete();
        }

        $this->dispatch('showSuccessModal', 'El canal ha sido eliminado correctamente.');

        $this->resetPage();
        $this->dispatch('channel-deleted')->self();
    }
}
