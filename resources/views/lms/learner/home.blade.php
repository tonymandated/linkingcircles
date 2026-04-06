<x-layouts.lms title="LMS Home">
    <section class="rounded-xl border border-zinc-200 bg-white p-8">
        <h1 class="font-serif text-3xl font-semibold text-zinc-900">Welcome to the LMS</h1>
        <p class="mt-3 max-w-3xl text-zinc-700">
            Browse our learning catalog and continue your academic journey with accessible, structured lessons.
        </p>
        <p class="mt-4 text-sm text-zinc-600">
            Published courses available: <span class="font-semibold text-zinc-900">{{ $courseCount }}</span>
        </p>
        <a href="{{ route('lms.courses.index') }}" class="mt-6 inline-flex rounded-md bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-800">
            Explore courses
        </a>
    </section>
</x-layouts.lms>
