<?php

it('renders each public page route', function (string $routeName): void {
    $response = $this->get(route($routeName));

    $response
        ->assertOk()
        ->assertSee('id="main-content"', false)
        ->assertSee('Skip to main content')
        ->assertSee('Linking Circles Academy');
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
