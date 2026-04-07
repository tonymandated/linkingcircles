@props([
    'mode' => 'panel',
])

@php
    $isLms = request()->getHost() === config('lms.domain');
    $mainSiteBaseUrl = rtrim((string) config('app.url'), '/');
    $mainSiteRoute = static fn (string $routeName): string => $mainSiteBaseUrl.route($routeName, absolute: false);

    $allSections = [
        [
            'label' => 'Account',
            'icon' => 'user-circle',
            'items' => [
                ['label' => 'Dashboard', 'icon' => 'home', 'href' => $mainSiteRoute('dashboard'), 'active' => request()->routeIs('dashboard'), 'permission' => null],
                ['label' => 'Profile', 'icon' => 'user', 'href' => $mainSiteRoute('profile.edit'), 'active' => request()->routeIs('profile.edit'), 'permission' => null],
                ['label' => 'Security', 'icon' => 'shield-check', 'href' => $mainSiteRoute('security.edit'), 'active' => request()->routeIs('security.edit'), 'permission' => null],
                ['label' => 'Appearance', 'icon' => 'swatch', 'href' => $mainSiteRoute('appearance.edit'), 'active' => request()->routeIs('appearance.edit'), 'permission' => null],
            ],
        ],
        [
            'label' => 'Learning',
            'icon' => 'academic-cap',
            'items' => [
                ['label' => 'LMS Home', 'icon' => 'rectangle-group', 'href' => route('lms.home'), 'active' => request()->routeIs('lms.home'), 'permission' => null],
                ['label' => 'Course Catalog', 'icon' => 'book-open', 'href' => route('lms.courses.index'), 'active' => request()->routeIs('lms.courses.*'), 'permission' => 'courses.view'],
            ],
        ],
        [
            'label' => 'Admin',
            'icon' => 'cog-6-tooth',
            'items' => [
                ['label' => 'Admin Dashboard', 'icon' => 'chart-bar-square', 'href' => route('lms.admin.dashboard'), 'active' => request()->routeIs('lms.admin.dashboard'), 'permission' => 'dashboard.view'],
                ['label' => 'Manage Courses', 'icon' => 'book-open', 'href' => route('lms.admin.courses.index'), 'active' => request()->routeIs('lms.admin.courses.*'), 'permission' => 'courses.view'],
                ['label' => 'Manage Lessons', 'icon' => 'queue-list', 'href' => route('lms.admin.lessons.index'), 'active' => request()->routeIs('lms.admin.lessons.*'), 'permission' => 'lessons.view'],
                ['label' => 'Manage Users', 'icon' => 'users', 'href' => route('lms.admin.users.index'), 'active' => request()->routeIs('lms.admin.users.*'), 'permission' => 'users.view'],
            ],
        ],
    ];

    $sections = [];
    foreach ($allSections as $section) {
        $items = array_values(array_filter($section['items'], function (array $item): bool {
            return $item['permission'] === null || auth()->user()->can($item['permission']);
        }));

        if ($items !== []) {
            $sections[] = [
                'label' => $section['label'],
                'icon' => $section['icon'],
                'open' => collect($items)->contains(fn (array $item): bool => (bool) $item['active']),
                'items' => $items,
            ];
        }
    }
@endphp

<nav
    data-backend-sidebar
    aria-label="Backend navigation"
    @class([
        'space-y-3',
        'rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-800' => $mode !== 'flux',
    ])
>
    @foreach ($sections as $section)
        <details class="group rounded-xl border border-zinc-200/70 bg-zinc-50/70 p-2 dark:border-zinc-700 dark:bg-zinc-900/50" @if ($section['open']) open @endif>
            <summary class="flex cursor-pointer list-none items-center justify-between rounded-lg px-2 py-2 text-sm font-semibold text-heading dark:text-zinc-100">
                <span class="inline-flex items-center gap-2">
                    <flux:icon :name="$section['icon']" class="size-4" />
                    {{ __($section['label']) }}
                </span>
                <flux:icon name="chevron-down" class="size-4 transition-transform group-open:rotate-180" />
            </summary>

            <ul class="mt-2 grid gap-1 border-t border-zinc-200 pt-2 dark:border-zinc-700">
                @foreach ($section['items'] as $item)
                    <li>
                        <a href="{{ $item['href'] }}" @class([
                            'flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-zinc-100 text-heading dark:bg-zinc-700 dark:text-white' => $item['active'],
                            'text-content hover:bg-zinc-100 hover:text-heading dark:text-zinc-300 dark:hover:bg-zinc-700 dark:hover:text-white' => ! $item['active'],
                        ])>
                            <flux:icon :name="$item['icon']" class="size-4" />
                            {{ __($item['label']) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </details>
    @endforeach
</nav>
