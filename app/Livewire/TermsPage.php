<?php

namespace App\Livewire;

use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class TermsPage extends Component
{
    public function render()
    {
        return view('livewire.terms-page');
    }

    public function placeholder()
    {
        return view('livewire.placeholders.terms-page');
    }
}
