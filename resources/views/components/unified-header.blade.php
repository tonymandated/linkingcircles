@php
    // Determine if we're on the LMS or main site
    $isLms = request()->getHost() === config('lms.domain');

    // Main site navigation items
    $mainItems = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'Offerings', 'route' => 'offerings'],
        ['label' => 'Programs', 'route' => 'programs'],
        ['label' => 'Team', 'route' => 'team'],
        ['label' => 'Blog', 'route' => 'blog'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];

    // LMS navigation items
    $lmsItems = [
        ['label' => 'Home', 'route' => 'lms.home'],
        ['label' => 'Courses', 'route' => 'lms.courses.index'],
    ];

    // Choose items based on current site
    $items = $isLms ? $lmsItems : $mainItems;
@endphp

<header class="border-b border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
    <div class="lc-container flex flex-wrap items-center justify-between gap-4 py-4">
        <!-- Logo / Brand -->
        <a href="{{ $isLms ? route('lms.home') : route('home') }}" class="inline-flex items-center gap-3 flex-shrink-0" wire:navigate>
            @if($isLms)
                <span class="font-semibold text-heading dark:text-white">LMS</span>
            @else
                <img
                    src="{{ asset('img/linking-circles-academy-logo.png') }}"
                    alt="Linking Circles Academy"
                    class="h-12 w-auto"
                >
            @endif
            <span class="sr-only">{{ $isLms ? 'Linking Circles LMS' : 'Linking Circles Academy' }}</span>
        </a>

        <!-- Main Navigation -->
        <div class="flex items-center gap-6 flex-1 justify-center">
            <nav aria-label="Primary navigation">
                <ul class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2">
                    @foreach ($items as $item)
                        <li>
                            <a
                                href="{{ route($item['route']) }}"
                                wire:navigate
                                @class([
                                    'lc-nav-link',
                                    'lc-nav-link-active' => request()->routeIs($item['route']) ||
                                        ($item['route'] === 'home' && request()->routeIs('home')) ||
                                        ($item['route'] === 'lms.home' && request()->routeIs('lms.home')),
                                ])
                            >
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <!-- Site Switcher & Auth Links -->
            <div class="flex items-center gap-4 border-l border-zinc-200 dark:border-zinc-700 pl-4">
                @if($isLms)
                    <!-- On LMS: Show link to main site -->
                    <a
                        href="{{ route('home') }}"
                        wire:navigate
                        class="text-xs font-semibold uppercase tracking-wide text-primary hover:text-heading dark:hover:text-white transition-colors"
                        title="Visit main website"
                    >
                        ← Main Site
                    </a>
                @else
                    <!-- On Main Site: Show link to LMS -->
                    <a
                        href="{{ route('lms.home') }}"
                        wire:navigate
                        class="text-xs font-semibold uppercase tracking-wide text-primary hover:text-heading dark:hover:text-white transition-colors"
                        title="Visit learning management system"
                    >
                        LMS →
                    </a>
                @endif

                @auth
                    @if(!$isLms)
                        <!-- Main site: Show dashboard link -->
                        <a
                            href="{{ route('dashboard') }}"
                            wire:navigate
                            class="lc-nav-link"
                        >
                            Dashboard
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Accessibility Controls -->
        <div x-data="setupAccessibilityControls()" x-init="init()" class="border-l border-zinc-200 dark:border-zinc-700 pl-6 flex-shrink-0">
            <x-accessibility-controls />
        </div>
    </div>
</header>
