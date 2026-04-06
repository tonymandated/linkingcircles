<x-layouts.lms title="Courses">
    <h1 class="font-serif text-3xl font-semibold text-zinc-900">Courses</h1>

    <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($courses as $course)
            <article class="rounded-xl border border-zinc-200 bg-white p-6">
                <h2 class="font-serif text-2xl font-semibold text-zinc-900">{{ $course->title }}</h2>
                <p class="mt-3 text-sm leading-7 text-zinc-700">{{ $course->excerpt }}</p>
                <a href="{{ route('lms.courses.show', $course) }}" class="mt-4 inline-flex text-sm font-semibold text-sky-700 hover:text-sky-800">
                    View course
                </a>
            </article>
        @empty
            <p class="text-sm text-zinc-600">No published courses yet.</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $courses->links() }}
    </div>
</x-layouts.lms>
