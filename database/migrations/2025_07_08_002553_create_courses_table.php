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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('content')->nullable(); // Course overview content
            $table->string('featured_image')->nullable();
            $table->string('video_trailer')->nullable(); // Course trailer video
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('beginner');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->decimal('price', 8, 2)->default(0);
            $table->integer('duration_minutes')->nullable(); // Total course duration
            $table->integer('lessons_count')->default(0);
            $table->integer('enrolled_count')->default(0);
            $table->integer('completed_count')->default(0);
            $table->json('learning_objectives')->nullable(); // What students will learn
            $table->json('prerequisites')->nullable(); // Course prerequisites
            $table->json('tools_required')->nullable(); // Tools/software needed
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->json('co_instructors')->nullable(); // Additional instructors
            $table->string('certificate_template')->nullable(); // Certificate template
            $table->boolean('has_certificate')->default(false);
            $table->decimal('rating', 3, 2)->nullable();
            $table->integer('ratings_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->json('tags')->nullable(); // Course tags
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('slug');
            $table->index('level');
            $table->index('status');
            $table->index('is_premium');
            $table->index('is_featured');
            $table->index('instructor_id');
            $table->index('rating');
            $table->index('enrolled_count');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
