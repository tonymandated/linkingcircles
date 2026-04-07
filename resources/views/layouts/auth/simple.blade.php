<x-layouts.marketing :title="$title ?? __('Authentication')">
    <section class="lc-container py-16 md:py-20" aria-labelledby="auth-page-title">
        <div class="mx-auto w-full max-w-xl">
            <h1 id="auth-page-title" class="sr-only">{{ $title ?? __('Authentication') }}</h1>

            <div class="lc-auth-shell">
                <div class="mb-6 text-center">
{{--                    <p class="text-xs font-semibold uppercase tracking-wide text-primary">Linking Circles Academy</p>--}}
                </div>

                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </section>

    @fluxScripts
</x-layouts.marketing>
