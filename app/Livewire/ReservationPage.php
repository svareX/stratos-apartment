<?php

namespace App\Livewire;

use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class ReservationPage extends Component
{
    public function render()
    {
        return view('livewire.reservation-page');
    }

    public function placeholder()
    {
        return view('livewire.placeholders.reservation-page');
    }
}
