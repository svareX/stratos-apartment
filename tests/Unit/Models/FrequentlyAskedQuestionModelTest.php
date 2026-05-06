<?php

namespace Tests\Unit\Models;

use App\Models\FrequentlyAskedQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrequentlyAskedQuestionModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_question_and_answer_translations_and_seo()
    {
        $faq = FrequentlyAskedQuestion::create([
            'question_en' => 'What?',
            'answer_en' => 'Yes',
            'question_cs' => 'Co?',
            'answer_cs' => 'Ano',
            'question_de' => 'Was?',
            'answer_de' => 'Ja',
            'position' => 1,
            'is_active' => true,
        ]);

        app()->setLocale('cs');
        $this->assertEquals('Co?', $faq->question);
        $this->assertEquals('Ano', $faq->answer);

        $seo = $faq->getDynamicSEOData();
        $this->assertStringContainsString('faq', $seo->type);
    }
}
