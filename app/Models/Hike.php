<?php

namespace App\Models;

/**
 * @property-read Apartment|null $apartment
 */

use App\Enums\HikeDifficulty;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Hike extends Model
{
    use HasFactory, HasSEO, HasTranslations;

    protected $fillable = [
        'apartment_id',
        'name_en', 'name_cs', 'name_de',
        'description_en', 'description_cs', 'description_de',
        'distance_tx_en', 'distance_tx_cs', 'distance_tx_de',
        'difficulty',
        'length',
        'is_for_families',
    ];

    protected $casts = [
        'difficulty' => HikeDifficulty::class,
        'length' => 'double',
        'is_for_families' => 'boolean',
    ];

    protected $appends = ['name', 'description', 'distance_tx'];

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

    public function getDistanceTxAttribute()
    {
        return $this->getTranslatedAttribute('distance_tx');
    }

    public function getDynamicSEOData(): SEOData
    {
        $title = $this->name;

        $description = Str::of(strip_tags((string) $this->description))->trim()->limit(155);

        $apt = $this->apartment;
        /** @var Apartment|null $apt */
        $locale = app()->getLocale() ?: config('app.locale');
        $url = $apt ? route('apartments.show', ['locale' => $locale, 'apartment' => $apt->slug]).'#hikes' : null;

        return new SEOData(
            title: $title,
            description: (string) $description,
            url: $url,
            type: 'hike',
        );
    }
}
