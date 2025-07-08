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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_channel_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_message_id')->nullable()->constrained('chat_messages')->onDelete('cascade'); // For threading
            $table->text('message');
            $table->enum('type', ['text', 'image', 'file', 'system', 'alert'])->default('text');
            $table->json('attachments')->nullable(); // File attachments
            $table->json('mentions')->nullable(); // Array of mentioned user IDs
            $table->boolean('is_edited')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('reactions_count')->default(0);
            $table->integer('replies_count')->default(0);
            $table->json('reactions')->nullable(); // Array of reactions with user IDs
            $table->timestamps();

            // Indexes for performance
            $table->index('chat_channel_id');
            $table->index('user_id');
            $table->index('parent_message_id');
            $table->index(['chat_channel_id', 'created_at']);
            $table->index('type');
            $table->index('is_pinned');
            $table->index('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
