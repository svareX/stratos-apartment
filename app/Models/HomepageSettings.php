<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSettings extends Model
{
    protected $fillable = [
        'hero_slides',
    ];

    protected $casts = [
        'hero_slides' => 'array',
    ];
}