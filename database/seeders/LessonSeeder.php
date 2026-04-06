<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();

        if ($courses->isEmpty()) {
            return;
        }

        foreach ($courses as $course) {
            // Create a few lessons per course
            Lesson::factory()->create([
                'course_id' => $course->id,
                'title' => 'Introduction to ' . $course->title,
                'summary' => 'Overview of the course topics.',
                'content' => 'This lesson introduces the main concepts that will be covered in the course.',
                'position' => 1,
                'is_published' => true,
                'published_at' => now(),
            ]);

            Lesson::factory()->create([
                'course_id' => $course->id,
                'title' => 'Core Concepts',
                'summary' => 'Dive into the core concepts.',
                'content' => 'In this lesson, we explore the fundamental ideas and principles.',
                'position' => 2,
                'is_published' => true,
                'published_at' => now(),
            ]);

            Lesson::factory()->create([
                'course_id' => $course->id,
                'title' => 'Practical Application',
                'summary' => 'Apply what you have learned.',
                'content' => 'This lesson focuses on practical exercises and real-world applications.',
                'position' => 3,
                'is_published' => false,
            ]);
        }
    }
}
