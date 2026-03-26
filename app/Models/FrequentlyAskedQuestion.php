<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class FrequentlyAskedQuestion extends Model
{
    use HasTranslations;

    protected $fillable = [
        'question_en', 'question_cs', 'question_de',
        'answer_en', 'answer_cs', 'answer_de',
        'position', 'is_active',
    ];

    protected $appends = ['question', 'answer'];
}
