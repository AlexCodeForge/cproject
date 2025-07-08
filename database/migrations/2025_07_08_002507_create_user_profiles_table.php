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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->string('twitter_handle')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->enum('trading_experience', ['beginner', 'intermediate', 'advanced', 'expert'])->default('beginner');
            $table->json('trading_interests')->nullable(); // Array of interests like ['options', 'crypto', 'forex']
            $table->decimal('portfolio_value', 15, 2)->nullable();
            $table->boolean('public_profile')->default(true);
            $table->boolean('show_portfolio')->default(false);
            $table->string('timezone')->default('UTC');
            $table->string('language')->default('en');
            $table->json('notification_preferences')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('user_id');
            $table->index('trading_experience');
            $table->index('public_profile');
            $table->index('last_active_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
