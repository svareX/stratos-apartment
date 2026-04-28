<?php

namespace App\Models;
/**
 * @property-read \App\Models\Apartment|null $apartment
 * @property int $id
 * @property string|null $name_cs
 * @property float|null $price
 * @property float|null $price_eur
 * @property array|null $features
 * @property array|null $translated_features
 */

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
        'price_eur',
        'icon',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'double:2',
        'price_eur' => 'double:2',
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
