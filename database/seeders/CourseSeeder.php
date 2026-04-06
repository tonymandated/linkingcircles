<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructor = User::where('email', 'instructor@linkingcirclesacademy.com')->first();

        if (!$instructor) {
            return;
        }

        Course::factory()->create([
            'title' => 'Introduction to Web Development',
            'excerpt' => 'Learn the basics of HTML, CSS, and JavaScript.',
            'description' => 'This course covers the fundamentals of web development, including HTML for structure, CSS for styling, and JavaScript for interactivity.',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => $instructor->id,
        ]);

        Course::factory()->create([
            'title' => 'Advanced PHP with Laravel',
            'excerpt' => 'Master Laravel framework for building robust web applications.',
            'description' => 'Dive deep into Laravel, exploring advanced features like Eloquent, middleware, and API development.',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => $instructor->id,
        ]);

        Course::factory()->create([
            'title' => 'Accessibility in Web Design',
            'excerpt' => 'Ensure your websites are accessible to everyone.',
            'description' => 'Learn WCAG guidelines and best practices for creating inclusive web experiences.',
            'status' => 'draft',
            'created_by' => $instructor->id,
        ]);

        // Create some additional courses
        Course::factory(5)->create([
            'created_by' => $instructor->id,
        ]);
    }
}
