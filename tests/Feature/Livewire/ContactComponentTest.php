<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\ContactComponent;
use App\Models\ContactSettings;
use App\Models\FrequentlyAskedQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\View\View;
use Tests\TestCase;

class ContactComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_component_renders_with_data(): void
    {
        FrequentlyAskedQuestion::create([
            'question_en' => 'Q1',
            'answer_en' => 'A1',
            'question_cs' => 'Q1 CS',
            'answer_cs' => 'A1 CS',
            'question_de' => 'Q1 DE',
            'answer_de' => 'A1 DE',
            'position' => 1,
            'is_active' => true,
        ]);

        ContactSettings::current();

        // Instantiate the component and call its render method directly
        $component = new ContactComponent;
        $view = $component->render();

        $this->assertInstanceOf(View::class, $view);
        $data = $view->getData();

        $this->assertArrayHasKey('faqs', $data);
        $this->assertArrayHasKey('settings', $data);
    }
}
