<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\FrequentlyAskedQuestion;

class FrequentlyAskedQuestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_update_delete_faq(): void
    {
        $faq = FrequentlyAskedQuestion::create([
            'question_en' => 'Q1',
            'answer_en' => 'A1',
            'question_cs' => 'Q1 CS',
            'answer_cs' => 'A1 CS',
            'question_de' => 'Q1 DE',
            'answer_de' => 'A1 DE',
            'position' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('frequently_asked_questions', ['id' => $faq->id, 'question_en' => 'Q1']);

        $faq->update(['question_en' => 'Q1 updated']);
        $this->assertDatabaseHas('frequently_asked_questions', ['id' => $faq->id, 'question_en' => 'Q1 updated']);

        $faq->delete();
        $this->assertDatabaseMissing('frequently_asked_questions', ['id' => $faq->id]);
    }
}
