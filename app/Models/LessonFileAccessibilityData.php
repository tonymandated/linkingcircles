<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'lesson_file_id',
    'transcript_file_path',
    'transcript_disk',
    'transcript_preview',
    'transcript_language',
    'sign_language_video_path',
    'sign_language_video_disk',
    'extended_description',
    'audio_description',
    'document_structure',
    'is_searchable',
    'accessibility_metadata',
    'accessibility_completed_at',
])]
class LessonFileAccessibilityData extends Model
{
    protected function casts(): array
    {
        return [
            'is_searchable' => 'boolean',
            'document_structure' => 'json',
            'accessibility_metadata' => 'json',
            'accessibility_completed_at' => 'datetime',
        ];
    }

    public function lessonFile(): BelongsTo
    {
        return $this->belongsTo(LessonFile::class);
    }

    /**
     * Check if all required accessibility data is complete
     */
    public function isComplete(): bool
    {
        $file = $this->lessonFile;

        if ($file->isVideo() || $file->isAudio()) {
            return ! empty($this->transcript_file_path);
        }

        if ($file->isImage()) {
            return ! empty($file->alt_text);
        }

        return true;
    }

    /**
     * Mark accessibility as complete
     */
    public function markComplete(): void
    {
        $this->update(['accessibility_completed_at' => now()]);
    }
}
