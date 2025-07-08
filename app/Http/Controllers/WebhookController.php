<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class WebhookController extends CashierWebhookController
{
    // You can override methods here if you need to handle webhooks in a custom way.
    // For example, to handle a specific event:
    //
    // protected function handleInvoicePaymentSucceeded(array $payload)
    // {
    //     // Handle the event
    // }
}
