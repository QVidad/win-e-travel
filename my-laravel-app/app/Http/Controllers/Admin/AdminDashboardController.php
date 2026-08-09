<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function index(): Response
    {
        $users = User::latest()->get();

        return Inertia::render('Admin/Dashboard', [
            'users' => $users,
            'stats' => [
                'totalUsers' => count($users),
                'totalAdmins' => $users->where('role', 'admin')->count(),
                'totalEducators' => $users->where('role', 'educator')->count(),
                'totalStudents' => $users->where('role', 'student')->count(),
            ],
        ]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,educator,student',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => 'active',
            'avatar' => $validated['role'] === 'educator' ? '/assets/images/facilitator-female.jpg' : '/assets/images/facilitator-male.jpg',
        ]);

        return redirect()->back()->with('success', 'User account created successfully.');
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,educator,student',
            'status' => 'required|in:active,inactive',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'User account updated successfully.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own admin account.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
