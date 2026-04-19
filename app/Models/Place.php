<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Place extends Model
{
    use HasFactory, HasTranslations, HasSEO;

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

    public function getDynamicSEOData(): SEOData
    {
        $image = $this->image ? Storage::url($this->image) : route('og.image', ['type' => 'place', 'identifier' => $this->id]);

        $title = $this->name;

        $description = Str::of(strip_tags((string) $this->description))->trim()->limit(155);

        $url = $this->url ?: ($this->apartment ? route('apartments.show', $this->apartment->slug) . '#nearby' : null);

        return new SEOData(
            title: $title,
            description: (string) $description,
            image: $image,
            url: $url,
            type: 'place',
        );
    }
}
