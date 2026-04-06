<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light" data-theme="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>{{ $title }} - Linking Circles Academy</title>
        <meta name="description" content="{{ $description ?? 'Serving academic excellence to African students globally.' }}" />

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">


        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-surface dark:bg-zinc-900 font-sans text-content dark:text-zinc-400 antialiased">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:rounded-md focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:font-medium focus:text-heading">
            Skip to main content
        </a>

        <x-unified-header />

        <main id="main-content">
            {{ $slot }}
        </main>

        <x-marketing.footer />
    </body>
</html>
