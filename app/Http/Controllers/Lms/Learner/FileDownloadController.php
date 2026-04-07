<?php

declare(strict_types=1);

namespace App\Http\Controllers\Lms\Learner;

use App\Http\Controllers\Controller;
use App\Models\LessonFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileDownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Download file
     */
    public function download(LessonFile $file): StreamedResponse
    {
        $this->authorize('lesson_files.download');

        // Check if user is enrolled in the course
        $lesson = $file->lesson;
        $course = $lesson->course;
        $isEnrolled = auth()->user()->enrolledCourses()->where('course_id', $course->id)->exists();

        if (! $isEnrolled && ! auth()->user()->can('courses.manage')) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Check if file exists
        if (! Storage::disk($file->disk)->exists($file->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk($file->disk)->download($file->file_path, $file->original_name);
    }
}
