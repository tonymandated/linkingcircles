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
        Schema::create('lesson_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();

            // Basic file metadata
            $table->string('title');
            $table->string('file_name');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->string('file_path');
            $table->string('disk')->default('media');

            // Media type classification
            $table->enum('file_type', ['video', 'audio', 'image', 'document'])->nullable();

            // Description and alt text
            $table->text('description')->nullable();
            $table->text('alt_text')->nullable();

            // Media-specific metadata
            $table->integer('duration_seconds')->nullable(); // For audio/video
            $table->integer('width')->nullable(); // For images/videos
            $table->integer('height')->nullable(); // For images/videos

            // Publishing status
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Indexes for common queries
            $table->index('lesson_id');
            $table->index('uploaded_by');
            $table->index(['lesson_id', 'file_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_files');
    }
};
