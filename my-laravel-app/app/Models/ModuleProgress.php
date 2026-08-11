<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleProgress extends Model
{
    use HasFactory;

    protected $table = 'module_progress';

    protected $fillable = [
        'user_id',
        'course_module_id',
        'score_percentage',
        'passed',
        'unlocked',
    ];

    protected $casts = [
        'score_percentage' => 'float',
        'passed' => 'boolean',
        'unlocked' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courseModule(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class);
    }

    public static function hasPassed(float $score): bool
    {
        return $score >= 90.0;
    }
}
