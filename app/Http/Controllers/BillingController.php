<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function cancel(Request $request)
    {
        $request->user()->subscription('default')->cancel();

        return back()->with('success', 'Your subscription has been cancelled.');
    }
}
