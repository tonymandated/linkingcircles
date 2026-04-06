<?php

declare(strict_types=1);

namespace App\Http\Controllers\Lms\Learner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseCatalogController extends Controller
{
    public function index(): View
    {
        return view('lms.learner.courses.index', [
            'courses' => Course::query()
                ->where('status', 'published')
                ->latest('published_at')
                ->paginate(9),
        ]);
    }

    public function show(Course $course): View|RedirectResponse
    {
        if ($course->status !== 'published') {
            return redirect()->route('lms.courses.index');
        }

        $course->load(['lessons' => fn ($query) => $query->where('is_published', true)]);

        return view('lms.learner.courses.show', [
            'course' => $course,
        ]);
    }
}
