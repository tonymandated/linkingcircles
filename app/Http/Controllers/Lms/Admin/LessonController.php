<?php

declare(strict_types=1);

namespace App\Http\Controllers\Lms\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\Admin\StoreLessonRequest;
use App\Http\Requests\Lms\Admin\UpdateLessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('lms.admin.lessons.index', [
            'lessons' => Lesson::query()->with('course')->latest()->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('lms.admin.lessons.create', [
            'courses' => Course::query()->orderBy('title')->get(['id', 'title']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (! empty($validated['is_published']) && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        Lesson::query()->create($validated);

        return redirect()
            ->route('lms.admin.lessons.index')
            ->with('status', 'Lesson created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Lesson $lesson): View
    {
        return view('lms.admin.lessons.edit', [
            'lesson' => $lesson,
            'courses' => Course::query()->orderBy('title')->get(['id', 'title']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLessonRequest $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validated();

        if (! empty($validated['is_published']) && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        if (empty($validated['is_published'])) {
            $validated['published_at'] = null;
        }

        $lesson->update($validated);

        return redirect()
            ->route('lms.admin.lessons.index')
            ->with('status', 'Lesson updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson): RedirectResponse
    {
        $lesson->delete();

        return redirect()
            ->route('lms.admin.lessons.index')
            ->with('status', 'Lesson deleted successfully.');
    }
}
