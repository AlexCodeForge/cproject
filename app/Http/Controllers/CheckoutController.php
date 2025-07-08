<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $plan = $request->input('plan');
        $priceId = '';

        if ($plan === 'monthly') {
            $priceId = env('MONTHLY_PLAN_ID');
        } elseif ($plan === 'yearly') {
            $priceId = env('YEARLY_PLAN_ID');
        }

        if (empty($priceId)) {
            return back()->with('error', 'Invalid plan selected.');
        }

        return $request->user()
            ->newSubscription('default', $priceId)
            ->checkout([
                'success_url' => route('dashboard'),
                'cancel_url' => route('pricing'),
            ]);
    }
}
