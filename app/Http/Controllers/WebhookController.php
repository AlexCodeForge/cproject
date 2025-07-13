<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class WebhookController extends CashierWebhookController
{
    /**
     * Handle a Stripe webhook call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Stripe webhook call received.');

        $payload = json_decode($request->getContent(), true);

        // Log the entire payload for detailed debugging
        Log::debug('Stripe Webhook Payload:', $payload);

        $method = 'handle' . str_replace('_', '', ucwords(str_replace('.', '_', $payload['type']), '_'));

        if (method_exists($this, $method)) {
            Log::info('Found custom handler for webhook type: ' . $payload['type']);
            $response = $this->{$method}($payload);

            return $response;
        }

        Log::info('No custom handler found. Passing to parent Cashier controller.');
        return parent::handleWebhook($request);
    }
}
