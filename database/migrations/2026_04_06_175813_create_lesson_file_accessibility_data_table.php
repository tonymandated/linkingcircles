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
        Schema::create('lesson_file_accessibility_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_file_id')->constrained('lesson_files')->cascadeOnDelete();

            // Transcripts and captions for videos/audio
            $table->string('transcript_file_path')->nullable(); // Path to .vtt or .srt file
            $table->string('transcript_disk')->default('captions');
            $table->text('transcript_preview')->nullable(); // First 500 chars of transcript for preview
            $table->string('transcript_language', 10)->default('en');

            // Sign language video overlay
            $table->string('sign_language_video_path')->nullable();
            $table->string('sign_language_video_disk')->default('media');

            // Extended descriptions
            $table->text('extended_description')->nullable(); // Longer description for screen readers
            $table->text('audio_description')->nullable(); // For video: detailed audio description for visually impaired

            // Document-specific accessibility
            $table->json('document_structure')->nullable(); // Heading hierarchy, landmarks for PDFs
            $table->boolean('is_searchable')->default(true);

            // Data URIs: can also store small images/diagrams as data URIs for accessibility
            $table->json('accessibility_metadata')->nullable(); // Flexible JSON for additional metadata

            $table->timestamp('accessibility_completed_at')->nullable(); // Track when all required accessibility features added
            $table->timestamps();

            // Indexes
            $table->index('lesson_file_id');
            $table->index('transcript_language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_file_accessibility_data');
    }
};
