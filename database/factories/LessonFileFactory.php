<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\LessonFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LessonFile>
 */
class LessonFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileType = fake()->randomElement(['video', 'audio', 'image', 'document']);
        $extension = match ($fileType) {
            'video' => 'mp4',
            'audio' => 'mp3',
            'image' => 'jpg',
            'document' => 'pdf',
            default => 'pdf',
        };

        $fileName = 'lesson-'.Str::random(10).'.'.$extension;
        $title = fake()->sentence(3);

        return [
            'lesson_id' => Lesson::factory(),
            'uploaded_by' => User::factory(),
            'title' => $title,
            'file_name' => $fileName,
            'original_name' => fake()->sentence(2).'.'.$extension,
            'mime_type' => match ($fileType) {
                'video' => 'video/mp4',
                'audio' => 'audio/mpeg',
                'image' => 'image/jpeg',
                'document' => 'application/pdf',
                default => 'application/octet-stream',
            },
            'file_size' => match ($fileType) {
                'video' => fake()->numberBetween(50_000_000, 500_000_000),
                'audio' => fake()->numberBetween(5_000_000, 100_000_000),
                'image' => fake()->numberBetween(500_000, 10_000_000),
                'document' => fake()->numberBetween(500_000, 50_000_000),
                default => fake()->numberBetween(1_000_000, 100_000_000),
            },
            'file_path' => 'uploads/lessons/'.$fileName,
            'disk' => 'media',
            'file_type' => $fileType,
            'description' => fake()->sentence(10),
            'alt_text' => $fileType === 'image' ? fake()->sentence(8) : null,
            'duration_seconds' => in_array($fileType, ['video', 'audio']) ? fake()->numberBetween(300, 3600) : null,
            'width' => $fileType === 'image' || $fileType === 'video' ? fake()->numberBetween(720, 1920) : null,
            'height' => $fileType === 'image' || $fileType === 'video' ? fake()->numberBetween(720, 1080) : null,
            'is_published' => true,
            'published_at' => now(),
        ];
    }

    public function withTranscript(): self
    {
        return $this->afterCreating(function (LessonFile $file) {
            if (in_array($file->file_type, ['video', 'audio'])) {
                $file->accessibilityData()->create([
                    'transcript_file_path' => 'transcripts/'.$file->id.'.vtt',
                    'transcript_disk' => 'captions',
                    'transcript_preview' => fake()->text(200),
                    'transcript_language' => 'en',
                ]);
            }
        });
    }

    public function unpublished(): self
    {
        return $this->state(fn (): array => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
