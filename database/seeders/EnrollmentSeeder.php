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
            // Enroll each student in 2-4 random courses
            $numberOfEnrollments = rand(2, min(4, $courses->count()));
            $enrolledCourses = $courses->random($numberOfEnrollments);

            foreach ($enrolledCourses as $course) {
                // Avoid duplicate enrollments
                if (Enrollment::where('user_id', $student->id)->where('course_id', $course->id)->exists()) {
                    continue;
                }

                // Simulate varied enrollment patterns
                $enrollmentDate = now()->subDays(rand(5, 90));
                $progress = rand(0, 100);

                // Some students complete courses, some don't
                $completedAt = null;
                if ($progress === 100 && rand(0, 1)) {
                    $completedAt = $enrollmentDate->copy()->addDays(rand(7, 45));
                }

                Enrollment::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'enrolled_at' => $enrollmentDate,
                    'progress' => $progress,
                    'completed_at' => $completedAt,
                ]);
            }
        }
    }
}
