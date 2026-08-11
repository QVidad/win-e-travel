<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers / educators.
     */
    public function index(): Response
    {
        $teachers = User::whereIn('role', ['teacher', 'educator'])
            ->latest()
            ->get();

        return Inertia::render('Admin/Teachers', [
            'teachers' => $teachers,
            'stats' => [
                'totalTeachers' => $teachers->count(),
                'activeTeachers' => $teachers->where('status', 'active')->count(),
            ],
        ]);
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'teacher',
            'status' => 'active',
            'avatar' => '/assets/images/facilitator-female.jpg',
        ]);

        return redirect()->back()->with('success', 'Teacher account created successfully.');
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy(User $teacher): RedirectResponse
    {
        $teacher->delete();

        return redirect()->back()->with('success', 'Teacher account removed successfully.');
    }
}
