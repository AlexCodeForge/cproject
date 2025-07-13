<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function cancel(Request $request)
    {
        $subscription = $request->user()->subscriptions()->active()->first();

        if ($subscription) {
            $subscription->cancel();
            return back()->with('success', 'Your subscription has been cancelled.');
        }

        return back()->with('error', 'No active subscription found to cancel.');
    }
}
