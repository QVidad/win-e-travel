<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Educator\DashboardController as EducatorDashboardController;
use App\Http\Controllers\Educator\LessonController;
use App\Http\Controllers\Educator\ModuleController as EducatorModuleController;
use App\Http\Controllers\Educator\PerformanceController;
use App\Http\Controllers\Educator\QuizController as EducatorQuizController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\AchievementController;
use App\Http\Controllers\Student\FoundationController;
use App\Http\Controllers\Student\HomeController;
use App\Http\Controllers\Student\ModuleController as StudentModuleController;
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
    Route::get('/student/modules', [StudentModuleController::class, 'index'])->name('student.modules.index');
    Route::get('/student/modules/{id}', [StudentModuleController::class, 'show'])->name('student.modules.show');
    Route::post('/student/modules/{id}/progress', [StudentModuleController::class, 'saveProgress'])->name('student.modules.progress');
    Route::get('/towns', [TownController::class, 'index'])->name('towns.index');
    Route::get('/towns/{slug}', [TownController::class, 'show'])->name('towns.show');
    Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
    Route::get('/foundation', [FoundationController::class, 'index'])->name('foundation.index');
    Route::get('/simulations', [SimulationController::class, 'index'])->name('simulation.index');
    Route::get('/simulation/final', [SimulationController::class, 'finalBoss'])->name('simulation.final');
    Route::get('/simulation/{id}', [SimulationController::class, 'show'])->name('simulation.show');
    Route::post('/simulation/validate', [SimulationController::class, 'validateSpeech'])->name('simulation.validate');
    Route::post('/simulation/{id}/complete', [SimulationController::class, 'complete'])->name('simulation.complete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Educator Panel CMS Routes (Educator & Admin)
Route::middleware(['auth'])->prefix('educator')->name('educator.')->group(function () {
    Route::get('/dashboard', [EducatorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/modules', [EducatorModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{id}/edit', [EducatorModuleController::class, 'edit'])->name('modules.edit');
    Route::put('/modules/{id}', [EducatorModuleController::class, 'update'])->name('modules.update');
    Route::post('/modules/reorder', [EducatorModuleController::class, 'reorder'])->name('modules.reorder');
    Route::post('/modules/{module}/simulation', [EducatorModuleController::class, 'updateSimulation'])->name('modules.simulation.update');

    Route::post('/modules/{module}/lessons', [LessonController::class, 'store'])->name('modules.lessons.store');
    Route::put('/modules/{module}/lessons/{lesson}', [LessonController::class, 'update'])->name('modules.lessons.update');
    Route::delete('/modules/{module}/lessons/{lesson}', [LessonController::class, 'destroy'])->name('modules.lessons.destroy');
    Route::post('/modules/{module}/lessons/reorder', [LessonController::class, 'reorder'])->name('modules.lessons.reorder');

    Route::get('/quizzes', [EducatorQuizController::class, 'index'])->name('quizzes.index');
    Route::post('/quizzes', [EducatorQuizController::class, 'store'])->name('quizzes.store');
    Route::put('/quizzes/{id}', [EducatorQuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{id}', [EducatorQuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');
    Route::get('/performance/export', [PerformanceController::class, 'export'])->name('performance.export');
    Route::get('/performance/{id}', [PerformanceController::class, 'show'])->name('performance.show');
});

// Admin Panel Routes (Admin Only)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');

    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students/{id}/reset', [StudentController::class, 'resetProgress'])->name('students.reset');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::patch('/modules/{id}/toggle', [ModuleController::class, 'toggleStatus'])->name('modules.toggle');
    Route::post('/modules/{module}/toggle-status', [ModuleController::class, 'toggleStatus'])->name('modules.toggle-status');
    Route::post('/modules/{module}/questions', [ModuleController::class, 'storeQuestion'])->name('modules.questions.store');
    Route::delete('/modules/questions/{question}', [ModuleController::class, 'deleteQuestion'])->name('modules.questions.destroy');

    Route::post('/users', [AdminDashboardController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');
});

require __DIR__.'/auth.php';
