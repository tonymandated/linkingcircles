<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            // Create structured lessons for each course
            $lessonCount = rand(4, 8);

            for ($position = 1; $position <= $lessonCount; $position++) {
                $title = match ($position) {
                    1 => "Introduction to {$course->title}",
                    2 => 'Core Concepts & Foundations',
                    3 => "Getting Started: Your First {$course->title}",
                    4 => 'Advanced Techniques & Best Practices',
                    5 => 'Real-World Projects & Case Studies',
                    6 => 'Troubleshooting & Common Pitfalls',
                    7 => 'Performance & Optimization',
                    default => fake()->sentence(3),
                };

                $isPublished = $position <= 5; // First 5 lessons published, rest draft

                Lesson::factory()->create([
                    'course_id' => $course->id,
                    'title' => $title,
                    'slug' => Str::slug($course->slug.'-lesson-'.$position),
                    'summary' => fake()->sentence(12),
                    'content' => $this->generateLessonContent($position, $course->title),
                    'position' => $position,
                    'is_published' => $isPublished,
                    'published_at' => $isPublished ? now()->subDays(rand(1, 30)) : null,
                ]);
            }
        }
    }

    private function generateLessonContent(int $position, string $courseTitle): string
    {
        return match ($position) {
            1 => "Welcome to {$courseTitle}! This introductory lesson sets the foundation for everything you'll learn in this course.\n\n".
                 "Key topics covered:\n".
                 "• Course overview and learning objectives\n".
                 "• Prerequisites and expectations\n".
                 "• How to get the most out of this course\n\n".
                 "By the end of this lesson, you'll have a clear understanding of what to expect and be ready to dive into the core material.",

            2 => "In this lesson, we'll explore the fundamental concepts that underpin {$courseTitle}.\n\n".
                 "You'll learn:\n".
                 "• Essential terminology and definitions\n".
                 "• Core principles and theory\n".
                 "• How different concepts relate to each other\n".
                 "• Why these foundations matter for your success\n\n".
                 'Understanding these concepts deeply will make everything else in the course much clearer.',

            3 => "Let's get hands-on! In this practical lesson, you'll create your first {$courseTitle} project.\n\n".
                 "What you'll accomplish:\n".
                 "• Setting up your development environment\n".
                 "• Creating your first project from scratch\n".
                 "• Understanding the project structure\n".
                 "• Running and testing your work\n\n".
                 "Don't worry if you get stuck—each step includes detailed explanations and troubleshooting tips.",

            4 => "Now that you understand the basics, we'll explore advanced techniques and industry best practices.\n\n".
                 "Topics include:\n".
                 "• Performance optimization strategies\n".
                 "• Architectural patterns and design principles\n".
                 "• Security considerations\n".
                 "• Code quality and maintainability\n\n".
                 'These advanced techniques will help you write professional-grade code.',

            5 => "Apply what you've learned with real-world projects and case studies.\n\n".
                 "In this lesson:\n".
                 "• We'll analyze production code examples\n".
                 "• Build a complete project from requirements to deployment\n".
                 "• Review case studies from industry leaders\n".
                 "• Discuss lessons learned and common challenges\n\n".
                 "Real-world experience is crucial for becoming proficient in {$courseTitle}.",

            default => fake()->paragraphs(4, asText: true),
        };
    }
}
