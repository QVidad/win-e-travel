<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'code',
        'title',
        'subtitle',
        'description',
        'key_spots',
        'cover_image',
        'icon',
        'order',
        'status',
        'updated_by',
        'last_modified_at',
    ];

    protected $casts = [
        'last_modified_at' => 'datetime',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class, 'module_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(ModuleProgress::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(ModuleLesson::class, 'course_module_id');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
