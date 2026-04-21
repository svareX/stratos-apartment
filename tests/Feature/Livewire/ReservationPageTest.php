<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_page_renders_without_errors(): void
    {
        Livewire::test(\App\Livewire\ReservationPage::class)
            ->assertHasNoErrors();
    }
}
