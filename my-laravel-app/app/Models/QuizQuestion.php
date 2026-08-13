<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'course_module_id',
        'module_lesson_id',
        'question_text',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'options',
        'correct_answer_index',
        'explanation',
        'created_by',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    protected $appends = [
        'question_text',
        'options',
        'correct_answer_index',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function courseModule(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function moduleLesson(): BelongsTo
    {
        return $this->belongsTo(ModuleLesson::class, 'module_lesson_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getQuestionTextAttribute(): string
    {
        return $this->attributes['question_text'] ?? $this->attributes['question'] ?? '';
    }

    public function getQuestionAttribute(): string
    {
        return $this->attributes['question'] ?? $this->attributes['question_text'] ?? '';
    }

    public function getOptionsAttribute(): array
    {
        if (!empty($this->attributes['options'])) {
            $decoded = is_string($this->attributes['options']) ? json_decode($this->attributes['options'], true) : $this->attributes['options'];
            if (is_array($decoded) && count($decoded) > 0) {
                return $decoded;
            }
        }
        return array_values(array_filter([
            $this->attributes['option_a'] ?? null,
            $this->attributes['option_b'] ?? null,
            $this->attributes['option_c'] ?? null,
            $this->attributes['option_d'] ?? null,
        ], fn($v) => !is_null($v)));
    }

    public function getCorrectAnswerIndexAttribute(): int
    {
        if (isset($this->attributes['correct_answer_index']) && !is_null($this->attributes['correct_answer_index'])) {
            return (int) $this->attributes['correct_answer_index'];
        }
        $char = strtolower($this->attributes['correct_option'] ?? 'a');
        return match ($char) {
            'b' => 1,
            'c' => 2,
            'd' => 3,
            default => 0,
        };
    }
}
