<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuleLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_module_id',
        'title',
        'content',
        'key_points',
        'order',
        'quiz_question_count',
        'cover_image',
    ];

    protected $casts = [
        'key_points' => 'array',
    ];

    public function courseModule(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class, 'module_lesson_id');
    }
}
