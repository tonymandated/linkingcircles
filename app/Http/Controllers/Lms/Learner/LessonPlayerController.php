<?php

declare(strict_types=1);

namespace App\Http\Controllers\Lms\Learner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonPlayerController extends Controller
{
    public function show(Course $course, Lesson $lesson): View|RedirectResponse
    {
        if ($course->status !== 'published' || ! $lesson->is_published || $lesson->course_id !== $course->id) {
            return redirect()->route('lms.courses.show', $course);
        }

        return view('lms.learner.lessons.show', [
            'course' => $course,
            'lesson' => $lesson,
        ]);
    }
}
