<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\ModuleLesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request, CourseModule $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'integer'
        ]);

        $lesson = $module->lessons()->create([
            'title' => $validated['title'],
            'order' => $validated['order'] ?? ($module->lessons()->max('order') + 1)
        ]);

        return redirect()->back()->with('success', 'Lesson added successfully.');
    }

    public function update(Request $request, CourseModule $module, ModuleLesson $lesson)
    {
        if ($lesson->course_module_id !== $module->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $lesson->update($validated);

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
