<?php

namespace App\Livewire;

use App\Models\FrequentlyAskedQuestion;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class ContactComponent extends Component
{
    public function render()
    {
        return view('livewire.contact-component', [
            'faqs' => FrequentlyAskedQuestion::where('is_active', true)
                ->orderBy('position')
                ->get(),
        ]);
    }

    public function placeholder()
    {
        return view('livewire.placeholders.contact-component');
    }
}