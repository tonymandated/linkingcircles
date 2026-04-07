<?php

declare(strict_types=1);

namespace App\Http\Controllers\Lms\Learner;

use App\Http\Controllers\Controller;
use App\Models\LessonFile;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FileViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Display file for viewing
     */
    public function show(LessonFile $file): View
    {
        $this->authorize('lesson_files.view');

        // Check if user is enrolled in the course
        $lesson = $file->lesson;
        $course = $lesson->course;
        $isEnrolled = auth()->user()->enrolledCourses()->where('course_id', $course->id)->exists();

        if (! $isEnrolled && ! auth()->user()->can('courses.manage')) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Load accessibility data
        $file->load('accessibilityData');

        // Get or create user progress
        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'lesson_file_id' => $file->id,
            ],
            [
                'percent_complete' => 0,
                'is_completed' => false,
            ]
        );

        $progress->update(['last_accessed_at' => now()]);

        // Get signed URL for file access if needed
        $fileUrl = $this->getFileUrl($file);

        return view('lms.learner.file.view', [
            'file' => $file,
            'lesson' => $lesson,
            'course' => $course,
            'progress' => $progress,
            'fileUrl' => $fileUrl,
            'isEnrolled' => $isEnrolled,
        ]);
    }

    /**
     * Get appropriate file URL based on file type
     */
    private function getFileUrl(LessonFile $file): string
    {
        if ($file->disk === 'media') {
            return Storage::disk('media')->url($file->file_path);
        }

        // For private files, generate signed URL
        return Storage::disk($file->disk)->temporaryUrl(
            $file->file_path,
            now()->addHours(24)
        );
    }
}
