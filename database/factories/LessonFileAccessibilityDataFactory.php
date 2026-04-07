<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\LessonFile;
use App\Models\LessonFileAccessibilityData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LessonFileAccessibilityData>
 */
class LessonFileAccessibilityDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lesson_file_id' => LessonFile::factory(),
            'transcript_file_path' => 'transcripts/'.fake()->md5().'.vtt',
            'transcript_disk' => 'captions',
            'transcript_preview' => fake()->text(200),
            'transcript_language' => fake()->randomElement(['en', 'es', 'fr', 'de']),
            'sign_language_video_path' => null,
            'sign_language_video_disk' => 'media',
            'extended_description' => fake()->paragraph(),
            'audio_description' => fake()->paragraph(),
            'document_structure' => null,
            'is_searchable' => true,
            'accessibility_metadata' => null,
            'accessibility_completed_at' => now(),
        ];
    }
}
