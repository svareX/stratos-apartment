<?php

namespace Tests\Unit\Models;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTitleTest extends TestCase
{
    use RefreshDatabase;

    public function test_title_respects_locale_and_falls_back()
    {
        $r = Review::create([
            'source' => 'external',
            'external_id' => 't-1',
            'hotel_id' => 1,
            'author_name' => 'Author',
            'title_en' => 'English Title',
            'title_cs' => 'Czech Title',
        ]);

        app()->setLocale('cs');
        $this->assertEquals('Czech Title', $r->title);

        app()->setLocale('en');
        $this->assertEquals('English Title', $r->title);

        app()->setLocale('de');
        // fallback to English
        $this->assertEquals('English Title', $r->title);
    }
}
