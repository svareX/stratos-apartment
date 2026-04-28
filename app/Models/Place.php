<?php

namespace App\Models;
/**
 * @property-read \App\Models\Apartment|null $apartment
 */

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Place extends Model
{
    use HasFactory, HasSEO, HasTranslations;

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

    public function apartment(): BelongsTo
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

    public function getDynamicSEOData(): SEOData
    {
        $image = $this->image ? Storage::url($this->image) : route('og.image', ['type' => 'place', 'identifier' => $this->id]);

        $title = $this->name;

        $description = Str::of(strip_tags((string) $this->description))->trim()->limit(155);

        $apt = $this->apartment;
        /** @var \App\Models\Apartment|null $apt */
        $locale = app()->getLocale() ?: config('app.locale');
        $url = $apt ? route('apartments.show', ['locale' => $locale, 'apartment' => $apt->slug]).'#nearby' : null;

        return new SEOData(
            title: $title,
            description: (string) $description,
            image: $image,
            url: $url,
            type: 'place',
        );
    }
}
