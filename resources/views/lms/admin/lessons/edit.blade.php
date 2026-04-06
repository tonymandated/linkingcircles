<x-layouts.lms :title="'Edit '.$lesson->title">
    <h1 class="font-serif text-3xl font-semibold text-zinc-900">Edit Lesson</h1>

    <form method="POST" action="{{ route('lms.admin.lessons.update', $lesson) }}" class="mt-8 space-y-5 rounded-xl border border-zinc-200 bg-white p-6">
        @csrf
        @method('PUT')

        <div>
            <label for="course_id" class="text-sm font-medium text-zinc-900">Course</label>
            <select id="course_id" name="course_id" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected((int) old('course_id', $lesson->course_id) === $course->id)>{{ $course->title }}</option>
                @endforeach
            </select>
            @error('course_id')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="title" class="text-sm font-medium text-zinc-900">Title</label>
            <input id="title" name="title" type="text" value="{{ old('title', $lesson->title) }}" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            @error('title')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="slug" class="text-sm font-medium text-zinc-900">Slug</label>
            <input id="slug" name="slug" type="text" value="{{ old('slug', $lesson->slug) }}" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            @error('slug')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="summary" class="text-sm font-medium text-zinc-900">Summary</label>
            <textarea id="summary" name="summary" rows="3" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">{{ old('summary', $lesson->summary) }}</textarea>
            @error('summary')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="content" class="text-sm font-medium text-zinc-900">Content</label>
            <textarea id="content" name="content" rows="6" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">{{ old('content', $lesson->content) }}</textarea>
            @error('content')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="position" class="text-sm font-medium text-zinc-900">Position</label>
            <input id="position" name="position" type="number" min="1" value="{{ old('position', $lesson->position) }}" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            @error('position')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-2">
            <input id="is_published" name="is_published" type="checkbox" value="1" @checked((bool) old('is_published', $lesson->is_published))>
            <label for="is_published" class="text-sm font-medium text-zinc-900">Publish lesson</label>
        </div>

        <div>
            <label for="published_at" class="text-sm font-medium text-zinc-900">Published At (optional)</label>
            <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', $lesson->published_at?->format('Y-m-d\\TH:i')) }}" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            @error('published_at')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="rounded-md bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-800">Save changes</button>
    </form>
</x-layouts.lms>
