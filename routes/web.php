<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/offerings', [PageController::class, 'offerings'])->name('offerings');
Route::get('/available-programs', [PageController::class, 'programs'])->name('programs');
Route::get('/the-team', [PageController::class, 'team'])->name('team');
Route::redirect('/our-team', '/the-team');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/available-programs-teachers', [PageController::class, 'programsTeachers'])->name('programs-teachers');
Route::get('/project-teci', [PageController::class, 'projectTeci'])->name('project-teci');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [PageController::class, 'dashboard'])->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/lms.php';
