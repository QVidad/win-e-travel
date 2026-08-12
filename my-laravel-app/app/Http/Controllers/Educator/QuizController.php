<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QuizController extends Controller
{
    /**
     * Display interface for managing question pools per module.
     */
    public function index(): Response
    {
        $modules = CourseModule::with(['questions', 'updatedBy'])->orderBy('order')->get();

        return Inertia::render('Educator/Quizzes/Index', [
            'modules' => $modules,
            'totalQuestions' => QuizQuestion::count(),
        ]);
    }

    /**
     * Store a new multiple-choice question in a module's quiz pool.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_module_id' => 'required|exists:course_modules,id',
            'question' => 'required|string|max:1000',
            'options' => 'required|array|min:2|max:6',
            'options.*' => 'required|string|max:255',
            'correct_answer_index' => 'required|integer|min:0',
            'explanation' => 'nullable|string|max:1000',
        ]);

        QuizQuestion::create([
            'course_module_id' => $validated['course_module_id'],
            'question' => $validated['question'],
            'options' => $validated['options'],
            'correct_answer_index' => $validated['correct_answer_index'],
            'explanation' => $validated['explanation'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        // Stamp audit log on parent module
        $module = CourseModule::find($validated['course_module_id']);
        if ($module) {
            $module->update([
                'updated_by' => $request->user()->id,
                'last_modified_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Quiz question added to pool successfully.');
    }

    /**
     * Delete a question item from a module's quiz pool.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $question = QuizQuestion::findOrFail($id);
        $module = $question->courseModule;

        $question->delete();

        if ($module) {
            $module->update([
                'updated_by' => $request->user()->id,
                'last_modified_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Question item removed from quiz pool.');
    }
}
