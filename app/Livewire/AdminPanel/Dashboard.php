<?php

namespace App\Livewire\AdminPanel;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout;
use App\Models\Post;
use App\Models\ChatMessage;
use Illuminate\Support\Carbon;
use Laravel\Cashier\Subscription;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public $monthlyRevenue = 0;
    public $activeUsers = 0;
    public $premiumSubscribers = 0;
    public $alertsSent = 2156; // Still hardcoded as per instruction
    public $chatMessagesLast24h = 0;
    public $postsThisWeek = 0;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        $this->dispatch('data-refreshed');
        session()->flash('message', 'Dashboard data refreshed successfully!');
    }

    private function loadDashboardData()
    {
        $this->activeUsers = User::count();
        $this->premiumSubscribers = Subscription::where('stripe_status', 'active')->count();

        // Calculate Monthly Revenue
        $this->monthlyRevenue = 0;
        $activeSubscriptions = Subscription::with('items')->where('stripe_status', 'active')->get();

        foreach ($activeSubscriptions as $subscription) {
            foreach ($subscription->items as $item) {
                // Assuming stripe_price on items table corresponds to a price ID on plans table
                $plan = \App\Models\SubscriptionPlan::where('stripe_monthly_price_id', $item->stripe_price)
                                                    ->orWhere('stripe_yearly_price_id', $item->stripe_price)
                                                    ->first();

                if ($plan) {
                    if ($plan->stripe_monthly_price_id == $item->stripe_price) {
                        $this->monthlyRevenue += $plan->monthly_price;
                    } elseif ($plan->stripe_yearly_price_id == $item->stripe_price) {
                        $this->monthlyRevenue += $plan->yearly_price / 12;
                    }
                }
            }
        }
        $this->monthlyRevenue = round($this->monthlyRevenue);

        $this->chatMessagesLast24h = ChatMessage::where('created_at', '>=', Carbon::now()->subDay())->count();
        $this->postsThisWeek = Post::where('created_at', '>=', Carbon::now()->startOfWeek())->count();
    }

    public function render()
    {
        return view('admin_panel.dashboard', [
            'monthlyRevenue' => $this->monthlyRevenue,
            'activeUsers' => $this->activeUsers,
            'premiumSubscribers' => $this->premiumSubscribers,
            'alertsSent' => $this->alertsSent,
            'chatMessagesLast24h' => $this->chatMessagesLast24h,
            'postsThisWeek' => $this->postsThisWeek,
        ]);
    }
}
