<?php

declare(strict_types=1);

use App\Http\Controllers\Lms\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Lms\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Lms\Learner\CourseCatalogController;
use App\Http\Controllers\Lms\Learner\HomeController;
use App\Http\Controllers\Lms\Learner\LessonPlayerController;
use Illuminate\Support\Facades\Route;

Route::domain(config('lms.domain'))
    ->as('lms.')
    ->group(function (): void {
        Route::get('/', HomeController::class)->name('home');
        Route::get('/courses', [CourseCatalogController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}', [CourseCatalogController::class, 'show'])->name('courses.show');
        Route::get('/courses/{course}/lessons/{lesson}', [LessonPlayerController::class, 'show'])->name('lessons.show');

        Route::middleware(['auth', 'verified'])
            ->prefix('/admin')
            ->as('admin.')
            ->group(function (): void {
                Route::resource('courses', AdminCourseController::class)->except('show');
                Route::resource('lessons', AdminLessonController::class)->except('show');
            });
    });
