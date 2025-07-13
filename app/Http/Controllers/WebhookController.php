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

        if (isset($session['subscription'])) {
            try {
                $stripeSubscription = \Stripe\Subscription::retrieve($session['subscription']);

                if (isset($session['client_reference_id'])) {
                    $user = User::find($session['client_reference_id']);

                    if ($user) {
                        // Correctly get the price object from the subscription items
                        $price = $stripeSubscription->items->data[0]->price;

                        $user->subscriptions()->updateOrCreate(
                            ['stripe_id' => $stripeSubscription->id],
                            [
                                // This is the fix: use 'type' column instead of 'name'
                                'type' => $price->nickname ?? 'default',
                                'stripe_status' => $stripeSubscription->status,
                                'stripe_price' => $price->id,
                                'quantity' => $stripeSubscription->quantity,
                                'trial_ends_at' => null,
                                'ends_at' => null,
                            ]
                        );

                        Log::info('User found, assigning premium role.', ['user_id' => $user->id]);
                        $user->removeRole('free');
                        $user->assignRole('premium');
                        Log::info('Premium role assigned and subscription synced.', ['user_id' => $user->id]);

                        return new Response('Webhook Handled', 200);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error processing checkout.session.completed webhook: ' . $e->getMessage(), [
                    'session_id' => $session['id'],
                    'exception' => $e
                ]);
                return new Response('Webhook Error: ' . $e->getMessage(), 500);
            }
        }

        // Fallback for non-subscription checkouts or if something goes wrong
        if (empty($session['client_reference_id'])) {
            Log::error('client_reference_id not found in webhook.', ['session_id' => $session['id']]);
            return new Response('Webhook Error: Missing client_reference_id', 400);
        }

        $user = User::find($session['client_reference_id']);

        if ($user) {
            Log::info('User found (no subscription sync), assigning premium role.', ['user_id' => $user->id]);
            $user->removeRole('free');
            $user->assignRole('premium');
            Log::info('Premium role assigned.', ['user_id' => $user->id]);
        } else {
            Log::warning('User not found for completed checkout session.', [
                'client_reference_id' => $session['client_reference_id']
            ]);
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle customer subscription updated.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionUpdated(array $payload): Response
    {
        Log::info('Handling customer.subscription.updated webhook.');

        // Let Cashier do its default sync logic first.
        parent::handleCustomerSubscriptionUpdated($payload);

        // You can add any additional custom logic here if needed.
        // For now, we'll just log that it was handled.
        Log::info('Parent handler for customer.subscription.updated executed.');

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

        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $subscriptionId = $payload['data']['object']['id'];

            $user->subscriptions->filter(function ($subscription) use ($subscriptionId) {
                return $subscription->stripe_id === $subscriptionId;
            })->each(function ($subscription) use ($user) {
                Log::info('Marking local subscription as canceled.', ['subscription_id' => $subscription->id, 'user_id' => $user->id]);

                $subscription->markAsCanceled();

                Log::info('Subscription definitely canceled, changing user role to free.', ['user_id' => $user->id]);
                $user->removeRole('premium');
                $user->assignRole('free');
            });

            return new Response('Webhook Handled', 200);
        }

        Log::warning('User not found for customer.subscription.deleted webhook.', [
            'customer_id' => $payload['data']['object']['customer'],
        ]);

        return new Response('Webhook Handled but user not found', 200);
    }

    /**
     * Get the user for the given Stripe customer ID.
     *
     * @param  string|null  $stripeId
     * @return \Laravel\Cashier\Billable|null
     */
    protected function getUserByStripeId($stripeId)
    {
        return \Laravel\Cashier\Cashier::findBillable($stripeId);
    }
}
