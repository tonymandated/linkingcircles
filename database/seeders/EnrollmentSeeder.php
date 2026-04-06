<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })->get();

        $courses = Course::where('status', 'published')->get();

        if ($students->isEmpty() || $courses->isEmpty()) {
            return;
        }

        foreach ($students as $student) {
            // Enroll each student in a few random courses
            $randomCourses = $courses->random(min(3, $courses->count()));

            foreach ($randomCourses as $course) {
                Enrollment::firstOrCreate([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ], [
                    'enrolled_at' => now()->subDays(rand(1, 30)),
                    'progress' => rand(0, 100),
                    'completed_at' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                ]);
            }
        }
    }
}
