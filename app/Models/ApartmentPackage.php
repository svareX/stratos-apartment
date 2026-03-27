<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class ApartmentPackage extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'apartment_id',
        'name_en', 'name_cs', 'name_de',
        'features',
        'price',
        'icon',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'double',
    ];

    protected $appends = ['name', 'translated_features'];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function getNameAttribute()
    {
        return $this->getTranslatedAttribute('name');
    }

    public function getTranslatedFeaturesAttribute()
    {
        $locale = App::getLocale();
        $features = $this->features ?? [];

        return array_map(function ($feature) use ($locale) {
            return $feature[$locale] ?? $feature['en'] ?? '';
        }, $features);
    }
}
