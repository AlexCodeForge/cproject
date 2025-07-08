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
        Schema::create('trading_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Alert creator
            $table->string('symbol'); // Stock/crypto symbol
            $table->string('company_name')->nullable();
            $table->enum('alert_type', ['buy', 'sell', 'hold', 'watch'])->default('buy');
            $table->enum('market_type', ['stocks', 'options', 'crypto', 'forex', 'commodities'])->default('stocks');
            $table->decimal('entry_price', 10, 4)->nullable();
            $table->decimal('target_price', 10, 4)->nullable();
            $table->decimal('stop_loss', 10, 4)->nullable();
            $table->decimal('current_price', 10, 4)->nullable();
            $table->text('analysis')->nullable();
            $table->text('reasoning')->nullable();
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('medium');
            $table->enum('time_frame', ['intraday', 'swing', 'position'])->default('swing');
            $table->enum('status', ['active', 'triggered', 'closed', 'expired', 'cancelled'])->default('active');
            $table->decimal('actual_entry_price', 10, 4)->nullable();
            $table->decimal('actual_exit_price', 10, 4)->nullable();
            $table->decimal('profit_loss', 10, 4)->nullable();
            $table->decimal('profit_loss_percentage', 5, 2)->nullable();
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('triggered_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('followers_count')->default(0);
            $table->json('technical_indicators')->nullable(); // RSI, MACD, etc.
            $table->string('chart_image')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('symbol');
            $table->index('alert_type');
            $table->index('market_type');
            $table->index('status');
            $table->index('is_premium');
            $table->index('user_id');
            $table->index(['status', 'created_at']);
            $table->index('risk_level');
            $table->index('time_frame');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_alerts');
    }
};
