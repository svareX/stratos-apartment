<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class FrequentlyAskedQuestion extends Model
{
    use HasTranslations, HasSEO;

    protected $fillable = [
        'question_en', 'question_cs', 'question_de',
        'answer_en', 'answer_cs', 'answer_de',
        'position', 'is_active',
    ];

    protected $appends = ['question', 'answer'];

    public function getDynamicSEOData(): SEOData
    {
        $title = $this->question;
        $description = Str::of(strip_tags((string) $this->answer))->trim()->limit(155);

        return new SEOData(
            title: $title,
            description: (string) $description,
            url: url()->current(),
            type: 'faq',
        );
    }
}
