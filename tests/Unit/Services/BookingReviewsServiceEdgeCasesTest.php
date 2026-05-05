<?php

namespace Tests\Unit\Services;

use App\Models\Review;
use App\Services\BookingReviewsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookingReviewsServiceEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_uses_pros_and_cons_when_no_text()
    {
        Http::fake([
            'https://booking-com.p.rapidapi.com/*' => Http::response([
                'result' => [
                    'reviews' => [
                        [
                            'review_id' => 'r-proscons',
                            'pros' => 'Good location',
                            'cons' => 'Small room',
                            'author' => ['name' => 'Bob'],
                            'average_score' => 3,
                            'languagecode' => 'en',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $svc = new BookingReviewsService();
        $svc->import(99);

        $this->assertDatabaseHas('reviews', ['external_id' => 'r-proscons', 'hotel_id' => 99]);

        $r = Review::where('external_id', 'r-proscons')->first();
        $this->assertStringContainsString('Good location', $r->content_en);
        $this->assertStringContainsString('Small room', $r->content_en);
    }

    public function test_import_uses_title_as_content_if_no_other_text()
    {
        Http::fake([
            'https://booking-com.p.rapidapi.com/*' => Http::response([
                'result' => [
                    'reviews' => [
                        [
                            'review_id' => 'r-title',
                            'title' => 'Lovely stay',
                            'author' => 'Chris',
                            'average_score' => 5,
                            'languagecode' => 'en',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $svc = new BookingReviewsService();
        $svc->import(100);

        $r = Review::where('external_id', 'r-title')->firstOrFail();
        $this->assertEquals('Lovely stay', $r->content_en);
    }

    public function test_import_creates_localized_content_and_english_fallback_and_maps_score()
    {
        Http::fake([
            'https://booking-com.p.rapidapi.com/*' => Http::response([
                'result' => [
                    'reviews' => [
                        [
                            'review_id' => 'r-cs',
                            'review_text' => 'Skvělý pobyt',
                            'author' => ['name' => 'Dana'],
                            'average_score' => 2,
                            'languagecode' => 'cs',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $svc = new BookingReviewsService();
        $svc->import(200, 'cs-cz');

        $r = Review::where('external_id', 'r-cs')->firstOrFail();
        $this->assertEquals('Skvělý pobyt', $r->content_cs);
        $this->assertEquals('Skvělý pobyt', $r->content_en);
        $this->assertIsInt($r->score);
        $this->assertEquals((int)round(2 / 4 * 10), $r->score);
    }
}
