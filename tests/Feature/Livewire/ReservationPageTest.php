<?php

namespace Tests\Feature\Livewire;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_render_reservation_page_component()
    {
        $apt = Apartment::create([
            'name_en' => 'Page Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 120,
            'active' => true,
        ]);

        Livewire::test(\App\Livewire\ReservationPage::class)
            ->assertStatus(200);
    }
}

