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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('content')->nullable(); // Detailed event content
            $table->string('featured_image')->nullable();
            $table->enum('type', ['webinar', 'workshop', 'conference', 'meetup', 'course'])->default('webinar');
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->decimal('price', 8, 2)->default(0);
            $table->integer('max_attendees')->nullable();
            $table->integer('registered_count')->default(0);
            $table->datetime('starts_at');
            $table->datetime('ends_at');
            $table->string('timezone')->default('UTC');
            $table->string('location')->nullable(); // Physical location or "Online"
            $table->string('meeting_url')->nullable(); // Zoom/Meet link
            $table->string('meeting_password')->nullable();
            $table->json('speakers')->nullable(); // Array of speaker data
            $table->json('agenda')->nullable(); // Event agenda/schedule
            $table->json('materials')->nullable(); // Downloadable materials
            $table->text('requirements')->nullable(); // Prerequisites
            $table->text('what_youll_learn')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('send_reminders')->default(true);
            $table->json('reminder_times')->nullable(); // Array of reminder times
            $table->integer('views_count')->default(0);
            $table->decimal('rating', 3, 2)->nullable();
            $table->integer('ratings_count')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('slug');
            $table->index('type');
            $table->index('status');
            $table->index('is_premium');
            $table->index('is_featured');
            $table->index('starts_at');
            $table->index('created_by');
            $table->index(['status', 'starts_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
