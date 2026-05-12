<?php

namespace App\Models;

/**
 * @property-read Apartment|null $apartment
 * @property int $id
 * @property string|null $path
 * @property bool|null $is_new
 * @property bool|null $is_main
 * @property int|null $position
 * @property string|null $title_en
 * @property string|null $description_en
 */

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    public function getTagAttribute()
    {
        $locale = AppFacade::getLocale();

        if ($locale === 'en') {
            if (! empty($this->attributes['tag_en'])) {
                return $this->attributes['tag_en'];
            }

            return $this->attributes['tag'] ?? '';
        }

        $column = "tag_{$locale}";
        if (! empty($this->attributes[$column])) {
            return $this->attributes[$column];
        }

        return '';
    }
}
