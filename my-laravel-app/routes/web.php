<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Educator\EducatorDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\AchievementController;
use App\Http\Controllers\Student\FoundationController;
use App\Http\Controllers\Student\HomeController;
use App\Http\Controllers\Student\SimulationController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\TownController;
use App\Http\Controllers\Student\WelcomeController;
use Illuminate\Support\Facades\Route;

// Public Home Landing
Route::get('/', [HomeController::class, 'index'])->name('home');

// Student Portal Routes (Authenticated Students)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');
    Route::get('/student/welcome', [WelcomeController::class, 'index'])->name('student.welcome');
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/towns', [TownController::class, 'index'])->name('towns.index');
    Route::get('/towns/{slug}', [TownController::class, 'show'])->name('towns.show');
    Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
    Route::get('/foundation', [FoundationController::class, 'index'])->name('foundation.index');
    Route::get('/simulation', [SimulationController::class, 'index'])->name('simulation.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Educator Panel CMS Routes (Educator & Admin)
Route::middleware(['auth', 'role:educator,admin'])->prefix('educator')->name('educator.')->group(function () {
    Route::get('/dashboard', [EducatorDashboardController::class, 'index'])->name('dashboard');
    Route::put('/towns/{town}', [EducatorDashboardController::class, 'updateTown'])->name('towns.update');
    Route::put('/destinations/{destination}', [EducatorDashboardController::class, 'updateDestination'])->name('destinations.update');
    Route::post('/media', [EducatorDashboardController::class, 'storeMedia'])->name('media.store');
    Route::put('/content/{section}', [EducatorDashboardController::class, 'updateContentSection'])->name('content.update');
});

// Admin Panel User Management Routes (Admin Only)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/users', [AdminDashboardController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');
});

require __DIR__.'/auth.php';
