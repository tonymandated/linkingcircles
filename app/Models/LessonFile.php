<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\LessonFileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'lesson_id',
    'uploaded_by',
    'title',
    'file_name',
    'original_name',
    'mime_type',
    'file_size',
    'file_path',
    'disk',
    'file_type',
    'description',
    'alt_text',
    'duration_seconds',
    'width',
    'height',
    'is_published',
    'published_at',
])]
class LessonFile extends Model
{
    /** @use HasFactory<LessonFileFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function accessibilityData(): HasOne
    {
        return $this->hasOne(LessonFileAccessibilityData::class);
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Get the URL to view this file
     */
    public function getViewUrl(): string
    {
        return route('lms.files.view', ['file' => $this->id]);
    }

    /**
     * Get the download URL for this file
     */
    public function getDownloadUrl(): string
    {
        return route('lms.files.download', ['file' => $this->id]);
    }

    /**
     * Check if file is a video
     */
    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }

    /**
     * Check if file is an audio
     */
    public function isAudio(): bool
    {
        return $this->file_type === 'audio';
    }

    /**
     * Check if file is an image
     */
    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }

    /**
     * Check if file is a document
     */
    public function isDocument(): bool
    {
        return $this->file_type === 'document';
    }

    /**
     * Check if file requires accessibility features
     */
    public function requiresAccessibility(): bool
    {
        return in_array($this->file_type, ['video', 'audio', 'document']);
    }

    /**
     * Check if all required accessibility features are complete
     */
    public function isAccessibilityComplete(): bool
    {
        if (! $this->requiresAccessibility()) {
            return true;
        }

        $accessibility = $this->accessibilityData;
        if (! $accessibility) {
            return false;
        }

        if ($this->isVideo() || $this->isAudio()) {
            return ! empty($accessibility->transcript_file_path);
        }

        if ($this->isImage()) {
            return ! empty($this->alt_text);
        }

        return true;
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeFormatted(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2).' '.$units[$pow];
    }

    /**
     * Get duration in human readable format (for video/audio)
     */
    public function getDurationFormatted(): string
    {
        if (! $this->duration_seconds) {
            return '—';
        }

        $hours = intdiv($this->duration_seconds, 3600);
        $minutes = intdiv($this->duration_seconds % 3600, 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
