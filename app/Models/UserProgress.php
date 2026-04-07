<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'lesson_file_id',
    'percent_complete',
    'seconds_watched',
    'lines_read',
    'is_completed',
    'completed_at',
    'last_accessed_at',
])]
class UserProgress extends Model
{
    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
            'last_accessed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lessonFile(): BelongsTo
    {
        return $this->belongsTo(LessonFile::class);
    }

    /**
     * Mark this item as completed
     */
    public function markComplete(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'percent_complete' => 100,
        ]);
    }

    /**
     * Update progress for video/audio viewing
     */
    public function updateVideoProgress(int $secondsWatched, int $percentComplete): void
    {
        $this->update([
            'seconds_watched' => $secondsWatched,
            'percent_complete' => $percentComplete,
            'last_accessed_at' => now(),
        ]);

        if ($percentComplete >= 90) {
            $this->markComplete();
        }
    }

    /**
     * Update progress for document reading
     */
    public function updateDocumentProgress(int $linesRead, int $percentComplete): void
    {
        $this->update([
            'lines_read' => $linesRead,
            'percent_complete' => $percentComplete,
            'last_accessed_at' => now(),
        ]);

        if ($percentComplete >= 90) {
            $this->markComplete();
        }
    }
}
