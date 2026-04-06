<x-layouts.lms :title="$course->title">
    <section class="rounded-xl border border-zinc-200 bg-white p-8">
        <h1 class="font-serif text-3xl font-semibold text-zinc-900">{{ $course->title }}</h1>
        @if ($course->description)
            <p class="mt-4 text-sm leading-7 text-zinc-700">{{ $course->description }}</p>
        @endif
    </section>

    <section class="mt-8">
        <h2 class="font-serif text-2xl font-semibold text-zinc-900">Lessons</h2>
        <div class="mt-4 space-y-3">
            @forelse ($course->lessons as $lesson)
                <article class="rounded-lg border border-zinc-200 bg-white p-4">
                    <h3 class="text-lg font-semibold text-zinc-900">{{ $lesson->position }}. {{ $lesson->title }}</h3>
                    @if ($lesson->summary)
                        <p class="mt-1 text-sm text-zinc-700">{{ $lesson->summary }}</p>
                    @endif
                    <a href="{{ route('lms.lessons.show', [$course, $lesson]) }}" class="mt-2 inline-flex text-sm font-semibold text-sky-700 hover:text-sky-800">
                        Open lesson
                    </a>
                </article>
            @empty
                <p class="text-sm text-zinc-600">No lessons published yet.</p>
            @endforelse
        </div>
    </section>
</x-layouts.lms>
