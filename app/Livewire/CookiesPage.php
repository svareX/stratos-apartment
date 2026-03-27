<?php

namespace App\Livewire;

use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class CookiesPage extends Component
{
    public function render()
    {
        return view('livewire.cookies-page');
    }

    public function placeholder()
    {
        return view('livewire.placeholders.cookies-page');
    }
}
