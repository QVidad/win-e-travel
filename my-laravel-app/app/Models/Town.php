<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Town extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'title',
        'region',
        'description',
        'hero_image',
        'video_url',
        'status',
        'order',
        'difficulty_level',
    ];

    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class)->orderBy('order');
    }
}
