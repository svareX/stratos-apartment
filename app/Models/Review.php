<?php

namespace App\Models;

use App\Enums\ReviewSource;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasTranslations;

    protected $fillable = [
        'source',
        'external_id',
        'hotel_id',
        'author_name',
        'locale',
        'language',
        'customer_type',
        'score',
        'title_en', 'title_cs', 'title_de',
        'content_en', 'content_cs', 'content_de',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'score' => 'integer',
        'source' => ReviewSource::class,
    ];

    protected $appends = ['title', 'content'];

    public function getTitleAttribute()
    {
        return $this->getTranslatedAttribute('title');
    }

    public function getContentAttribute()
    {
        return $this->getTranslatedAttribute('content');
    }
}
