<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id',
        'path',
        'tag',
        'is_main',
        'position',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'position' => 'integer',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}
