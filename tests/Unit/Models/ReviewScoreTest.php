<?php

namespace Tests\Unit\Models;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewScoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_score_is_cast_to_integer()
    {
        $r = Review::create([
            'source' => 'external',
            'external_id' => 's-1',
            'hotel_id' => 3,
            'author_name' => 'Scorer',
            'score' => '8',
        ]);

        $this->assertIsInt($r->score);
        $this->assertEquals(8, $r->score);
    }
}
