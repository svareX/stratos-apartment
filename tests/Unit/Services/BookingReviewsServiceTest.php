<?php

namespace Tests\Unit\Services;

use App\Enums\ReviewSource;
use App\Models\Review;
use App\Services\BookingReviewsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookingReviewsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_creates_reviews_from_api()
    {
        $fake = [
            'result' => [
                'reviews' => [
                    [
                        'review_id' => 'r-1',
                        'review_title' => 'Great',
                        'review_text' => 'Nice stay',
                        'language' => 'en',
                        'average_score' => 4.0,
                        'author' => 'Alice',
                    ],
                    [
                        'review_id' => 'r-2',
                        'review_title' => 'Super',
                        'review_text' => 'Loved it',
                        'language' => 'cs',
                        'average_score' => 3.0,
                        'author' => 'Bob',
                    ],
                ],
            ],
        ];

        Http::fake([ '*' => Http::response($fake, 200) ]);

        $svc = new BookingReviewsService();

        $svc->import(99, 'en-gb');

        $this->assertDatabaseHas('reviews', ['external_id' => 'r-1']);
        $this->assertDatabaseHas('reviews', ['external_id' => 'r-2']);

        $r1 = Review::where('external_id', 'r-1')->first();
        $this->assertEquals(10, $r1->score);
        $this->assertEquals('Alice', $r1->author_name);
        $this->assertEquals('Great', $r1->title_en);

        $r2 = Review::where('external_id', 'r-2')->first();
        $this->assertEquals(8, $r2->score);
        $this->assertEquals('Bob', $r2->author_name);
        $this->assertEquals('Super', $r2->title_cs);
        $this->assertEquals(ReviewSource::External, $r2->source);
    }
}

