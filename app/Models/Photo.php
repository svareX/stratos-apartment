<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id', 'path', 'tag', 'is_main', 'position', 'is_new',
        'title_en', 'title_cs', 'title_de',
        'highlighted_title_en', 'highlighted_title_cs', 'highlighted_title_de',
        'description_en', 'description_cs', 'description_de',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'is_new' => 'boolean',
        'position' => 'integer',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}
