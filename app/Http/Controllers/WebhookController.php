<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends CashierWebhookController
{
    /**
     * Handle a Stripe webhook call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request): Response
    {
        $payload = $request->all();
        $method = 'handle' . str_replace('_', '', ucwords(str_replace('.', '_', $payload['type']), '_'));

        if (method_exists($this, $method)) {
            Log::info('Found custom handler for webhook type: ' . $payload['type']);
            return $this->{$method}($payload);
        }

        // If no custom handler exists, let the parent controller handle it.
        // This is crucial for Cashier's internal operations.
        return parent::handleWebhook($request);
    }

    /**
     * Handle checkout session completed.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCheckoutSessionCompleted(array $payload): Response
    {
        Log::info('Handling checkout.session.completed webhook.');
        $session = $payload['data']['object'];

        // Use the client_reference_id to find the user.
        // Cashier automatically sets this to the user's ID.
        if (empty($session['client_reference_id'])) {
            Log::error('client_reference_id not found in checkout.session.completed webhook.', ['session_id' => $session['id']]);
            return new Response('Webhook Error: Missing client_reference_id', 400);
        }

        $user = User::find($session['client_reference_id']);

        if ($user) {
            Log::info('User found via client_reference_id, assigning premium role.', ['user_id' => $user->id]);
            $user->removeRole('free');
            $user->assignRole('premium');
            Log::info('Premium role assigned.', ['user_id' => $user->id]);
        } else {
            Log::warning('User not found for completed checkout session using client_reference_id.', [
                'client_reference_id' => $session['client_reference_id']
            ]);
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle customer subscription deleted.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionDeleted(array $payload): Response
    {
        Log::info('Handling customer.subscription.deleted webhook.');
        $subscription_id = $payload['data']['object']['id'];

        $subscription = \Laravel\Cashier\Subscription::where('stripe_id', $subscription_id)->first();

        if ($subscription) {
            // Mark the subscription as canceled in the database.
            // This sets the `ends_at` timestamp to now.
            $subscription->markAsCanceled();

            // Also explicitly update the status for clarity in the database.
            $subscription->update(['stripe_status' => 'canceled']);

            $user = $subscription->user;
            if ($user) {
                Log::info('Subscription deleted, changing user role to free.', ['user_id' => $user->id]);
                $user->removeRole('premium');
                $user->assignRole('free');
            }
            Log::info('Local subscription record updated to canceled.', ['subscription_id' => $subscription->id]);
        }

        return new Response('Webhook Handled', 200);
    }
}
