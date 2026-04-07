<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

it('renders each public page route', function (string $routeName): void {
    $response = $this->get(route($routeName));

    $response
        ->assertOk()
        ->assertSee('id="main-content"', false)
        ->assertSee('Skip to main content')
        ->assertSee('Linking Circles Academy')
        ->assertSee('href="'.route('lms.home').'"', false)
        ->assertSee('data-header-drawer-toggle', false)
        ->assertSee('About Overview')
        ->assertSee('data-accessibility-controls', false)
        ->assertSee('data-accessibility-action="toggle-read-aloud"', false)
        ->assertSee('data-accessibility-action="toggle-settings-panel"', false)
        ->assertSee('data-accessibility-setting="speech-locale"', false);
})->with([
    'home',
    'about',
    'offerings',
    'programs',
    'team',
    'blog',
    'contact',
    'programs-teachers',
    'project-teci',
]);

it('redirects our-team to the canonical team URL', function (): void {
    $this->get('/our-team')->assertRedirect('/the-team');
});

it('shows auth links in the header on marketing pages', function (): void {
    $guestResponse = $this->get(route('home'));

    $guestResponse->assertOk();

    if (Route::has('login')) {
        $guestResponse->assertSee('href="'.route('login').'"', false);
    }

    if (Route::has('register')) {
        $guestResponse->assertSee('href="'.route('register').'"', false);
    }

    if (Route::has('logout')) {
        $guestResponse->assertDontSee('Log out');
    }

    $user = User::factory()->create();
    $authResponse = $this->actingAs($user)->get(route('home'));

    $authResponse
        ->assertOk()
        ->assertSee('href="'.route('dashboard').'"', false)
        ->assertSee('Dashboard');

    if (Route::has('logout')) {
        $authResponse
            ->assertSee('action="'.route('logout').'"', false)
            ->assertSee('Log out');
    }
});
