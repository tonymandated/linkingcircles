<x-layouts.lms :title="'Edit '.$course->title">
    <h1 class="font-serif text-3xl font-semibold text-zinc-900">Edit Course</h1>

    <form method="POST" action="{{ route('lms.admin.courses.update', $course) }}" class="mt-8 space-y-5 rounded-xl border border-zinc-200 bg-white p-6">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="text-sm font-medium text-zinc-900">Title</label>
            <input id="title" name="title" type="text" value="{{ old('title', $course->title) }}" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            @error('title')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="slug" class="text-sm font-medium text-zinc-900">Slug</label>
            <input id="slug" name="slug" type="text" value="{{ old('slug', $course->slug) }}" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            @error('slug')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="excerpt" class="text-sm font-medium text-zinc-900">Excerpt</label>
            <textarea id="excerpt" name="excerpt" rows="3" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">{{ old('excerpt', $course->excerpt) }}</textarea>
            @error('excerpt')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="description" class="text-sm font-medium text-zinc-900">Description</label>
            <textarea id="description" name="description" rows="6" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">{{ old('description', $course->description) }}</textarea>
            @error('description')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="status" class="text-sm font-medium text-zinc-900">Status</label>
            <select id="status" name="status" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
                <option value="draft" @selected(old('status', $course->status) === 'draft')>Draft</option>
                <option value="published" @selected(old('status', $course->status) === 'published')>Published</option>
            </select>
            @error('status')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="published_at" class="text-sm font-medium text-zinc-900">Published At (optional)</label>
            <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', $course->published_at?->format('Y-m-d\\TH:i')) }}" class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm">
            @error('published_at')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="rounded-md bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-800">Save changes</button>
    </form>
</x-layouts.lms>
