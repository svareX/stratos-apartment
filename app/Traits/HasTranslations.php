<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait hasTranslations
{
    protected function getTranslatedAttribute(string $attribute)
    {
        $locale = App::getLocale();
        $column = "{$attribute}_{$locale}";

        // Pokud sloupec pro dané locale neexistuje, zkusíme fallback na angličtinu
        if (!isset($this->attributes[$column])) {
            return $this->attributes["{$attribute}_en"] ?? null;
        }

        return $this->attributes[$column];
    }

    public function getQuestionAttribute()
    {
        return $this->getTranslatedAttribute('question');
    }

    public function getAnswerAttribute()
    {
        return $this->getTranslatedAttribute('answer');
    }
}
