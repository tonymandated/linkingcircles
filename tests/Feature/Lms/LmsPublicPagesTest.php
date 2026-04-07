<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\Route;

it('renders the lms homepage on the configured subdomain', function (): void {
    $response = $this->get(route('lms.home'));
    $mainSiteBaseUrl = rtrim((string) config('app.url'), '/');

    $response
        ->assertOk()
        ->assertSee('LMS Home')
        ->assertSee('Explore courses')
        ->assertSee('href="'.$mainSiteBaseUrl.route('home', absolute: false).'"', false)
        ->assertSee('href="'.$mainSiteBaseUrl.route('about', absolute: false).'"', false)
        ->assertSee('data-header-drawer-toggle', false)
        ->assertSee('About Overview')
        ->assertSee('data-accessibility-controls', false)
        ->assertSee('data-accessibility-action="toggle-read-aloud"', false)
        ->assertSee('data-accessibility-action="toggle-settings-panel"', false)
        ->assertSee('data-accessibility-setting="speech-locale"', false);
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

it('shows auth links in the header on lms pages', function (): void {
    $mainSiteBaseUrl = rtrim((string) config('app.url'), '/');

    $guestResponse = $this->get(route('lms.home'));
    $guestResponse->assertOk();

    if (Route::has('login')) {
        $guestResponse->assertSee('href="'.$mainSiteBaseUrl.route('login', absolute: false).'"', false);
    }

    if (Route::has('register')) {
        $guestResponse->assertSee('href="'.$mainSiteBaseUrl.route('register', absolute: false).'"', false);
    }

    if (Route::has('logout')) {
        $guestResponse->assertDontSee('Log out');
    }

    $user = User::factory()->create();
    $authResponse = $this->actingAs($user)->get(route('lms.home'));

    $authResponse
        ->assertOk()
        ->assertSee('href="'.$mainSiteBaseUrl.route('dashboard', absolute: false).'"', false)
        ->assertSee('Dashboard');

    if (Route::has('logout')) {
        $authResponse
            ->assertSee('action="'.$mainSiteBaseUrl.route('logout', absolute: false).'"', false)
            ->assertSee('Log out');
    }
});
