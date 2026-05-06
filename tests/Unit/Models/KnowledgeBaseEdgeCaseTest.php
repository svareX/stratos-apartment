<?php

namespace Tests\Unit\Models;

use App\Models\KnowledgeBase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KnowledgeBaseEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_pg_embedding_stored_as_json_string_and_retrieved_as_array()
    {
        $driver = \Illuminate\Support\Facades\DB::getDriverName();

        if ($driver === 'pgsql') {
            // Postgres vector column expects 768 dims in this app migrations
            $embedding = array_fill(0, 768, 0.1);
        } else {
            // other drivers store embedding as text/JSON
            $embedding = [0.1, 0.2];
        }

        $kb = KnowledgeBase::create([
            'source_type' => 'edge',
            'content' => 'Edge content',
            'embedding' => $embedding,
        ]);

        // ensure model returns array cast
        $this->assertIsArray($kb->embedding);

        if ($driver === 'pgsql') {
            $this->assertCount(768, $kb->embedding);
            $this->assertEquals(0.1, $kb->embedding[0]);
        } else {
            $this->assertEquals(0.2, $kb->embedding[1]);
        }
    }
}
