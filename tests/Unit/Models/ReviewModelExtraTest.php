<?php

namespace Tests\Unit\Models;

use App\Enums\ReviewSource;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewModelExtraTest extends TestCase
{
    use RefreshDatabase;

    public function test_score_casts_to_integer_and_source_enum()
    {
        $r = Review::create([
            'source' => 'external',
            'external_id' => 'r-123',
            'hotel_id' => 1,
            'author_name' => 'Tester',
            'score' => '4',
            'content_en' => 'Nice',
        ]);

        $this->assertIsInt($r->score);
        $this->assertSame(4, $r->score);
        $this->assertInstanceOf(ReviewSource::class, $r->source);
        $this->assertTrue($r->source === ReviewSource::External);
    }
}
