<?php

namespace App\Livewire;

use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class ReservationResultPage extends Component
{
    public bool $success = false;

    public function mount()
    {
        if (session()->has('reservation_completed')) {
            $this->success = true;
            session()->forget('reservation_completed'); 
        } else {
            $this->success = false;
        }
    }

    public function render()
    {
        return view('livewire.reservation-result-page');
    }

    public function placeholder()
    {
        return view('livewire.placeholders.reservation-result-page');
    }
}