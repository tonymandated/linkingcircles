<x-layouts.lms :title="$lesson->title">
    <a href="{{ route('lms.courses.show', $course) }}" class="text-sm font-semibold text-sky-700 hover:text-sky-800">
        Back to {{ $course->title }}
    </a>

    <article class="mt-4 rounded-xl border border-zinc-200 bg-white p-8">
        <h1 class="font-serif text-3xl font-semibold text-zinc-900">{{ $lesson->title }}</h1>
        @if ($lesson->summary)
            <p class="mt-3 text-sm text-zinc-700">{{ $lesson->summary }}</p>
        @endif

        <div class="prose prose-zinc mt-6 max-w-none">
            {{ $lesson->content }}
        </div>
    </article>
</x-layouts.lms>
