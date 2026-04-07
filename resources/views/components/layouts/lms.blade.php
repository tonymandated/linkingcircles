<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @php
        $showBackendSidebar = auth()->check() && request()->routeIs('lms.admin.*');
    @endphp

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>{{ $title ?? 'LMS' }} - {{ config('app.name') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 antialiased">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:rounded-md focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:font-semibold">
            Skip to main content
        </a>

        <x-unified-header :show-backend-sidebar-toggle="$showBackendSidebar" />

        <main id="main-content" class="mx-auto w-full max-w-7xl px-6 py-10 lg:px-8">
            @if (session('status'))
                <p role="status" class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </p>
            @endif

            @if ($showBackendSidebar)
                <div class="grid gap-8 lg:grid-cols-[280px_minmax(0,1fr)]">
                    <aside>
                        <x-backend.sidebar-menu />
                    </aside>

                    <div>
                        {{ $slot }}
                    </div>
                </div>
            @else
                {{ $slot }}
            @endif
        </main>
    </body>
</html>
