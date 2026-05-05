<?php

namespace Tests\Unit\Services;

use App\Models\Review;
use App\Services\BookingReviewsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookingReviewsServiceFailureTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_handles_failed_http_response_gracefully()
    {
        Http::fake([
            'https://booking-com.p.rapidapi.com/*' => Http::response('', 500),
        ]);

        $svc = new BookingReviewsService();
        $svc->import(321);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_import_skips_items_without_external_id()
    {
        Http::fake([
            'https://booking-com.p.rapidapi.com/*' => Http::response([
                'result' => [
                    'reviews' => [
                        ['review_text' => 'No id here', 'author' => 'X'],
                    ],
                ],
            ], 200),
        ]);

        $svc = new BookingReviewsService();
        $svc->import(400);

        $this->assertDatabaseCount('reviews', 0);
    }
}
