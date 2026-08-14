<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\ModuleLesson;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request, CourseModule $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'key_points' => 'nullable|array',
            'key_points.*.title' => 'nullable|string|max:255',
            'key_points.*.description' => 'nullable|string',
            'key_points.*.icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'quiz_question_count' => 'nullable|integer|min:0|max:100',
            'cover_image' => 'nullable|string|max:500',
            'cover_image_position' => 'nullable|string|max:50',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $requestedCount = (int)($validated['quiz_question_count'] ?? 5);
        $actualBankCount = 0;

        if ($module->type !== 'town_chapter' && $requestedCount > $actualBankCount) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'quiz_question_count' => "Save failed: You specified {$requestedCount} questions for this lesson's quick check quiz, but there are 0 questions available in the question bank for this new lesson. Please add questions to the quiz bank first or set the question count to 0."
                ]);
        }

        $coverImagePath = null;
        if ($request->hasFile('cover_image_file')) {
            $file = $request->file('cover_image_file');
            $path = $file->store('lessons', 'public');
            $coverImagePath = '/storage/' . $path;
        } elseif (!empty($validated['cover_image'])) {
            $coverImagePath = $validated['cover_image'];
        }

        $lesson = $module->lessons()->create([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'key_points' => $validated['key_points'] ?? [],
            'quiz_question_count' => $requestedCount,
            'order' => $validated['order'] ?? ($module->lessons()->max('order') + 1),
            'cover_image' => $coverImagePath,
            'cover_image_position' => $validated['cover_image_position'] ?? 'center 50%',
        ]);

        return redirect()->back()->with('success', 'Lesson added successfully.');
    }

    public function update(Request $request, CourseModule $module, ModuleLesson $lesson)
    {
        if ($lesson->course_module_id !== $module->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'key_points' => 'nullable|array',
            'key_points.*.title' => 'nullable|string|max:255',
            'key_points.*.description' => 'nullable|string',
            'key_points.*.icon' => 'nullable|string|max:100',
            'quiz_question_count' => 'required|integer|min:0|max:100',
            'cover_image' => 'nullable|string|max:500',
            'cover_image_position' => 'nullable|string|max:50',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $requestedCount = (int)$validated['quiz_question_count'];
        $actualBankCount = QuizQuestion::where('module_lesson_id', $lesson->id)->count();

        if ($module->type !== 'town_chapter' && $requestedCount > $actualBankCount) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'quiz_question_count' => "Save failed: You specified {$requestedCount} questions for this lesson's quick check quiz, but there are only {$actualBankCount} question(s) available in the question bank for this lesson. Please lower the question count to {$actualBankCount} or fewer, or add more questions to the quiz bank first."
                ]);
        }

        $coverImagePath = $lesson->cover_image;
        if ($request->hasFile('cover_image_file')) {
            $file = $request->file('cover_image_file');
            $path = $file->store('lessons', 'public');
            $coverImagePath = '/storage/' . $path;
        } elseif (!empty($validated['cover_image'])) {
            $coverImagePath = $validated['cover_image'];
        }

        $lesson->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'key_points' => $validated['key_points'] ?? [],
            'quiz_question_count' => $requestedCount,
            'cover_image' => $coverImagePath,
            'cover_image_position' => $validated['cover_image_position'] ?? 'center 50%',
        ]);

        return redirect()->back()->with('success', 'Lesson updated successfully.');
    }

    public function destroy(CourseModule $module, ModuleLesson $lesson)
    {
        if ($lesson->course_module_id !== $module->id) {
            abort(403);
        }

        $lesson->delete();

        return redirect()->back()->with('success', 'Lesson deleted successfully.');
    }

    public function reorder(Request $request, CourseModule $module)
    {
        $validated = $request->validate([
            'lessons' => 'required|array',
            'lessons.*.id' => 'required|exists:module_lessons,id',
            'lessons.*.order' => 'required|integer',
        ]);

        foreach ($validated['lessons'] as $lessonData) {
            ModuleLesson::where('id', $lessonData['id'])
                ->where('course_module_id', $module->id)
                ->update(['order' => $lessonData['order']]);
        }

        return redirect()->back();
    }
}
