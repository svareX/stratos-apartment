<?php

namespace Tests\Unit\Models;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_respects_locale_and_falls_back_to_english()
    {
        $r = Review::create([
            'source' => 'external',
            'external_id' => 'c-1',
            'hotel_id' => 2,
            'author_name' => 'Author',
            'content_en' => 'English content',
            'content_cs' => 'Czech content',
        ]);

        app()->setLocale('cs');
        $this->assertEquals('Czech content', $r->content);

        app()->setLocale('en');
        $this->assertEquals('English content', $r->content);

        app()->setLocale('de');
        $this->assertEquals('English content', $r->content);
    }
}
