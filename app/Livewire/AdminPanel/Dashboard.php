<?php

namespace App\Livewire\AdminPanel;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public $monthlyRevenue = 12847;
    public $activeUsers = 0;
    public $premiumSubscribers = 0;
    public $alertsSent = 2156;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        session()->flash('message', 'Dashboard data refreshed successfully!');
    }

    private function loadDashboardData()
    {
        // Load real data here - using mock data for some
        $this->monthlyRevenue = 12847;
        $this->activeUsers = User::count();
        $this->premiumSubscribers = User::role('premium')->count();
        $this->alertsSent = 2156;
    }

    public function render()
    {
        return view('admin_panel.dashboard', [
            'monthlyRevenue' => $this->monthlyRevenue,
            'activeUsers' => $this->activeUsers,
            'premiumSubscribers' => $this->premiumSubscribers,
            'alertsSent' => $this->alertsSent,
        ]);
    }
}
