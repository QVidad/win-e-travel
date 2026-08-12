<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('town_chapter'); // foundation, town_chapter
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->string('status')->default('published'); // draft, published
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('last_modified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->onDelete('cascade');
            $table->foreignId('course_module_id')->nullable()->constrained('course_modules')->onDelete('cascade');
            $table->text('question_text')->nullable();
            $table->text('question')->nullable();
            $table->string('option_a')->nullable();
            $table->string('option_b')->nullable();
            $table->string('option_c')->nullable();
            $table->string('option_d')->nullable();
            $table->enum('correct_option', ['a', 'b', 'c', 'd'])->default('a');
            $table->json('options')->nullable();
            $table->integer('correct_answer_index')->default(0);
            $table->text('explanation')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('module_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_module_id')->constrained('course_modules')->onDelete('cascade');
            $table->decimal('score_percentage', 5, 2)->default(0.00);
            $table->boolean('passed')->default(false); // true if score_percentage >= 90.00
            $table->boolean('unlocked')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_progress');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('course_modules');
    }
};
