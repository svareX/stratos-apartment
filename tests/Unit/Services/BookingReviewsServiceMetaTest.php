<?php

namespace Tests\Unit\Services;

use App\Models\Review;
use App\Services\BookingReviewsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookingReviewsServiceMetaTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_saves_meta_and_handles_missing_score()
    {
        Http::fake([
            'https://booking-com.p.rapidapi.com/*' => Http::response([
                'result' => [
                    'reviews' => [
                        [
                            'review_id' => 'r-meta',
                            'review_text' => 'Some text',
                            'author' => ['name' => 'MetaUser'],
                            'languagecode' => 'en',
                            // no average_score provided
                        ],
                    ],
                ],
            ], 200),
        ]);

        $svc = new BookingReviewsService();
        $svc->import(555);

        $r = Review::where('external_id', 'r-meta')->firstOrFail();
        $this->assertEquals('Some text', $r->content_en);
        $this->assertNull($r->score);
        $this->assertIsArray($r->meta);
    }
}
