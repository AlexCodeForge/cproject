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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['registered', 'attended', 'cancelled', 'no_show'])->default('registered');
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('attended_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->json('registration_data')->nullable(); // Additional registration info
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            $table->decimal('rating', 3, 2)->nullable(); // Event rating by user
            $table->text('feedback')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate registrations
            $table->unique(['event_id', 'user_id']);

            // Indexes
            $table->index('event_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('registered_at');
            $table->index('attended_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
