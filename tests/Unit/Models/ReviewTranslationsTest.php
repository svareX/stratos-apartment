<?php

namespace Tests\Unit\Models;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTranslationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_get_content_respects_locale_and_falls_back()
    {
        $r = Review::create([
            'source' => 'external',
            'external_id' => 'r-model',
            'hotel_id' => 1,
            'author_name' => 'Auth',
            'content_en' => 'Hello',
            'content_cs' => 'Ahoj',
        ]);

        app()->setLocale('cs');
        $this->assertEquals('Ahoj', $r->content);

        app()->setLocale('en');
        $this->assertEquals('Hello', $r->content);

        app()->setLocale('de');
        // fallback to English when other locale missing
        $this->assertEquals('Hello', $r->content);
    }
}
