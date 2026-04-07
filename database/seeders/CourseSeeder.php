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

        if (! $instructor) {
            return;
        }

        // Core featured courses
        Course::factory()->published()->create([
            'title' => 'Introduction to Web Development',
            'slug' => 'intro-web-development',
            'excerpt' => 'Learn the basics of HTML, CSS, and JavaScript from scratch.',
            'description' => 'This comprehensive course covers the fundamentals of web development, including HTML for semantic structure, CSS for responsive styling, and JavaScript for interactivity. Perfect for beginners.',
            'created_by' => $instructor->id,
        ]);

        Course::factory()->published()->create([
            'title' => 'Advanced PHP with Laravel',
            'slug' => 'advanced-php-laravel',
            'excerpt' => 'Master Laravel framework for building robust, scalable web applications.',
            'description' => 'Dive deep into Laravel 11, exploring advanced features like Eloquent ORM, middleware, middleware groups, API development, testing patterns, and deployment strategies.',
            'created_by' => $instructor->id,
        ]);

        Course::factory()->published()->create([
            'title' => 'Web Accessibility Fundamentals (WCAG 2.1)',
            'slug' => 'web-accessibility-wcag',
            'excerpt' => 'Ensure your websites are accessible to everyone, including people with disabilities.',
            'description' => 'Learn WCAG 2.1 Level AA guidelines and best practices for creating truly inclusive web experiences. Covers keyboard navigation, screen readers, color contrast, ARIA attributes, and semantic HTML.',
            'created_by' => $instructor->id,
        ]);

        Course::factory()->published()->create([
            'title' => 'Tailwind CSS for Responsive Design',
            'slug' => 'tailwind-css-responsive',
            'excerpt' => 'Build beautiful, responsive interfaces with utility-first CSS.',
            'description' => 'Master Tailwind CSS v4, covering responsive design principles, dark mode implementation, component composition, accessibility utilities, and production optimization.',
            'created_by' => $instructor->id,
        ]);

        Course::factory()->published()->create([
            'title' => 'Livewire 4: Building Dynamic Laravel UIs',
            'slug' => 'livewire-dynamic-ui',
            'excerpt' => 'Create reactive, real-time interfaces without writing JavaScript.',
            'description' => 'Explore Livewire 4 for building interactive user interfaces using PHP. Topics include form validation, file uploads, live search, polling, and integrating with Alpine.js.',
            'created_by' => $instructor->id,
        ]);

        Course::factory()->published()->create([
            'title' => 'Database Design & SQL Optimization',
            'slug' => 'database-design-sql',
            'excerpt' => 'Learn relational database design and SQL query optimization techniques.',
            'description' => 'Cover database normalization, indexing strategies, query optimization, and performance monitoring. Includes hands-on examples with PostgreSQL and MySQL.',
            'created_by' => $instructor->id,
        ]);

        // Draft/upcoming course
        Course::factory()->create([
            'title' => 'Testing PHP Applications with Pest',
            'slug' => 'testing-pest',
            'excerpt' => 'Write comprehensive tests for Laravel applications using Pest PHP.',
            'description' => 'Learn testing strategies with Pest, including feature tests, unit tests, browser testing, and mutation testing.',
            'status' => 'draft',
            'created_by' => $instructor->id,
        ]);

        Course::factory()->create([
            'title' => 'API Security & Authentication',
            'slug' => 'api-security-auth',
            'excerpt' => 'Secure your APIs with OAuth, JWT, and best practices.',
            'description' => 'Comprehensive guide to API security, including authentication mechanisms, rate limiting, input validation, and protection against common vulnerabilities.',
            'status' => 'draft',
            'created_by' => $instructor->id,
        ]);

        // Create additional random courses
        Course::factory(4)->published()->create([
            'created_by' => $instructor->id,
        ]);
    }
}
