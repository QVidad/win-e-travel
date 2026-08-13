<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_modules', function (Blueprint $table) {
            if (!Schema::hasColumn('course_modules', 'quiz_question_count')) {
                $table->integer('quiz_question_count')->default(5)->after('status');
            }
        });

        Schema::table('module_lessons', function (Blueprint $table) {
            if (!Schema::hasColumn('module_lessons', 'quiz_question_count')) {
                $table->integer('quiz_question_count')->default(5)->after('order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('course_modules', function (Blueprint $table) {
            if (Schema::hasColumn('course_modules', 'quiz_question_count')) {
                $table->dropColumn('quiz_question_count');
            }
        });

        Schema::table('module_lessons', function (Blueprint $table) {
            if (Schema::hasColumn('module_lessons', 'quiz_question_count')) {
                $table->dropColumn('quiz_question_count');
            }
        });
    }
};
