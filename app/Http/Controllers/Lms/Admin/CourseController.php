<?php

declare(strict_types=1);

namespace App\Http\Controllers\Lms\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\Admin\StoreCourseRequest;
use App\Http\Requests\Lms\Admin\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('lms.admin.courses.index', [
            'courses' => Course::query()->latest()->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('lms.admin.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = (int) $request->user()->id;

        Course::query()->create($validated);

        return redirect()
            ->route('lms.admin.courses.index')
            ->with('status', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Course $course): View
    {
        return view('lms.admin.courses.edit', [
            'course' => $course,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()
            ->route('lms.admin.courses.index')
            ->with('status', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()
            ->route('lms.admin.courses.index')
            ->with('status', 'Course deleted successfully.');
    }
}
