<x-layouts::app :title="__('Dashboard')">
    <section class="space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h1 class="font-serif text-3xl font-semibold text-heading dark:text-white md:text-4xl">Welcome to your dashboard</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-content dark:text-zinc-400">
                Manage your learning journey, account preferences, and quick links to the LMS from one place.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <article class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-heading dark:text-white">My Learning</h2>
                <p class="mt-2 text-sm text-content dark:text-zinc-400">Browse courses and continue where you left off in the LMS.</p>
                <a href="{{ route('lms.courses.index') }}" class="mt-4 inline-flex rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">
                    View Course Catalog
                </a>
            </article>

            <article class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-heading dark:text-white">Profile Settings</h2>
                <p class="mt-2 text-sm text-content dark:text-zinc-400">Update your personal details and account information.</p>
                <a href="{{ route('profile.edit') }}" wire:navigate class="mt-4 inline-flex rounded-md border border-zinc-300 px-4 py-2 text-sm font-semibold text-heading hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                    Edit Profile
                </a>
            </article>

            <article class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-heading dark:text-white">Accessibility Preferences</h2>
                <p class="mt-2 text-sm text-content dark:text-zinc-400">Adjust appearance and accessibility controls for your experience.</p>
                <a href="{{ route('appearance.edit') }}" wire:navigate class="mt-4 inline-flex rounded-md border border-zinc-300 px-4 py-2 text-sm font-semibold text-heading hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                    Appearance Settings
                </a>
            </article>
        </div>

        @can('dashboard.view')
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-heading dark:text-white">Admin Tools</h2>
                <p class="mt-2 text-sm text-content dark:text-zinc-400">Manage LMS content and platform activity.</p>
                <a href="{{ route('lms.admin.courses.index') }}" class="mt-4 inline-flex rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">
                    Open Admin Courses
                </a>
            </div>
        @endcan
    </section>
</x-layouts::app>
