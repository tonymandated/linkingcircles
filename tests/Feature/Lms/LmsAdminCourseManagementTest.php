<?php

use App\Models\Course;
use App\Models\User;

it('redirects guests from the lms admin area to login', function (): void {
    $this->get(route('lms.admin.courses.index'))
        ->assertRedirect(route('login'));
});

it('allows authenticated users to create, update, and delete courses', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post(route('lms.admin.courses.store'), [
        'title' => 'Foundations of Learning',
        'slug' => 'foundations-of-learning',
        'excerpt' => 'Core concepts for foundational learning.',
        'description' => 'Detailed description.',
        'status' => 'published',
        'published_at' => now()->toDateTimeString(),
    ])->assertRedirect(route('lms.admin.courses.index'));

    $course = Course::query()->where('slug', 'foundations-of-learning')->firstOrFail();

    $this->put(route('lms.admin.courses.update', $course), [
        'title' => 'Foundations of Learning Updated',
        'slug' => 'foundations-of-learning-updated',
        'excerpt' => 'Updated excerpt.',
        'description' => 'Updated description.',
        'status' => 'draft',
        'published_at' => null,
    ])->assertRedirect(route('lms.admin.courses.index'));

    expect($course->fresh()->slug)->toBe('foundations-of-learning-updated');

    $this->delete(route('lms.admin.courses.destroy', $course->fresh()))
        ->assertRedirect(route('lms.admin.courses.index'));

    expect(Course::query()->whereKey($course->id)->exists())->toBeFalse();
});

it('allows authenticated users to create lessons from admin', function (): void {
    $user = User::factory()->create();
    $course = Course::factory()->for($user, 'creator')->published()->create();

    $this->actingAs($user)
        ->post(route('lms.admin.lessons.store'), [
            'course_id' => $course->id,
            'title' => 'Getting Started',
            'slug' => 'getting-started',
            'summary' => 'A summary',
            'content' => 'Lesson content',
            'position' => 1,
            'is_published' => true,
            'published_at' => now()->toDateTimeString(),
        ])
        ->assertRedirect(route('lms.admin.lessons.index'));

    $course->refresh();

    expect($course->lessons()->where('slug', 'getting-started')->exists())->toBeTrue();
});
