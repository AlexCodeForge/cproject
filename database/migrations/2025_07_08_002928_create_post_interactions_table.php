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
        Schema::create('post_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['like', 'comment', 'share', 'bookmark', 'view'])->default('like');
            $table->text('content')->nullable(); // For comments
            $table->foreignId('parent_id')->nullable()->constrained('post_interactions')->onDelete('cascade'); // For comment replies
            $table->json('metadata')->nullable(); // Additional data (share platform, etc.)
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('post_id');
            $table->index('user_id');
            $table->index('type');
            $table->index(['post_id', 'type']);
            $table->index(['user_id', 'type']);
            $table->index('parent_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_interactions');
    }
};
