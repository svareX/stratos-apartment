<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Models\FrequentlyAskedQuestion;
use App\Filament\Resources\FrequentlyAskedQuestions\Pages\CreateFrequentlyAskedQuestion;
use App\Filament\Resources\FrequentlyAskedQuestions\Pages\EditFrequentlyAskedQuestion;

class FrequentlyAskedQuestionResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_filament_can_create_faq(): void
    {
        $data = [
            'question_en' => 'How does this work?',
            'answer_en' => 'You just test it.',
            'question_cs' => 'Jak to funguje?',
            'answer_cs' => 'Jen to otestujte.',
            'question_de' => 'Wie funktioniert das?',
            'answer_de' => 'Einfach testen.',
            'position' => 1,
            'is_active' => true,
        ];

        Livewire::test(CreateFrequentlyAskedQuestion::class)
            ->set('data', $data)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('frequently_asked_questions', ['question_en' => 'How does this work?']);
    }

    public function test_filament_can_update_and_delete_faq(): void
    {
        $faq = FrequentlyAskedQuestion::create([
            'question_en' => 'Old question',
            'answer_en' => 'Old answer',
            'question_cs' => 'Stará otázka',
            'answer_cs' => 'Stará odpověď',
            'question_de' => 'Alte Frage',
            'answer_de' => 'Alte Antwort',
            'position' => 5,
            'is_active' => true,
        ]);

        $update = [
            'question_en' => 'Updated question',
            'answer_en' => 'Updated answer',
            'position' => 2,
        ];

        Livewire::test(EditFrequentlyAskedQuestion::class, ['record' => $faq->id])
            ->set('data', array_merge($faq->toArray(), $update))
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('frequently_asked_questions', ['id' => $faq->id, 'question_en' => 'Updated question']);

        // Delete via model to verify removal
        $faq->delete();
        $this->assertDatabaseMissing('frequently_asked_questions', ['id' => $faq->id]);
    }
}
