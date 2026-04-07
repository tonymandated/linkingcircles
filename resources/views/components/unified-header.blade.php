@props([
    'showBackendSidebarToggle' => false,
])

@php
    $isLms = request()->getHost() === config('lms.domain');
    $hasLoginRoute = Route::has('login');
    $hasRegisterRoute = Route::has('register');
    $hasLogoutRoute = Route::has('logout');

    $mainSiteBaseUrl = rtrim((string) config('app.url'), '/');
    $mainSiteRoute = static fn (string $routeName): string => $mainSiteBaseUrl.route($routeName, absolute: false);

    $aboutChildren = [
        ['label' => 'About Overview', 'href' => $mainSiteRoute('about'), 'route' => 'about', 'cross_domain' => $isLms],
        ['label' => 'Offerings', 'href' => $mainSiteRoute('offerings'), 'route' => 'offerings', 'cross_domain' => $isLms],
        ['label' => 'Programs', 'href' => $mainSiteRoute('programs'), 'route' => 'programs', 'cross_domain' => $isLms],
        ['label' => 'Team', 'href' => $mainSiteRoute('team'), 'route' => 'team', 'cross_domain' => $isLms],
    ];

    $marketingItems = [
        ['label' => 'Home', 'href' => $mainSiteRoute('home'), 'route' => 'home', 'cross_domain' => $isLms],
        ['label' => 'About', 'href' => $mainSiteRoute('about'), 'active' => request()->routeIs('about', 'offerings', 'programs', 'team'), 'children' => $aboutChildren],
        ['label' => 'Blog', 'href' => $mainSiteRoute('blog'), 'route' => 'blog', 'cross_domain' => $isLms],
        ['label' => 'Contact', 'href' => $mainSiteRoute('contact'), 'route' => 'contact', 'cross_domain' => $isLms],
    ];

    $lmsItems = $isLms ? [
        ['label' => 'LMS Home', 'href' => route('lms.home'), 'route' => 'lms.home', 'cross_domain' => false],
        ['label' => 'Courses', 'href' => route('lms.courses.index'), 'route' => 'lms.courses.index', 'cross_domain' => false],
    ] : [];

    $items = [...$marketingItems, ...$lmsItems];
    $mainHomeUrl = $mainSiteRoute('home');
@endphp

