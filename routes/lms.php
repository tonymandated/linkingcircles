<?php

declare(strict_types=1);

use App\Http\Controllers\Lms\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Lms\Admin\DashboardController;
use App\Http\Controllers\Lms\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Lms\Admin\LessonFileAccessibilityController;
use App\Http\Controllers\Lms\Admin\LessonFileController;
use App\Http\Controllers\Lms\Admin\UserManagementController;
use App\Http\Controllers\Lms\Learner\CourseCatalogController;
use App\Http\Controllers\Lms\Learner\FileDownloadController;
use App\Http\Controllers\Lms\Learner\FileViewController;
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

        // Authenticated learner routes
        Route::middleware(['auth', 'verified'])
            ->group(function (): void {
                Route::get('/files/{file}', [FileViewController::class, 'show'])->name('files.view');
                Route::get('/files/{file}/download', [FileDownloadController::class, 'download'])->name('files.download');
            });

        // Admin routes
        Route::middleware(['auth', 'verified'])
            ->prefix('/admin')
            ->as('admin.')
            ->group(function (): void {
                // Dashboard
                Route::get('/', DashboardController::class)->name('dashboard');

                // Courses and Lessons
                Route::resource('courses', AdminCourseController::class)->except('show');
                Route::resource('lessons', AdminLessonController::class)->except('show');

                // Lesson Files Management
                Route::get('lessons/{lesson}/files', [LessonFileController::class, 'index'])->name('lesson-files.index');
                Route::get('lessons/{lesson}/files/create', [LessonFileController::class, 'create'])->name('lesson-files.create');
                Route::post('lessons/{lesson}/files', [LessonFileController::class, 'store'])->name('lesson-files.store');
                Route::get('files/{file}/edit', [LessonFileController::class, 'edit'])->name('lesson-files.edit');
                Route::patch('files/{file}', [LessonFileController::class, 'update'])->name('lesson-files.update');
                Route::delete('files/{file}', [LessonFileController::class, 'destroy'])->name('lesson-files.destroy');

                // Lesson File Accessibility
                Route::get('files/{file}/accessibility', [LessonFileAccessibilityController::class, 'edit'])->name('lesson-files.accessibility.edit');
                Route::patch('files/{file}/accessibility', [LessonFileAccessibilityController::class, 'update'])->name('lesson-files.accessibility.update');

                // User Management
                Route::resource('users', UserManagementController::class);
            });
    });
