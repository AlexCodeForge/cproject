<?php

namespace App\Livewire\AdminPanel\Pagos;

use Livewire\Component;
use Livewire\WithPagination;
use Laravel\Cashier\Subscription;
use Livewire\Attributes\On;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;

class PaymentTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $totalRevenueThisMonth = 0;

    #[On('cancelSubscription')]
    public function cancelSubscription($subscriptionId)
    {
        $subscription = Subscription::find($subscriptionId);
        if ($subscription && $subscription->active() && !$subscription->canceled()) {
            $subscription->cancel();
            $this->dispatch('showSuccessModal', 'Suscripción cancelada exitosamente.');
        } else {
            session()->flash('error', 'No se pudo cancelar la suscripción.');
        }
    }

    public function render()
    {
        $query = Subscription::with('user', 'items') // Eager load items for price calculation
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

        // Dynamically add the end date and amount to each subscription for display
        $subscriptions->getCollection()->transform(function ($subscription) {
            if ($subscription->active() && !$subscription->canceled()) {
                try {
                    $stripeSubscription = $subscription->asStripeSubscription();
                    $subscription->dynamic_ends_at = \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end);
                } catch (\Exception $e) {
                    $subscription->dynamic_ends_at = null; // Handle API errors
                }
            } else {
                // For canceled or other statuses, use the existing ends_at
                $subscription->dynamic_ends_at = $subscription->ends_at;
            }

            // Calculate amount paid for this specific subscription
            $subscription->amount_paid = 0;
            if ($subscription->items->isNotEmpty()) {
                $item = $subscription->items->first(); // Assuming one item per subscription for simplicity
                $plan = SubscriptionPlan::where('stripe_monthly_price_id', $item->stripe_price)
                                        ->orWhere('stripe_yearly_price_id', $item->stripe_price)
                                        ->first();

                if ($plan) {
                    if ($plan->stripe_monthly_price_id == $item->stripe_price) {
                        $subscription->amount_paid = $plan->monthly_price;
                    } elseif ($plan->stripe_yearly_price_id == $item->stripe_price) {
                        $subscription->amount_paid = $plan->yearly_price;
                    }
                }
            }
            return $subscription;
        });

        // Stats
        $totalSubscriptions = Subscription::count();
        $activeSubscriptions = Subscription::where('stripe_status', 'active')->count();
        $canceledSubscriptions = Subscription::where('stripe_status', 'canceled')->count();
        // $newSubscriptionsToday = Subscription::whereDate('created_at', today())->count(); // Removed

        // Calculate total revenue for the current month
        $this->totalRevenueThisMonth = 0;
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $subscriptionsThisMonth = Subscription::with('items')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        foreach ($subscriptionsThisMonth as $subscription) {
            foreach ($subscription->items as $item) {
                $plan = SubscriptionPlan::where('stripe_monthly_price_id', $item->stripe_price)
                                        ->orWhere('stripe_yearly_price_id', $item->stripe_price)
                                        ->first();

                if ($plan) {
                    if ($plan->stripe_monthly_price_id == $item->stripe_price) {
                        $this->totalRevenueThisMonth += $plan->monthly_price;
                    } elseif ($plan->stripe_yearly_price_id == $item->stripe_price) {
                        $this->totalRevenueThisMonth += $plan->yearly_price;
                    }
                }
            }
        }
        $this->totalRevenueThisMonth = round($this->totalRevenueThisMonth, 2);


        return view('livewire.admin-panel.pagos.payment-table', [
            'subscriptions' => $subscriptions,
            'totalSubscriptions' => $totalSubscriptions,
            'activeSubscriptions' => $activeSubscriptions,
            'canceledSubscriptions' => $canceledSubscriptions,
            // 'newSubscriptionsToday' => $newSubscriptionsToday, // Removed
            'totalRevenueThisMonth' => $this->totalRevenueThisMonth,
        ]);
    }
}
