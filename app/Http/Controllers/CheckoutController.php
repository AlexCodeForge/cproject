<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        Log::info('Checkout process started.', ['user_id' => $request->user()->id]);

        $planSlug = $request->input('plan_slug', 'premium'); // Default to 'premium'
        $frequency = $request->input('plan'); // 'monthly' or 'yearly'

        Log::info('Plan details for checkout.', ['plan_slug' => $planSlug, 'frequency' => $frequency]);

        $plan = SubscriptionPlan::where('slug', $planSlug)->first();

        if (!$plan) {
            Log::error('Invalid plan selected during checkout.', ['plan_slug' => $planSlug]);
            return back()->with('error', 'Invalid plan selected.');
        }

        $priceId = '';
        if ($frequency === 'monthly') {
            $priceId = $plan->stripe_monthly_price_id;
        } elseif ($frequency === 'yearly') {
            $priceId = $plan->stripe_yearly_price_id;
        }

        if (empty($priceId)) {
            Log::error('Stripe Price ID not found for selected plan.', ['plan_slug' => $planSlug, 'frequency' => $frequency]);
            return back()->with('error', 'Pricing information not found for the selected plan.');
        }

        $checkout = $request->user()
            ->newSubscription('default', $priceId)
            ->checkout([
                'success_url' => route('dashboard'),
                'cancel_url' => route('pricing'),
                'client_reference_id' => $request->user()->id,
            ]);

        Log::info('Redirecting user to Stripe Checkout.', ['user_id' => $request->user()->id, 'price_id' => $priceId]);

        return redirect($checkout->url);
    }
}
