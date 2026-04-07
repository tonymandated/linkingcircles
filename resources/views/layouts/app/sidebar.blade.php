<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="w-full basis-full">
            <x-unified-header :show-backend-sidebar-toggle="true" />
        </div>

        <div class="flex w-full">
            <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <x-backend.sidebar-menu mode="flux" />

            <flux:spacer />


                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            </flux:sidebar>

            {{ $slot }}
        </div>

        @fluxScripts
    </body>
</html>
