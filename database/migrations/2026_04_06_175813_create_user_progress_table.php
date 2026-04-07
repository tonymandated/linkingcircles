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
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_file_id')->constrained('lesson_files')->cascadeOnDelete();

            // Progress tracking
            $table->unsignedSmallInteger('percent_complete')->default(0); // 0-100
            $table->unsignedBigInteger('seconds_watched')->default(0); // For videos/audio
            $table->unsignedInteger('lines_read')->default(0); // For documents

            // Status tracking
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();

            // Timestamps
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();

            // Indexes for efficient queries
            $table->index(['user_id', 'lesson_file_id']);
            $table->index('user_id');
            $table->index('lesson_file_id');

            // Ensure one progress record per user per file
            $table->unique(['user_id', 'lesson_file_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
