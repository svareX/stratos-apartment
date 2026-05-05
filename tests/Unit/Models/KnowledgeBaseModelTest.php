<?php

namespace Tests\Unit\Models;

use App\Models\KnowledgeBase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KnowledgeBaseModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_embedding_is_cast_to_array()
    {
        $embedding = array_fill(0, 768, 0.1);

        $kb = KnowledgeBase::create([
            'source_type' => 'tests',
            'content' => 'Some content',
            'embedding' => $embedding,
        ]);

        $this->assertIsArray($kb->embedding);
        $this->assertCount(768, $kb->embedding);
        $this->assertEquals(0.1, $kb->embedding[0]);
    }
}
