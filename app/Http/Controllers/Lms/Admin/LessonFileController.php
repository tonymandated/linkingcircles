<?php

declare(strict_types=1);

namespace App\Http\Controllers\Lms\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LessonFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Display files for a lesson
     */
    public function index(Lesson $lesson): View
    {
        $this->authorize('lesson_files.view');

        $lesson->load('files.uploader', 'files.accessibilityData');

        return view('lms.admin.lesson-files.index', [
            'lesson' => $lesson,
            'files' => $lesson->files()->with('uploader', 'accessibilityData')->paginate(15),
        ]);
    }

    /**
     * Show form for uploading files
     */
    public function create(Lesson $lesson): View
    {
        $this->authorize('lesson_files.create');

        return view('lms.admin.lesson-files.create', ['lesson' => $lesson]);
    }

    /**
     * Store uploaded file
     */
    public function store(Request $request, Lesson $lesson)
    {
        $this->authorize('lesson_files.create');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:512000', // 500MB
            'file_type' => 'required|in:video,audio,image,document',
            'description' => 'nullable|string|max:1000',
            'alt_text' => 'nullable|string|max:500',
            'transcript_file' => 'nullable|file|mimes:vtt,srt|max:10240', // 10MB
        ]);

        $file = $request->file('file');
        $fileName = time().'_'.$file->getClientOriginalName();
        $filePath = $file->storeAs('lesson-files', $fileName, 'media');

        $lessonFile = LessonFile::create([
            'lesson_id' => $lesson->id,
            'uploaded_by' => auth()->id(),
            'title' => $validated['title'],
            'file_name' => $fileName,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'file_path' => $filePath,
            'disk' => 'media',
            'file_type' => $validated['file_type'],
            'description' => $validated['description'] ?? null,
            'alt_text' => $validated['alt_text'] ?? null,
            'is_published' => true,
            'published_at' => now(),
        ]);

        // Handle transcript upload if present
        if ($request->hasFile('transcript_file')) {
            $transcript = $request->file('transcript_file');
            $transcriptName = time().'_'.$transcript->getClientOriginalName();
            $transcriptPath = $transcript->storeAs('transcripts', $transcriptName, 'captions');

            $lessonFile->accessibilityData()->create([
                'transcript_file_path' => $transcriptPath,
                'transcript_disk' => 'captions',
                'transcript_preview' => substr($transcript->getContent(), 0, 200),
                'transcript_language' => 'en',
            ]);
        } elseif (in_array($validated['file_type'], ['video', 'audio'])) {
            // Create accessibility record even without transcript for now
            $lessonFile->accessibilityData()->create([
                'transcript_file_path' => null,
                'transcript_disk' => 'captions',
                'transcript_language' => 'en',
            ]);
        }

        return redirect()->route('lms.admin.lesson-files.index', $lesson)->with('success', 'File uploaded successfully.');
    }

    /**
     * Show file edit form
     */
    public function edit(LessonFile $file): View
    {
        $this->authorize('lesson_files.update');

        $file->load('accessibilityData');

        return view('lms.admin.lesson-files.edit', ['file' => $file]);
    }

    /**
     * Update file metadata
     */
    public function update(Request $request, LessonFile $file)
    {
        $this->authorize('lesson_files.update');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'alt_text' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $file->update($validated);

        return redirect()->route('lms.admin.lesson-files.index', $file->lesson)->with('success', 'File updated successfully.');
    }

    /**
     * Delete file
     */
    public function destroy(LessonFile $file)
    {
        $this->authorize('lesson_files.delete');

        $lesson = $file->lesson;

        // Delete from storage
        Storage::disk($file->disk)->delete($file->file_path);

        // Delete transcript if exists
        if ($file->accessibilityData?->transcript_file_path) {
            Storage::disk('captions')->delete($file->accessibilityData->transcript_file_path);
        }

        $file->delete();

        return redirect()->route('lms.admin.lesson-files.index', $lesson)->with('success', 'File deleted successfully.');
    }
}
