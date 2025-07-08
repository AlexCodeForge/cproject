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
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['enrolled', 'in_progress', 'completed', 'cancelled', 'expired'])->default('enrolled');
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->decimal('progress_percentage', 5, 2)->default(0); // 0.00 to 100.00
            $table->integer('lessons_completed')->default(0);
            $table->integer('total_lessons')->default(0);
            $table->integer('time_spent_minutes')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->json('completed_lessons')->nullable(); // Array of completed lesson IDs
            $table->json('quiz_scores')->nullable(); // Quiz scores and attempts
            $table->decimal('final_score', 5, 2)->nullable(); // Final course score
            $table->boolean('certificate_issued')->default(false);
            $table->timestamp('certificate_issued_at')->nullable();
            $table->string('certificate_url')->nullable();
            $table->decimal('rating', 3, 2)->nullable(); // Course rating by user
            $table->text('feedback')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate enrollments
            $table->unique(['course_id', 'user_id']);

            // Indexes
            $table->index('course_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('enrolled_at');
            $table->index('progress_percentage');
            $table->index('completed_at');
            $table->index('last_accessed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_enrollments');
    }
};
