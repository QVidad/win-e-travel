<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use Illuminate\Support\Facades\Cache;

class ModuleController extends Controller
{
    /**
     * Display listing of foundation modules and town chapter modules for educators.
     */
    public function index(): Response
    {
        $modules = CourseModule::with(['updatedBy', 'questions'])->orderBy('order')->get();

        $foundationModules = $modules->where('type', 'foundation')->values();
        $townModules = $modules->where('type', 'town_chapter')->values();

        return Inertia::render('Educator/Modules/Index', [
            'foundationModules' => $foundationModules,
            'townModules' => $townModules,
            'stats' => [
                'totalModules' => $modules->count(),
                'publishedCount' => $modules->where('status', 'published')->count(),
                'draftCount' => $modules->where('status', 'draft')->count(),
                'totalQuestions' => QuizQuestion::count(),
            ],
        ]);
    }

    /**
     * Show form to edit module text/media/content.
     */
    public function edit($id): Response
    {
        $module = CourseModule::with([
            'updatedBy', 
            'questions' => function($query) {
                $query->whereNull('module_lesson_id');
            }, 
            'lessons' => function($query) {
                $query->with('questions')->orderBy('order');
            }
        ])->findOrFail($id);

        $bankCount = QuizQuestion::where('module_id', $id)->whereNull('module_lesson_id')->count();
        if ($bankCount === 0) {
            $bankCount = QuizQuestion::where('module_id', $id)->count();
        }

        return Inertia::render('Educator/Modules/Edit', [
            'module' => $module,
            'questionBankCount' => $bankCount,
        ]);
    }

    /**
     * Update module text, description, town chapter details, status, and media.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $module = CourseModule::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'key_spots' => 'nullable|string',
            'cover_image' => 'nullable|string|max:500',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'status' => 'required|in:draft,published',
            'quiz_question_count' => 'required|integer|min:1|max:100',
        ]);

        $bankCount = QuizQuestion::where('module_id', $id)->whereNull('module_lesson_id')->count();
        if ($bankCount === 0) {
            $bankCount = QuizQuestion::where('module_id', $id)->count();
        }

        $requestedCount = (int)$validated['quiz_question_count'];
        if ($requestedCount > $bankCount) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'quiz_question_count' => "Save failed: You specified {$requestedCount} questions for the quiz, but there are only {$bankCount} question(s) available in the question bank for this module. Please lower the question count to {$bankCount} or fewer, or add more questions to the quiz bank first."
                ]);
        }

        $coverImagePath = $module->cover_image;
        if ($request->hasFile('cover_image_file')) {
            $file = $request->file('cover_image_file');
            $path = $file->store('modules', 'public');
            $coverImagePath = '/storage/' . $path;
        } elseif (!empty($validated['cover_image'])) {
            $coverImagePath = $validated['cover_image'];
        }

        $module->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'description' => $validated['description'] ?? null,
            'key_spots' => $validated['key_spots'] ?? null,
            'cover_image' => $coverImagePath,
            'status' => $validated['status'],
            'quiz_question_count' => $requestedCount,
            'updated_by' => $request->user()->id,
            'last_modified_at' => now(),
        ]);

        Cache::forget('published_student_modules');

        return redirect()->route('educator.modules.index')
            ->with('success', 'Module updated successfully.');
    }

    /**
     * Reorder modules sequence.
     */
    public function reorder(Request $request): RedirectResponse
    {
        \Illuminate\Support\Facades\Log::info('Reorder method hit', $request->all());

        try {
            $validated = $request->validate([
                'modules' => 'required|array',
                'modules.*.id' => 'required|exists:course_modules,id',
                'modules.*.order' => 'required|integer',
            ]);
            
            \Illuminate\Support\Facades\Log::info('Validation passed', $validated);

            foreach ($validated['modules'] as $moduleData) {
                CourseModule::where('id', $moduleData['id'])->update(['order' => $moduleData['order']]);
            }

            return redirect()->back()->with('success', 'Module sequence updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Reorder validation failed', $e->errors());
            throw $e;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Reorder exception: ' . $e->getMessage());
            throw $e;
        }
    }
}
