# Understanding the Subscription Lifecycle

This document explains how user subscriptions are managed in the application, detailing the interaction between Laravel Cashier, Stripe webhooks, and the user role system.

## The `subscriptions` Table and `ends_at`

A common point of confusion with Laravel Cashier is the `ends_at` column in the `subscriptions` table. Its behavior is intentional and key to managing subscriptions correctly.

-   **Active Subscriptions**: For an active, auto-renewing subscription, `ends_at` is `NULL`. The subscription renews automatically at the end of each billing cycle.
-   **Canceled Subscriptions**: When a user cancels, `ends_at` is set to the end of the current paid-for period. The user remains a "premium" user until this date. This is known as the "grace period."

To get the correct expiration date regardless of status, we use a custom accessor `getSubscriptionEndsAtAttribute()` in the `User` model. This function intelligently checks the subscription status and returns the correct end date, whether it's the end of a grace period or the end of the current billing cycle for an active subscription.

## The Checkout Process & `client_reference_id`

For the role-assignment system to work, it is **critical** that we can identify which user completed the checkout. We accomplish this by passing the user's ID to Stripe during the checkout session creation.

In `app/Http/Controllers/CheckoutController.php`, we explicitly set the `client_reference_id`:

```php
// In CheckoutController.php

$checkout = $request->user()
    ->newSubscription($plan->slug, $priceId)
    ->checkout([
        'success_url' => route('dashboard'),
        'cancel_url' => route('pricing'),
        'client_reference_id' => $request->user()->id, // This is essential!
    ]);

return redirect($checkout->url);
```

By setting `client_reference_id` to the user's ID, we ensure this information is included in the webhook payload Stripe sends back to our application.

## Webhook Handling for Role Management

The `app/Http/Controllers/WebhookController.php` is the core of our automated role management system. It listens for specific events from Stripe and acts on them.

### 1. `handleCheckoutSessionCompleted` - Assigning 'premium' Role

-   **Event**: `checkout.session.completed`
-   **Action**: This is the most important webhook. When a user successfully pays, this event is triggered.
-   **Logic**:
    1.  The handler receives the webhook payload from Stripe.
    2.  It retrieves the `client_reference_id` that we set during checkout.
    3.  It finds the `User` in the database using this ID.
    4.  It removes the user's `'free'` role and assigns the `'premium'` role.
    5.  This grants the user immediate access to premium features.

### 2. `handleCustomerSubscriptionDeleted` - Downgrading to 'free' Role

-   **Event**: `customer.subscription.deleted`
-   **Action**: This event is sent by Stripe when a canceled subscription's grace period has officially ended.
-   **Logic**:
    1.  The handler finds the user associated with the subscription.
    2.  It removes the user's `'premium'` role and assigns the `'free'` role.
    3.  This revokes the user's access to premium features, completing the subscription lifecycle.

## Debugging Workflow

If role changes are not happening as expected:

1.  **Check the Logs**: The first place to look is `storage/logs/laravel.log`. Our webhook handlers have detailed logging.
2.  **Look for Errors**: Specifically, search for `client_reference_id not found`. If you see this, it means the ID is not being passed correctly during checkout.
3.  **Use Stripe CLI**: For local development, use the Stripe CLI to listen for and forward webhooks (`stripe listen --forward-to <your-local-url>/stripe/webhook`). This allows you to see the exact webhook payloads and HTTP status codes your application is returning, which is invaluable for debugging. A `500` status code indicates a server error in your webhook handler. 

### The Complete Subscription Flow

1.  **Initiate Checkout**: A user selects a plan and is redirected to Stripe's secure checkout page. We pass `'client_reference_id' => $user->id` during this step.
2.  **Successful Payment & Redirect**: After a successful payment, Stripe redirects the user to our custom "Thank You" page (`/subscription-thank-you`), providing immediate positive feedback.
3.  **Webhook: `checkout.session.completed`**: Almost simultaneously, Stripe sends this webhook. Our `WebhookController` catches it, finds the user via the `client_reference_id`, and assigns them the `premium` role.
4.  **Webhook: Other Events**: The parent `CashierWebhookController` handles other events in the background, such as updating the local subscription status from `incomplete` to `active`.
5.  **Cancellation (User-Initiated)**: If a user cancels via the profile page, Cashier sets the `ends_at` date. The user enjoys premium features until this date (grace period). 
