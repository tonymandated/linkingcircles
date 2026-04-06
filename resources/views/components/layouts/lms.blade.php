<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>{{ $title ?? 'LMS' }} - {{ config('app.name') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:rounded-md focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:font-semibold">
            Skip to main content
        </a>

        <header class="border-b border-zinc-200 bg-white">
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
                <a href="{{ route('lms.home') }}" class="font-semibold text-zinc-900">
                    Linking Circles LMS
                </a>

                <nav aria-label="LMS navigation" class="flex items-center gap-4 text-sm">
                    <a href="{{ route('lms.home') }}" class="hover:text-sky-700">Home</a>
                    <a href="{{ route('lms.courses.index') }}" class="hover:text-sky-700">Courses</a>
                    @auth
                        <a href="{{ route('lms.admin.courses.index') }}" class="hover:text-sky-700">Admin</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main id="main-content" class="mx-auto w-full max-w-7xl px-6 py-10 lg:px-8">
            @if (session('status'))
                <p role="status" class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </p>
            @endif

            {{ $slot }}
        </main>
    </body>
</html>
