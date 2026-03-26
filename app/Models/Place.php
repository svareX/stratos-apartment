<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'apartment_id',
        'name_en', 'name_cs', 'name_de',
        'description_en', 'description_cs', 'description_de',
        'distance_text_en', 'distance_text_cs', 'distance_text_de',
        'icon',
        'image',
        'latitude',
        'longitude',
        'url',
    ];

    protected $appends = ['name', 'description', 'distance_text'];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function getNameAttribute()
    {
        return $this->getTranslatedAttribute('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->getTranslatedAttribute('description');
    }

    public function getDistanceTextAttribute()
    {
        return $this->getTranslatedAttribute('distance_text');
    }
}