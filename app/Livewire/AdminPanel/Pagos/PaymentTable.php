<?php

namespace App\Livewire\AdminPanel\Pagos;

use Livewire\Component;
use Livewire\WithPagination;
use Laravel\Cashier\Subscription;

class PaymentTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    public function render()
    {
        $query = Subscription::with('user')
            ->where(function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            });

        if ($this->status) {
            $query->where('stripe_status', $this->status);
        }

        $subscriptions = $query->latest()->paginate(15);

        // Stats
        $totalSubscriptions = Subscription::count();
        $activeSubscriptions = Subscription::where('stripe_status', 'active')->count();
        $canceledSubscriptions = Subscription::where('stripe_status', 'canceled')->count();
        $newSubscriptionsToday = Subscription::whereDate('created_at', today())->count();

        return view('livewire.admin-panel.pagos.payment-table', [
            'subscriptions' => $subscriptions,
            'totalSubscriptions' => $totalSubscriptions,
            'activeSubscriptions' => $activeSubscriptions,
            'canceledSubscriptions' => $canceledSubscriptions,
            'newSubscriptionsToday' => $newSubscriptionsToday,
        ]);
    }
}
