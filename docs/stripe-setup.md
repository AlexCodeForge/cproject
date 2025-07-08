# Stripe & Laravel Cashier Setup Guide

This guide provides the necessary steps to configure Stripe with Laravel Cashier for handling subscription billing in this project.

## 1. Environment Variables (`.env`)

You need to add the following keys to your `.env` file. You can get these values from your Stripe Dashboard.

```dotenv
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
MONTHLY_PLAN_ID=price_...
YEARLY_PLAN_ID=price_...
```

### Finding Your Keys:

-   **`STRIPE_KEY` & `STRIPE_SECRET`**:
    1.  Go to your [Stripe Dashboard](https://dashboard.stripe.com/).
    2.  Navigate to **Developers > API keys**.
    3.  Copy the "Publishable key" (`pk_test_...`) and "Secret key" (`sk_test_...`).
        > **Note:** Use your test keys for development.

-   **`MONTHLY_PLAN_ID` & `YEARLY_PLAN_ID`**:
    1.  In the Stripe Dashboard, go to **Products**.
    2.  Create two products (e.g., "Premium Monthly" and "Premium Yearly").
    3.  For each product, add a price. This will create a Price ID (`price_...`).
    4.  Copy these Price IDs into your `.env` file.

## 2. Webhook Configuration

Laravel Cashier uses webhooks to receive events from Stripe. Because your local development server (`.test` domain) is not accessible from the public internet, you cannot use its URL directly for webhooks.

The recommended and most secure method for testing webhooks locally is to use the **Stripe CLI**.

1.  **Install the Stripe CLI:**
    Follow the official instructions to [install the Stripe CLI](https://stripe.com/docs/stripe-cli) on your operating system.

2.  **Login to your Stripe Account:**
    In your terminal, run the following command and follow the prompts to link the CLI to your Stripe account:
    ```bash
    stripe login
    ```

3.  **Listen for Events:**
    In your terminal, from your project directory, run the following command. This tells Stripe to forward events to your local application.
    ```bash
    stripe listen --forward-to http://option-rocket.test:81/stripe/webhook
    ```
    Remember to use the correct local URL for your Laragon setup [[memory:682663]].

4.  **Use the Local Webhook Secret:**
    When you run the `stripe listen` command, it will output a webhook signing secret that looks like `whsec_...`. **This is a temporary secret for your local session ONLY.**
    
    -   Copy this new `whsec_...` secret.
    -   Paste it into the `STRIPE_WEBHOOK_SECRET` variable in your `.env` file.
    
    > **Important:** This secret is different from the one you create in the Stripe Dashboard for your production endpoint. You must use the one provided by the `stripe listen` command for local testing to work.

## 3. Automated Invoice Emailing

To ensure customers automatically receive email receipts after a successful payment:

1.  Go to your [Stripe Dashboard](https://dashboard.stripe.com/).
2.  Navigate to **Settings > Billing > Invoicing settings**.
3.  Under the **Emails** section, ensure that **Email finalized invoices to customers** is turned **ON**.
4.  You can also customize the email templates from this page to match your brand.

Once these steps are completed, your application will be fully configured to handle Stripe subscriptions with Laravel Cashier. 
