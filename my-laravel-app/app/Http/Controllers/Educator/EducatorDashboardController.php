<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\ContentSection;
use App\Models\Destination;
use App\Models\MediaAsset;
use App\Models\Town;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class EducatorDashboardController extends Controller
{
    public function index(): Response
    {
        $towns = Town::with('destinations')->orderBy('order')->get();
        $mediaAssets = MediaAsset::with('uploader')->latest()->get();
        $contentSections = ContentSection::all();

        return Inertia::render('Educator/Dashboard', [
            'towns' => $towns,
            'mediaAssets' => $mediaAssets,
            'contentSections' => $contentSections,
        ]);
    }

    public function updateTown(Request $request, Town $town): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'difficulty_level' => 'required|string',
        ]);

        $town->update($validated);

        return redirect()->back()->with('success', 'Town content updated successfully.');
    }

    public function updateDestination(Request $request, Destination $destination): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'history' => 'nullable|string',
            'significance' => 'nullable|string',
            'coordinates' => 'nullable|string',
            'is_visible' => 'required|boolean',
        ]);

        $destination->update($validated);

        return redirect()->back()->with('success', 'Destination updated successfully.');
    }

    public function storeMedia(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:20480', // 20MB max
        ]);

        $file = $request->file('file');
        $filePath = $file->store('media', 'public');
        $fileType = str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image';

        MediaAsset::create([
            'title' => $request->title,
            'file_path' => '/storage/' . $filePath,
            'file_type' => $fileType,
            'file_size' => $file->getSize(),
            'uploaded_by' => $request->user()->id,
            'is_visible' => true,
        ]);

        return redirect()->back()->with('success', 'Media asset uploaded successfully.');
    }

    public function updateContentSection(Request $request, ContentSection $section): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'is_visible' => 'required|boolean',
        ]);

        $section->update($validated);

        return redirect()->back()->with('success', 'Content section updated successfully.');
    }
}
