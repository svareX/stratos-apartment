<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\ReservationResultPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationResultPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_shows_success_when_session_flag_set(): void
    {
        session()->put('reservation_completed', true);

        $component = new ReservationResultPage;
        $component->mount();

        $this->assertTrue($component->success);
    }

    public function test_shows_no_success_when_no_session_flag(): void
    {
        // Ensure session is clear
        session()->forget('reservation_completed');

        $component = new ReservationResultPage;
        $component->mount();

        $this->assertFalse($component->success);
    }
}
