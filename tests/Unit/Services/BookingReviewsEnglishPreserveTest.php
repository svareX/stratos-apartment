<?php

namespace Tests\Unit\Services;

use App\Models\Review;
use App\Services\BookingReviewsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookingReviewsEnglishPreserveTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_does_not_overwrite_existing_english_when_localized_arrives()
    {
        Review::create([
            'external_id' => 'r-preserve',
            'source' => 'external',
            'hotel_id' => 999,
            'author_name' => 'Existing',
            'title_en' => 'Existing EN',
            'content_en' => 'Existing content',
        ]);

        Http::fake([
            'https://booking-com.p.rapidapi.com/*' => Http::response([
                'result' => [
                    'reviews' => [
                        [
                            'review_id' => 'r-preserve',
                            'review_text' => 'CS obsah',
                            'author' => ['name' => 'New'],
                            'languagecode' => 'cs',
                            'average_score' => 4,
                        ],
                    ],
                ],
            ], 200),
        ]);

        $svc = new BookingReviewsService();
        $svc->import(999, 'cs-cz');

        $r = Review::where('external_id', 'r-preserve')->firstOrFail();
        $this->assertEquals('Existing EN', $r->title_en);
        $this->assertEquals('Existing content', $r->content_en);
        $this->assertEquals('CS obsah', $r->content_cs);
    }
}
