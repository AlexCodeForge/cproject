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
        Schema::create('chat_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['public', 'premium', 'private', 'direct'])->default('public');
            $table->string('color')->nullable(); // Hex color for UI
            $table->string('icon')->nullable(); // Icon class or path
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_premium')->default(false);
            $table->integer('max_members')->nullable();
            $table->integer('members_count')->default(0);
            $table->integer('messages_count')->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->json('moderators')->nullable(); // Array of user IDs
            $table->json('settings')->nullable(); // Channel-specific settings
            $table->timestamp('last_message_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('slug');
            $table->index('type');
            $table->index('is_active');
            $table->index('requires_premium');
            $table->index('created_by');
            $table->index('last_message_at');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_channels');
    }
};
