<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'town_id',
        'name',
        'type',
        'description',
        'image',
        'history',
        'significance',
        'coordinates',
        'order',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function town(): BelongsTo
    {
        return $this->belongsTo(Town::class);
    }
}
