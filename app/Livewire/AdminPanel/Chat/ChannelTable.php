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
    public array $userMessageCounts = []; // New property for message counts

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

    protected function messages()
    {
        return [
            'name.required' => 'El nombre del canal es obligatorio.',
            'name.string' => 'El nombre del canal debe ser una cadena de texto.',
            'name.max' => 'El nombre del canal no debe exceder los :max caracteres.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'type.required' => 'El tipo de canal es obligatorio.',
            'type.in' => 'El tipo de canal seleccionado no es válido.',
            'requires_premium.boolean' => 'El valor de "requiere premium" debe ser verdadero o falso.',
            'is_active.boolean' => 'El valor de "activo" debe ser verdadero o falso.',
            'max_members.integer' => 'El número máximo de miembros debe ser un número entero.',
            'max_members.min' => 'El número máximo de miembros debe ser al menos :min.',
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
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('showErrorModal', message: collect($e->errors())->flatten()->toArray(), title: 'Error de Validación');
            throw $e; // Re-throw the exception to ensure individual field errors are displayed
        }

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
        $channels = ChatChannel::withCount('chatParticipants') // Changed from 'participants'
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->paginate(10);

        $totalChannels = ChatChannel::count();
        $activeChannels = ChatChannel::whereHas('chatParticipants')->count(); // Changed from 'participants'
        $newChannelsToday = ChatChannel::whereDate('created_at', today())->count();
        $emptyChannels = ChatChannel::doesntHave('chatParticipants')->count(); // Changed from 'participants'

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
        // Eager load chatParticipants and messages for the selected channel
        $this->selectedChannel = ChatChannel::with(['chatParticipants.user', 'messages'])->find($channelId);
        $this->userMessageCounts = []; // Reset counts for the new channel

        if ($this->selectedChannel) {
            \Illuminate\Support\Facades\Log::info("Viewing channel: {$this->selectedChannel->name} (ID: {$this->selectedChannel->id})");

            // Calculate message counts for each user in this channel
            $this->userMessageCounts = $this->selectedChannel->messages
                                          ->groupBy('user_id')
                                          ->map->count()
                                          ->toArray();

            foreach ($this->selectedChannel->chatParticipants as $participant) {
                \Illuminate\Support\Facades\Log::info("  Participant ID: {$participant->id}, User ID: " . ($participant->user_id ?? 'NULL') . ", User Exists: " . ($participant->user ? 'Yes' : 'No') . ", Messages: " . ($this->userMessageCounts[$participant->user_id] ?? 0));
            }
        } else {
            \Illuminate\Support\Facades\Log::warning("Attempted to view non-existent channel: {$channelId}");
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->selectedChannel = null;
        $this->showModal = false;
        $this->userMessageCounts = []; // Clear counts when modal closes
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
