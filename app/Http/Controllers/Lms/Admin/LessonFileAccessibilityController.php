<?php

declare(strict_types=1);

namespace App\Http\Controllers\Lms\Admin;

use App\Http\Controllers\Controller;
use App\Models\LessonFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LessonFileAccessibilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Show accessibility editing form
     */
    public function edit(LessonFile $file): View
    {
        $this->authorize('lesson_files.update');

        $file->load('accessibilityData');
        if (! $file->accessibilityData) {
            $file->accessibilityData()->create();
        }

        return view('lms.admin.lesson-files.accessibility', ['file' => $file]);
    }

    /**
     * Update accessibility data
     */
    public function update(Request $request, LessonFile $file)
    {
        $this->authorize('lesson_files.update');

        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:500',
            'extended_description' => 'nullable|string|max:2000',
            'audio_description' => 'nullable|string|max:2000',
            'transcript_file' => 'nullable|file|mimes:vtt,srt|max:10240',
            'transcript_language' => 'nullable|string|max:10',
            'sign_language_video' => 'nullable|file|mimes:mp4,webm|max:512000',
        ]);

        // Update alt text
        if (isset($validated['alt_text'])) {
            $file->update(['alt_text' => $validated['alt_text']]);
        }

        $accessibility = $file->accessibilityData ?? $file->accessibilityData()->create();

        // Update accessibility metadata
        $accessibility->update([
            'extended_description' => $validated['extended_description'] ?? $accessibility->extended_description,
            'audio_description' => $validated['audio_description'] ?? $accessibility->audio_description,
            'transcript_language' => $validated['transcript_language'] ?? $accessibility->transcript_language,
        ]);

        // Handle transcript upload
        if ($request->hasFile('transcript_file')) {
            // Delete old transcript if exists
            if ($accessibility->transcript_file_path) {
                Storage::disk('captions')->delete($accessibility->transcript_file_path);
            }

            $transcript = $request->file('transcript_file');
            $transcriptName = time().'_'.$transcript->getClientOriginalName();
            $transcriptPath = $transcript->storeAs('transcripts', $transcriptName, 'captions');

            $accessibility->update([
                'transcript_file_path' => $transcriptPath,
                'transcript_preview' => substr($transcript->getContent(), 0, 200),
            ]);
        }

        // Handle sign language video upload
        if ($request->hasFile('sign_language_video')) {
            // Delete old video if exists
            if ($accessibility->sign_language_video_path) {
                Storage::disk('media')->delete($accessibility->sign_language_video_path);
            }

            $video = $request->file('sign_language_video');
            $videoName = time().'_sign_language_'.$video->getClientOriginalName();
            $videoPath = $video->storeAs('sign-language', $videoName, 'media');

            $accessibility->update(['sign_language_video_path' => $videoPath]);
        }

        // Mark accessibility as complete if all required fields are filled
        if ($file->isAccessibilityComplete()) {
            $accessibility->markComplete();
        }

        return redirect()->back()->with('success', 'Accessibility data updated successfully.');
    }
}
