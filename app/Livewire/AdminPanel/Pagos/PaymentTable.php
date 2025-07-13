<?php

namespace App\Livewire\AdminPanel\Pagos;

use Livewire\Component;
use Livewire\WithPagination;
use Laravel\Cashier\Subscription;
use Livewire\Attributes\On;

class PaymentTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

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

        // Dynamically add the end date to each subscription for display
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
            return $subscription;
        });

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
