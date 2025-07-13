<?php

use Illuminate\Support\Facades\Route;

Route::post('/stripe/webhook', [App\Http\Controllers\WebhookController::class, 'handleWebhook'])->name('cashier.webhook');
