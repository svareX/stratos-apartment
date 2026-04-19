<?php

namespace App\Models;

use App\Enums\ApartmentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Apartment extends Model
{
    use HasFactory, HasSEO;

    protected $fillable = [
        'name',
        'description',
        'address',
        'capacity',
        'amenities',
        'slug',
        'type',
        'tags',
        'base_price',
        'cleaning_fee',
        'days_for_cleaning_fee',
        'active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'tags' => 'array',
        'active' => 'boolean',
        'base_price' => 'decimal:2',
        'cleaning_fee' => 'decimal:2',
        'days_for_cleaning_fee' => 'integer',
        'type' => ApartmentType::class,
    ];

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
            if (empty($apartment->slug) && ! empty($apartment->name)) {
                $apartment->slug = Str::slug($apartment->name);
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
            url: route('apartments.show', $this->slug),
            type: 'apartment',
        );
    }
}
