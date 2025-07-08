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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('post_category_id')->nullable(); // Changed from foreignId
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->json('images')->nullable(); // Additional images
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->json('tags')->nullable(); // Array of tags
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('shares_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->integer('reading_time')->nullable(); // in minutes
            $table->decimal('difficulty_level', 2, 1)->nullable(); // 1.0 to 5.0
            $table->timestamps();

            // Indexes for performance
            $table->index(['status', 'published_at']);
            $table->index('user_id');
            $table->index('post_category_id');
            $table->index('is_premium');
            $table->index('is_featured');
            $table->index('views_count');
            $table->index('likes_count');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