<header class="sticky top-0 z-50 border-b border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900" data-header-drawer-root>
    <div class="border-b border-zinc-200/80 dark:border-zinc-700/80" aria-label="Accessibility controls bar">
        <div class="lc-container flex items-center justify-end py-2">
            <x-accessibility-controls />
        </div>
    </div>

    <div class="lc-container flex flex-wrap items-center justify-between gap-4 py-4">
        <div class="flex min-w-0 items-center gap-3">
            @if ($showBackendSidebarToggle)
                <flux:sidebar.toggle
                    class="inline-flex items-center justify-center rounded-md border border-zinc-300 p-2 text-zinc-900 transition-colors hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-100 dark:hover:bg-zinc-800"
                    icon="bars-2"
                    inset="left"
                    aria-label="Toggle sidebar"
                />
            @endif

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-md border border-zinc-300 p-2 text-zinc-900 transition-colors hover:bg-zinc-100 lg:hidden dark:border-zinc-700 dark:text-zinc-100 dark:hover:bg-zinc-800"
                data-header-drawer-toggle
                aria-expanded="false"
                aria-controls="mobile-primary-menu"
                aria-label="Open menu"
            >
                <svg data-header-icon="menu" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
                <svg data-header-icon="close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden size-5">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>

            <a href="{{ $mainHomeUrl }}" class="inline-flex items-center gap-3 shrink-0">
                <img
                    src="{{ asset('img/linking-circles-academy-logo.png') }}"
                    alt="Linking Circles Academy"
                    class="h-12 w-auto"
                >
                @if ($isLms)
                    <span class="inline-flex items-center rounded-full bg-primary px-2 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                        LMS
                    </span>
                @endif
                <span class="sr-only">Linking Circles Academy</span>
            </a>
        </div>

        <nav aria-label="Primary navigation" class="hidden lg:block">
            <ul class="flex items-center justify-center gap-5">
                @foreach ($items as $item)
                    @if (isset($item['children']))
                        <li class="relative">
                            <details class="group" data-header-submenu>
                                <summary
                                    data-header-summary
                                    @class([
                                        'lc-nav-link flex cursor-pointer list-none items-center gap-1',
                                        'lc-nav-link-active' => $item['active'],
                                    ])
                                >
                                    <span>{{ $item['label'] }}</span>
                                    <svg data-header-chevron xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 transition-transform duration-200">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                    </svg>
                                </summary>

                                <div class="absolute left-0 top-full mt-3 w-64 rounded-xl border border-zinc-200 bg-white p-3 shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
                                    <ul class="grid gap-1">
                                        @foreach ($item['children'] as $child)
                                            <li>
                                                <a
                                                    href="{{ $child['href'] }}"
                                                    @if (! $child['cross_domain']) wire:navigate @endif
                                                    @class([
                                                        'block rounded-lg px-3 py-2 text-sm text-content transition-colors hover:bg-zinc-100 hover:text-heading dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-white',
                                                        'bg-zinc-100 text-heading dark:bg-zinc-800 dark:text-white' => request()->routeIs($child['route']),
                                                    ])
                                                >
                                                    {{ $child['label'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </details>
                        </li>
                    @else
                        <li>
                            <a
                                href="{{ $item['href'] }}"
                                @if (! $item['cross_domain']) wire:navigate @endif
                                @class([
                                    'lc-nav-link',
                                    'lc-nav-link-active' => request()->routeIs($item['route']) ||
                                        ($item['route'] === 'lms.courses.index' && request()->routeIs('lms.courses.*')),
                                ])
                            >
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>

        <div class="ml-auto flex items-center gap-4 lg:ml-0">
            <div class="hidden items-center gap-4 border-l border-zinc-200 pl-4 dark:border-zinc-700 lg:flex">
                @if ($isLms)
                    <a
                        href="{{ $mainHomeUrl }}"
                        class="text-xs font-semibold uppercase tracking-wide text-primary transition-colors hover:text-heading dark:hover:text-white"
                        title="Visit main website"
                    >
                        ← Main Site
                    </a>
                @else
                    <a
                        href="{{ route('lms.home') }}"
                        class="text-xs font-semibold uppercase tracking-wide text-primary transition-colors hover:text-heading dark:hover:text-white"
                        title="Visit learning management system"
                    >
                        LMS →
                    </a>
                @endif

                @auth
                    <a href="{{ $mainSiteRoute('dashboard') }}" @if (! $isLms) wire:navigate @endif class="lc-nav-link">
                        Dashboard
                    </a>

                    @if ($hasLogoutRoute)
                        <form method="POST" action="{{ $mainSiteRoute('logout') }}" class="inline-flex">
                            @csrf
                            <button type="submit" class="lc-nav-link">Log out</button>
                        </form>
                    @endif
                @endauth

                @guest
                    @if ($hasLoginRoute)
                        <a href="{{ $mainSiteRoute('login') }}" @if (! $isLms) wire:navigate @endif class="lc-nav-link">
                            Log in
                        </a>
                    @endif

                    @if ($hasRegisterRoute)
                        <a href="{{ $mainSiteRoute('register') }}" @if (! $isLms) wire:navigate @endif class="lc-nav-link">
                            Register
                        </a>
                    @endif
                @endguest
            </div>

        </div>
    </div>

    <div class="pointer-events-none fixed inset-0 z-40 hidden lg:hidden" data-header-drawer-container aria-hidden="true">
        <div class="absolute inset-0 bg-zinc-950/45 opacity-0 transition-opacity duration-300" data-header-drawer-backdrop></div>

        <aside
            id="mobile-primary-menu"
            class="absolute inset-y-0 left-0 flex w-full max-w-xs -translate-x-full flex-col overflow-y-auto bg-white p-6 shadow-xl transition-transform duration-300 dark:bg-zinc-900"
            data-header-drawer-panel
            tabindex="-1"
            aria-label="Mobile navigation"
        >
            <div class="flex items-center justify-between gap-3 border-b border-zinc-200 pb-4 dark:border-zinc-700">
                <span class="text-base font-semibold text-heading dark:text-white">Menu</span>
                @if ($isLms)
                    <span class="inline-flex items-center rounded-full bg-primary px-2 py-1 text-xs font-semibold uppercase tracking-wide text-white">LMS</span>
                @endif
            </div>

            <nav class="mt-5" aria-label="Mobile primary navigation">
                <ul class="grid gap-2">
                    @foreach ($items as $item)
                        @if (isset($item['children']))
                            <li class="rounded-xl border border-zinc-200 px-3 py-2 dark:border-zinc-700">
                                <details data-header-submenu @if ($item['active']) open @endif>
                                    <summary
                                        data-header-summary
                                        @class([
                                            'flex cursor-pointer list-none items-center justify-between gap-3 rounded-lg py-1 text-sm font-medium text-content dark:text-zinc-300',
                                            'text-primary dark:text-white' => $item['active'],
                                        ])
                                    >
                                        <span>{{ $item['label'] }}</span>
                                        <svg data-header-chevron xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 transition-transform duration-200">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                    </summary>

                                    <ul class="mt-2 grid gap-1 border-t border-zinc-200 pt-2 dark:border-zinc-700">
                                        @foreach ($item['children'] as $child)
                                            <li>
                                                <a
                                                    href="{{ $child['href'] }}"
                                                    data-header-drawer-link
                                                    @if (! $child['cross_domain']) wire:navigate @endif
                                                    @class([
                                                        'block rounded-lg px-3 py-2 text-sm text-content transition-colors hover:bg-zinc-100 hover:text-heading dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-white',
                                                        'bg-zinc-100 text-heading dark:bg-zinc-800 dark:text-white' => request()->routeIs($child['route']),
                                                    ])
                                                >
                                                    {{ $child['label'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </details>
                            </li>
                        @else
                            <li>
                                <a
                                    href="{{ $item['href'] }}"
                                    data-header-drawer-link
                                    @if (! $item['cross_domain']) wire:navigate @endif
                                    @class([
                                        'block rounded-xl border border-zinc-200 px-4 py-3 text-sm font-medium text-content transition-colors hover:bg-zinc-100 hover:text-heading dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-white',
                                        'border-primary bg-zinc-100 text-heading dark:bg-zinc-800 dark:text-white' => request()->routeIs($item['route']) ||
                                            ($item['route'] === 'lms.courses.index' && request()->routeIs('lms.courses.*')),
                                    ])
                                >
                                    {{ $item['label'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>

            <div class="mt-6 grid gap-3 border-t border-zinc-200 pt-4 dark:border-zinc-700">
                @if ($isLms)
                    <a
                        href="{{ $mainHomeUrl }}"
                        data-header-drawer-link
                        class="text-sm font-semibold uppercase tracking-wide text-primary transition-colors hover:text-heading dark:hover:text-white"
                    >
                        ← Main Site
                    </a>
                @else
                    <a
                        href="{{ route('lms.home') }}"
                        data-header-drawer-link
                        class="text-sm font-semibold uppercase tracking-wide text-primary transition-colors hover:text-heading dark:hover:text-white"
                    >
                        LMS →
                    </a>
                @endif

                @auth
                    <a href="{{ $mainSiteRoute('dashboard') }}" data-header-drawer-link @if (! $isLms) wire:navigate @endif class="lc-nav-link">
                        Dashboard
                    </a>

                    @if ($hasLogoutRoute)
                        <form method="POST" action="{{ $mainSiteRoute('logout') }}" class="inline-flex">
                            @csrf
                            <button type="submit" data-header-drawer-link class="lc-nav-link">Log out</button>
                        </form>
                    @endif
                @endauth

                @guest
                    @if ($hasLoginRoute)
                        <a href="{{ $mainSiteRoute('login') }}" data-header-drawer-link @if (! $isLms) wire:navigate @endif class="lc-nav-link">
                            Log in
                        </a>
                    @endif

                    @if ($hasRegisterRoute)
                        <a href="{{ $mainSiteRoute('register') }}" data-header-drawer-link @if (! $isLms) wire:navigate @endif class="lc-nav-link">
                            Register
                        </a>
                    @endif
                @endguest
            </div>
        </aside>
    </div>
</header>
