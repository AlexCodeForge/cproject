<?php

namespace App\Livewire\UserPanel;

use Livewire\Component;
use App\Models\ChatMessage;
use App\Models\TradingAlert;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $openPositions = 0;
    public $totalTrades = 0;
    public $wins = 0;
    public $losses = 0;
    public $dailyPnL = 0;
    public $newAlerts = 0;
    public $unreadMessages = 0;
    public $featuredPosts = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    private function loadDashboardData()
    {
        // Mock data for now - replace with real data later
        $this->openPositions = 4;
        $this->dailyPnL = 1280.50;

        // TODO: Implement a more robust way to count new/unread items.
        // This is a temporary implementation for the dashboard view.
        $this->newAlerts = TradingAlert::where('status', 'active')->count();
        $this->unreadMessages = ChatMessage::whereDate('created_at', today())->count();

        // Fetch latest 3 featured posts
        $this->featuredPosts = Post::where('is_featured', true)
                                   ->where('status', 'published')
                                   ->latest('published_at')
                                   ->take(3)
                                   ->get();
    }

    public function render()
    {
        return view('user_panel.dashboard', [
            'openPositions' => $this->openPositions,
            'totalTrades' => $this->totalTrades,
            'wins' => $this->wins,
            'losses' => $this->losses,
            'dailyPnL' => $this->dailyPnL,
            'newAlerts' => $this->newAlerts,
            'unreadMessages' => $this->unreadMessages,
            'featuredPosts' => $this->featuredPosts,
        ]);
    }
}
