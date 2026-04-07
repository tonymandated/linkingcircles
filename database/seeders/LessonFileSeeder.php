<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\LessonFile;
use App\Models\User;
use Illuminate\Database\Seeder;

class LessonFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessons = Lesson::all();
        $instructor = User::where('email', 'instructor@linkingcirclesacademy.com')->first();

        if ($lessons->isEmpty() || ! $instructor) {
            return;
        }

        foreach ($lessons as $lesson) {
            // Create 1-3 files per lesson with varied types
            $fileCount = rand(1, 3);

            for ($i = 0; $i < $fileCount; $i++) {
                $fileTypes = ['video', 'audio', 'image', 'document'];
                $fileType = $fileTypes[array_rand($fileTypes)];

                $file = LessonFile::factory()
                    ->create([
                        'lesson_id' => $lesson->id,
                        'uploaded_by' => $instructor->id,
                        'file_type' => $fileType,
                    ]);

                // Create accessibility data for video and audio
                if (in_array($fileType, ['video', 'audio'])) {
                    $file->accessibilityData()->create([
                        'transcript_file_path' => "transcripts/{$file->id}.vtt",
                        'transcript_disk' => 'captions',
                        'transcript_preview' => fake()->text(200),
                        'transcript_language' => 'en',
                        'extended_description' => fake()->paragraph(),
                        'accessibility_completed_at' => now(),
                    ]);
                }

                // Create accessibility data for images
                if ($fileType === 'image') {
                    $file->accessibilityData()->create([
                        'extended_description' => fake()->paragraph(),
                        'accessibility_completed_at' => now(),
                    ]);
                }
            }
        }
    }
}
