<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ModuleController extends Controller
{
    /**
     * Display a listing of foundation modules and town chapter modules.
     */
    public function index(): Response
    {
        $modules = CourseModule::with(['updatedBy', 'questions'])->orderBy('order')->get();

        $foundationModules = $modules->where('type', 'foundation')->values();
        $townModules = $modules->where('type', 'town_chapter')->values();

        return Inertia::render('Admin/Modules', [
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
     * Toggle module status between draft and published.
     */
    public function toggleStatus(Request $request, $id): RedirectResponse
    {
        $module = CourseModule::findOrFail($id);
        $newStatus = $module->status === 'published' ? 'draft' : 'published';

        $module->update([
            'status' => $newStatus,
            'updated_by' => $request->user()->id,
            'last_modified_at' => now(),
        ]);

        $statusText = ucfirst($newStatus);
        return redirect()->back()->with('success', "Module '{$module->title}' status set to {$statusText}.");
    }

    /**
     * Store a new multiple-choice question in the module's shared quiz pool.
     */
    public function storeQuestion(Request $request, $id): RedirectResponse
    {
        $module = CourseModule::findOrFail($id);

        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'options' => 'required|array|min:2|max:6',
            'options.*' => 'required|string|max:255',
            'correct_answer_index' => 'required|integer|min:0',
            'explanation' => 'nullable|string|max:1000',
        ]);

        QuizQuestion::create([
            'course_module_id' => $module->id,
            'question' => $validated['question'],
            'options' => $validated['options'],
            'correct_answer_index' => $validated['correct_answer_index'],
            'explanation' => $validated['explanation'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        $module->update([
            'updated_by' => $request->user()->id,
            'last_modified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Question added to shared quiz pool successfully.');
    }

    /**
     * Delete a question from a module's quiz pool.
     */
    public function deleteQuestion(Request $request, QuizQuestion $question): RedirectResponse
    {
        $module = $question->courseModule;
        $question->delete();

        if ($module) {
            $module->update([
                'updated_by' => $request->user()->id,
                'last_modified_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Question removed from quiz pool.');
    }
}
