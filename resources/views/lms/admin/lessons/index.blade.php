<x-layouts.lms title="Admin Lessons">
    <div class="flex items-center justify-between gap-4">
        <h1 class="font-serif text-3xl font-semibold text-zinc-900">Manage Lessons</h1>
        <a href="{{ route('lms.admin.lessons.create') }}" class="rounded-md bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-800">
            New lesson
        </a>
    </div>

    <div class="mt-8 overflow-x-auto rounded-xl border border-zinc-200 bg-white">
        <table class="w-full text-left text-sm">
            <caption class="sr-only">LMS lessons list</caption>
            <thead class="bg-zinc-50 text-zinc-700">
                <tr>
                    <th scope="col" class="px-4 py-3">Title</th>
                    <th scope="col" class="px-4 py-3">Course</th>
                    <th scope="col" class="px-4 py-3">Position</th>
                    <th scope="col" class="px-4 py-3">Published</th>
                    <th scope="col" class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lessons as $lesson)
                    <tr class="border-t border-zinc-200">
                        <td class="px-4 py-3 font-medium text-zinc-900">{{ $lesson->title }}</td>
                        <td class="px-4 py-3">{{ $lesson->course->title }}</td>
                        <td class="px-4 py-3">{{ $lesson->position }}</td>
                        <td class="px-4 py-3">{{ $lesson->is_published ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('lms.admin.lessons.edit', $lesson) }}" class="text-sky-700 hover:text-sky-800">Edit</a>
                                <form method="POST" action="{{ route('lms.admin.lessons.destroy', $lesson) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-700 hover:text-red-800">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-zinc-600">No lessons found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $lessons->links() }}
    </div>
</x-layouts.lms>
