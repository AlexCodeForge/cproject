<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_alert_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('trading_alert_id')->constrained()->onDelete('cascade');
            $table->enum('notification_method', ['email', 'sms', 'push', 'in_app'])->default('in_app');
            $table->boolean('is_active')->default(true);
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->json('preferences')->nullable(); // Custom alert preferences
            $table->timestamps();

            // Unique constraint to prevent duplicate subscriptions
            $table->unique(['user_id', 'trading_alert_id', 'notification_method'], 'user_alert_notification_unique');

            // Indexes
            $table->index('user_id');
            $table->index('trading_alert_id');
            $table->index('notification_method');
            $table->index('is_active');
            $table->index('subscribed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_alert_subscriptions');
    }
};
