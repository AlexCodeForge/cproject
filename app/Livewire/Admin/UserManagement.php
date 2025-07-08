<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedRole = '';
    public $selectedUser = null;
    public $showModal = false;
    public $roles = [];

    protected $listeners = ['userUpdated' => '$refresh'];

    public function mount()
    {
        $this->roles = Role::all();
    }

    public function render()
    {
        $users = User::with('roles')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedRole, function ($query) {
                $query->role($this->selectedRole);
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users,
            'availableRoles' => $this->roles
        ])->layout('layouts.app', [
            'isAdmin' => true
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedRole()
    {
        $this->resetPage();
    }

    public function editUser($userId)
    {
        $this->selectedUser = User::findOrFail($userId);
        $this->showModal = true;
    }

    public function updateUserRole($userId, $roleName)
    {
        $user = User::findOrFail($userId);

        // Remove all current roles and assign new one
        $user->syncRoles([$roleName]);

        session()->flash('message', "User role updated to {$roleName} successfully!");
        $this->dispatch('userUpdated');
    }

    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            session()->flash('message', 'User email verification removed.');
        } else {
            $user->update(['email_verified_at' => now()]);
            session()->flash('message', 'User email verified.');
        }

        $this->dispatch('userUpdated');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedUser = null;
    }
}
