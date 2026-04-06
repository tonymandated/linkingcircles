@php
    $items = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'Offerings', 'route' => 'offerings'],
        ['label' => 'Available Programs', 'route' => 'programs'],
        ['label' => 'The Team', 'route' => 'team'],
        ['label' => 'News Blog', 'route' => 'blog'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
@endphp

<header class="border-b border-zinc-200 bg-white">
    <div class="lc-container flex flex-wrap items-center justify-between gap-4 py-4">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-3" wire:navigate>
            <img
                src="{{ asset('img/linking-circles-academy-logo.png') }}"
                alt="Linking Circles Academy"
                class="h-12 w-auto"
            >
            <span class="sr-only">Linking Circles Academy</span>
        </a>

        <div class="flex items-center gap-6">
            <nav aria-label="Primary navigation">
                <ul class="flex flex-wrap items-center justify-end gap-x-5 gap-y-2">
                    @foreach ($items as $item)
                        <li>
                            <a
                                href="{{ route($item['route']) }}"
                                wire:navigate
                                @class([
                                    'lc-nav-link',
                                    'lc-nav-link-active' => request()->routeIs($item['route']),
                                ])
                            >
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a
                            href="{{ route('lms.home') }}"
                            wire:navigate
                            class="lc-nav-link"
                        >
                            LMS
                        </a>
                    </li>
                    @auth
                        <li>
                            <a
                                href="{{ route('dashboard') }}"
                                wire:navigate
                                class="lc-nav-link"
                            >
                                Dashboard
                            </a>
                        </li>
                    @endauth
                </ul>
            </nav>

            <div x-data="setupAccessibilityControls()" x-init="init()" class="border-l border-zinc-200 pl-6">
                <x-accessibility-controls />
            </div>
        </div>
    </div>
</header>
