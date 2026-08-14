<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Simulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'town_id',
        'title',
        'description',
        'scenarios',
        'status',
    ];

    protected $casts = [
        'scenarios' => 'array',
    ];

    public function town()
    {
        return $this->belongsTo(Town::class);
    }
}
