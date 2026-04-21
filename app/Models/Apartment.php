<?php

namespace App\Models;

use App\Enums\ApartmentType;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App as AppFacade;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Apartment extends Model
{
    use HasFactory, HasTranslations, HasSEO;

    protected $fillable = [
        'name_en', 'name_cs', 'name_de',
        'description_en', 'description_cs', 'description_de',
        'address',
        'capacity',
        'amenities',
        'slug',
        'type',
        'tags_en', 'tags_cs', 'tags_de',
        'base_price',
        'cleaning_fee',
        'days_for_cleaning_fee',
        'active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'tags_en' => 'array',
        'tags_cs' => 'array',
        'tags_de' => 'array',
        'active' => 'boolean',
        'base_price' => 'decimal:2',
        'cleaning_fee' => 'decimal:2',
        'days_for_cleaning_fee' => 'integer',
        'type' => ApartmentType::class,
    ];

    protected $appends = ['name', 'description', 'tags'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function photosMain()
    {
        return $this->hasMany(Photo::class)->where('is_main', true)->orderBy('position');
    }

    public function photosOther()
    {
        return $this->hasMany(Photo::class)->where('is_main', false)->orderBy('position');
    }

    public function packages()
    {
        return $this->hasMany(ApartmentPackage::class);
    }

    public function places()
    {
        return $this->hasMany(Place::class);
    }

    public function hikes()
    {
        return $this->hasMany(Hike::class);
    }

    protected static function booted()
    {
        static::creating(function ($apartment) {
            if (empty($apartment->slug)) {
                $name = $apartment->getTranslatedAttribute('name') ?? ($apartment->attributes['name'] ?? null);
                if (! empty($name)) {
                    $apartment->slug = Str::slug($name);
                }
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getDynamicSEOData(): SEOData
    {
        $image = $this->photosMain()->first()?->path;

        return new SEOData(
            title: $this->name,
            description: Str::of(strip_tags((string) $this->description))->trim()->limit(155),
            image: $image ? Storage::url($image) : route('og.image', ['type' => 'apartment', 'identifier' => $this->slug]),
            url: route('apartments.show', ['locale' => app()->getLocale() ?? config('app.locale'), 'apartment' => $this->slug]),
            type: 'apartment',
        );
    }

    public function getNameAttribute()
    {
        return $this->getTranslatedAttribute('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->getTranslatedAttribute('description');
    }

    public function getTagsAttribute()
    {
        $locale = AppFacade::getLocale();

        // For English locale keep existing behavior (English or legacy tags)
        if ($locale === 'en') {
            $val = $this->getAttributeValue('tags_en');
            if (! empty($val)) {
                return $val;
            }

            if (isset($this->attributes['tags'])) {
                $raw = $this->attributes['tags'];
                return $raw ? json_decode($raw, true) : [];
            }

            return [];
        }

        // For non-English locales only return localized tags if present, otherwise empty array
        $column = "tags_{$locale}";
        $val = $this->getAttributeValue($column);
        if (! empty($val)) {
            return $val;
        }

        return [];
    }
}
