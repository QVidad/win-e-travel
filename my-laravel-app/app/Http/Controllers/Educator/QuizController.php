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
    public function index(Request $request): Response
    {
        $modules = CourseModule::with(['questions', 'updatedBy', 'lessons'])->orderBy('order')->get();

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
            'module_id' => 'nullable|exists:course_modules,id',
            'course_module_id' => 'nullable|exists:course_modules,id',
            'module_lesson_id' => 'nullable|exists:module_lessons,id',
            'question_text' => 'nullable|string|max:1000',
            'question' => 'nullable|string|max:1000',
            'option_a' => 'nullable|string|max:255',
            'option_b' => 'nullable|string|max:255',
            'option_c' => 'nullable|string|max:255',
            'option_d' => 'nullable|string|max:255',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
            'correct_option' => 'nullable|string|in:a,b,c,d,A,B,C,D',
            'correct_answer_index' => 'nullable|integer|min:0|max:3',
            'explanation' => 'nullable|string|max:1000',
        ]);

        $moduleId = $validated['module_id'] ?? $validated['course_module_id'] ?? null;
        if (!$moduleId) {
            return redirect()->back()->withErrors(['module_id' => 'The target module field is required.']);
        }

        $questionText = $validated['question_text'] ?? $validated['question'] ?? '';
        if (empty(trim($questionText))) {
            return redirect()->back()->withErrors(['question_text' => 'The question text field is required.']);
        }

        $optionsArray = $validated['options'] ?? [];
        $optA = $validated['option_a'] ?? ($optionsArray[0] ?? '');
        $optB = $validated['option_b'] ?? ($optionsArray[1] ?? '');
        $optC = $validated['option_c'] ?? ($optionsArray[2] ?? '');
        $optD = $validated['option_d'] ?? ($optionsArray[3] ?? '');

        $correctOpt = strtolower($validated['correct_option'] ?? '');
        if (!in_array($correctOpt, ['a', 'b', 'c', 'd'])) {
            $idx = (int)($validated['correct_answer_index'] ?? 0);
            $correctOpt = match ($idx) {
                1 => 'b',
                2 => 'c',
                3 => 'd',
                default => 'a',
            };
        } else {
            $idx = match ($correctOpt) {
                'b' => 1,
                'c' => 2,
                'd' => 3,
                default => 0,
            };
        }

        $optionsCombined = [$optA, $optB, $optC, $optD];

        QuizQuestion::create([
            'module_id' => $moduleId,
            'course_module_id' => $moduleId,
            'question_text' => $questionText,
            'question' => $questionText,
            'option_a' => $optA,
            'option_b' => $optB,
            'option_c' => $optC,
            'option_d' => $optD,
            'correct_option' => $correctOpt,
            'options' => $optionsCombined,
            'correct_answer_index' => $idx,
            'explanation' => $validated['explanation'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        $module = CourseModule::find($moduleId);
        if ($module) {
            $module->update([
                'updated_by' => $request->user()->id,
                'last_modified_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Quiz question added to pool successfully.');
    }

    /**
     * Update an existing question item in the pool.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $questionItem = QuizQuestion::findOrFail($id);

        $validated = $request->validate([
            'module_id' => 'nullable|exists:course_modules,id',
            'course_module_id' => 'nullable|exists:course_modules,id',
            'module_lesson_id' => 'nullable|exists:module_lessons,id',
            'question_text' => 'nullable|string|max:1000',
            'question' => 'nullable|string|max:1000',
            'option_a' => 'nullable|string|max:255',
            'option_b' => 'nullable|string|max:255',
            'option_c' => 'nullable|string|max:255',
            'option_d' => 'nullable|string|max:255',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
            'correct_option' => 'nullable|string|in:a,b,c,d,A,B,C,D',
            'correct_answer_index' => 'nullable|integer|min:0|max:3',
            'explanation' => 'nullable|string|max:1000',
        ]);

        $moduleId = $validated['module_id'] ?? $validated['course_module_id'] ?? $questionItem->module_id;
        $questionText = $validated['question_text'] ?? $validated['question'] ?? $questionItem->question_text;

        $optionsArray = $validated['options'] ?? [];
        $optA = $validated['option_a'] ?? ($optionsArray[0] ?? $questionItem->option_a);
        $optB = $validated['option_b'] ?? ($optionsArray[1] ?? $questionItem->option_b);
        $optC = $validated['option_c'] ?? ($optionsArray[2] ?? $questionItem->option_c);
        $optD = $validated['option_d'] ?? ($optionsArray[3] ?? $questionItem->option_d);

        $correctOpt = strtolower($validated['correct_option'] ?? '');
        if (!in_array($correctOpt, ['a', 'b', 'c', 'd'])) {
            if (isset($validated['correct_answer_index'])) {
                $idx = (int)$validated['correct_answer_index'];
                $correctOpt = match ($idx) {
                    1 => 'b',
                    2 => 'c',
                    3 => 'd',
                    default => 'a',
                };
            } else {
                $correctOpt = $questionItem->correct_option ?? 'a';
                $idx = $questionItem->correct_answer_index;
            }
        } else {
            $idx = match ($correctOpt) {
                'b' => 1,
                'c' => 2,
                'd' => 3,
                default => 0,
            };
        }

        $optionsCombined = [$optA, $optB, $optC, $optD];

        $questionItem->update([
            'module_id' => $moduleId,
            'course_module_id' => $moduleId,
            'question_text' => $questionText,
            'question' => $questionText,
            'option_a' => $optA,
            'option_b' => $optB,
            'option_c' => $optC,
            'option_d' => $optD,
            'correct_option' => $correctOpt,
            'options' => $optionsCombined,
            'correct_answer_index' => $idx,
            'explanation' => $validated['explanation'] ?? null,
            'module_lesson_id' => $validated['module_lesson_id'] ?? null,
        ]);

        $module = CourseModule::find($moduleId);
        if ($module) {
            $module->update([
                'updated_by' => $request->user()->id,
                'last_modified_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Quiz question updated successfully.');
    }

    /**
     * Delete a question item from a module's quiz pool.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $question = QuizQuestion::findOrFail($id);
        $module = $question->module ?? $question->courseModule;

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
