<?php

declare(strict_types=1);

namespace App\Http\Controllers\Lms\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonFile;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Display admin dashboard
     */
    public function __invoke(): View
    {
        $this->authorize('dashboard.view');

        $stats = [
            'totalUsers' => User::count(),
            'totalCourses' => Course::count(),
            'totalLessons' => Lesson::count(),
            'totalFiles' => LessonFile::count(),
            'instructors' => User::whereHas('roles', function ($q) {
                $q->where('name', 'instructor');
            })->count(),
            'students' => User::whereHas('roles', function ($q) {
                $q->where('name', 'student');
            })->count(),
            'filesWithoutAccessibility' => LessonFile::whereHas('lesson.files', function ($q) {
                $q->whereNotExists(function ($subQuery) {
                    $subQuery->select(1)
                        ->from('lesson_file_accessibility_data')
                        ->whereColumn('lesson_files.id', 'lesson_file_accessibility_data.lesson_file_id');
                });
            })->count(),
        ];

        $recentFiles = LessonFile::with('lesson', 'uploader')
            ->latest('created_at')
            ->limit(10)
            ->get();

        $recentCourses = Course::with('creator')
            ->latest('created_at')
            ->limit(5)
            ->get();

        $incompleteLessons = Lesson::where('is_published', false)
            ->with('course')
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('lms.admin.dashboard', [
            'stats' => $stats,
            'recentFiles' => $recentFiles,
            'recentCourses' => $recentCourses,
            'incompleteLessons' => $incompleteLessons,
        ]);
    }
}
