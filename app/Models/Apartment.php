<?php

namespace App\Models;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reservation[] $reservations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Photo[] $photosMain
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Photo[] $photosOther
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ApartmentPackage[] $packages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Place[] $places
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Hike[] $hikes
 * @property string $slug
 * @property float|null $base_price_eur
 * @property float|null $cleaning_fee_eur
 * @property int|null $days_for_cleaning_fee
 */

use App\Enums\ApartmentType;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Apartment extends Model
{
    use HasFactory, HasSEO, HasTranslations;

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
        'base_price_eur',
        'cleaning_fee_eur',
        'days_for_cleaning_fee',
        'active',
        'external_ical_url',
        'ical_export_token',
        'check_in_time',
        'check_out_time',
    ];

    protected $casts = [
        'amenities' => 'array',
        'tags_en' => 'array',
        'tags_cs' => 'array',
        'tags_de' => 'array',
        'active' => 'boolean',
        'base_price' => 'decimal:2',
        'cleaning_fee' => 'decimal:2',
        'base_price_eur' => 'decimal:2',
        'cleaning_fee_eur' => 'decimal:2',
        'days_for_cleaning_fee' => 'integer',
        'type' => ApartmentType::class,
        'check_in_time' => 'string',
        'check_out_time' => 'string',
    ];

    /**
     * Model attribute defaults to match DB migration defaults.
     */
    protected $attributes = [
        'check_in_time' => '15:00:00',
        'check_out_time' => '10:00:00',
    ];

    protected $appends = ['name', 'description', 'tags'];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function photosMain(): HasMany
    {
        return $this->hasMany(Photo::class)->where('is_main', true)->orderBy('position');
    }

    public function photosOther(): HasMany
    {
        return $this->hasMany(Photo::class)->where('is_main', false)->orderBy('position');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(ApartmentPackage::class);
    }

    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }

    public function hikes(): HasMany
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
            if (blank($apartment->ical_export_token)) {
                $apartment->ical_export_token = Str::random(48);
            }
        });

        static::saving(function (Apartment $apartment): void {
            if (blank($apartment->ical_export_token)) {
                $apartment->ical_export_token = Str::random(48);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getDynamicSEOData(): SEOData
    {
        $photo = $this->photosMain()->first();
        /** @var \App\Models\Photo|null $photo */
        $image = $photo?->path;

        return new SEOData(
            title: $this->name,
            description: Str::of(strip_tags((string) $this->description))->trim()->limit(155),
            image: $image ? Storage::url($image) : route('og.image', ['type' => 'apartment', 'identifier' => $this->slug]),
            url: route('apartments.show', ['locale' => (app()->getLocale() ?: config('app.locale')), 'apartment' => $this->slug]),
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

    public function getCheckInTimeFormattedAttribute()
    {
        if (empty($this->check_in_time)) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($this->check_in_time)->format('H:i');
        } catch (\Exception $e) {
            return $this->check_in_time;
        }
    }

    public function getCheckOutTimeFormattedAttribute()
    {
        if (empty($this->check_out_time)) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($this->check_out_time)->format('H:i');
        } catch (\Exception $e) {
            return $this->check_out_time;
        }
    }
}
