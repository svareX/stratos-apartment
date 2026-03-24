<?php

namespace App\Livewire;

use App\Models\FrequentlyAskedQuestion;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class FrequentlyAskedQuestionsComponent extends Component
{
    public function render()
    {
        return view('livewire.frequently-asked-questions-component', [
            'faqs' => FrequentlyAskedQuestion::where('is_active', true)
                ->orderBy('position')
                ->get()
        ]);
    }

    public function placeholder()
    {
        return view('livewire.placeholders.frequently-asked-questions-component');
    }
}
