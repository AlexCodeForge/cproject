<?php

namespace App\Livewire\AdminPanel\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserTable extends Component
{
    use WithPagination;

    public $search = '';
    public $role = '';
    public $selectedUser;
    public $showModal = false;

    protected $listeners = ['userUpdated' => '$refresh'];

    public function render()
    {
        $users = User::with('roles', 'profile')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->role, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->role);
                });
            })
            ->paginate(10);

        $totalUsers = User::count();
        $premiumUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'premium');
        })->count();
        $activeUsers = User::whereNotNull('email_verified_at')->count();
        $newUsersToday = User::whereDate('created_at', today())->count();

        return view('admin_panel.users.livewire.user_table', [
            'users' => $users,
            'roles' => Role::all(),
            'totalUsers' => $totalUsers,
            'premiumUsers' => $premiumUsers,
            'activeUsers' => $activeUsers,
            'newUsersToday' => $newUsersToday,
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

    public function viewUser($userId)
    {
        $this->selectedUser = User::with(['profile', 'roles', 'posts', 'subscriptions'])->find($userId);
        $this->showModal = true;
    }

    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            session()->flash('message', 'Estado del usuario actualizado correctamente.');
        } else {
            $user->update(['email_verified_at' => now()]);
            session()->flash('message', 'User email verified.');
        }

        $this->dispatch('userUpdated');
    }

    public function closeModal()
    {
        $this->selectedUser = null;
        $this->showModal = false;
    }
}
