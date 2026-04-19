<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App as AppFacade;

class Photo extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'apartment_id', 'path', 'tag', 'tag_en', 'tag_cs', 'tag_de', 'is_main', 'position', 'is_new',
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

    public function getTagAttribute()
    {
        $locale = AppFacade::getLocale();

        // If current locale is English, prefer the English tag or legacy `tag` column.
        if ($locale === 'en') {
            if (isset($this->attributes['tag_en']) && $this->attributes['tag_en'] !== null && (string) $this->attributes['tag_en'] !== '') {
                return $this->attributes['tag_en'];
            }

            return $this->attributes['tag'] ?? '';
        }

        // For non-English locales, only return the localized column if it exists and is non-empty.
        $column = "tag_{$locale}";
        if (isset($this->attributes[$column]) && $this->attributes[$column] !== null && (string) $this->attributes[$column] !== '') {
            return $this->attributes[$column];
        }

        // Otherwise return empty string (do not fall back to English)
        return '';
    }
}
