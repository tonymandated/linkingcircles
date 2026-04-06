<x-layouts.lms title="Admin Courses">
    <div class="flex items-center justify-between gap-4">
        <h1 class="font-serif text-3xl font-semibold text-zinc-900">Manage Courses</h1>
        <a href="{{ route('lms.admin.courses.create') }}" class="rounded-md bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-800">
            New course
        </a>
    </div>

    <div class="mt-8 overflow-x-auto rounded-xl border border-zinc-200 bg-white">
        <table class="w-full text-left text-sm">
            <caption class="sr-only">LMS courses list</caption>
            <thead class="bg-zinc-50 text-zinc-700">
                <tr>
                    <th scope="col" class="px-4 py-3">Title</th>
                    <th scope="col" class="px-4 py-3">Status</th>
                    <th scope="col" class="px-4 py-3">Published</th>
                    <th scope="col" class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                    <tr class="border-t border-zinc-200">
                        <td class="px-4 py-3 font-medium text-zinc-900">{{ $course->title }}</td>
                        <td class="px-4 py-3">{{ $course->status }}</td>
                        <td class="px-4 py-3">{{ $course->published_at?->toDateString() ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('lms.admin.courses.edit', $course) }}" class="text-sky-700 hover:text-sky-800">Edit</a>
                                <form method="POST" action="{{ route('lms.admin.courses.destroy', $course) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-700 hover:text-red-800">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-zinc-600">No courses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $courses->links() }}
    </div>
</x-layouts.lms>
