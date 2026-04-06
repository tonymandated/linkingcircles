<?php

use App\Models\Course;
use App\Models\Lesson;

it('renders the lms homepage on the configured subdomain', function (): void {
    $response = $this->get(route('lms.home'));

    $response
        ->assertOk()
        ->assertSee('Linking Circles LMS')
        ->assertSee('Explore courses');
});

it('lists only published courses in the learner catalog', function (): void {
    $publishedCourse = Course::factory()->published()->create(['title' => 'Published Course']);
    Course::factory()->create(['title' => 'Draft Course']);

    $response = $this->get(route('lms.courses.index'));

    $response
        ->assertOk()
        ->assertSee($publishedCourse->title)
        ->assertDontSee('Draft Course');
});

it('redirects draft courses away from the learner course details page', function (): void {
    $draftCourse = Course::factory()->create();

    $this->get(route('lms.courses.show', $draftCourse))
        ->assertRedirect(route('lms.courses.index'));
});

it('renders a published lesson player page', function (): void {
    $course = Course::factory()->published()->create();
    $lesson = Lesson::factory()->for($course)->create([
        'title' => 'Lesson One',
        'is_published' => true,
    ]);

    $this->get(route('lms.lessons.show', [$course, $lesson]))
        ->assertOk()
        ->assertSee('Lesson One');
});
