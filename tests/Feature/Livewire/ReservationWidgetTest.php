<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ReservationWidget;
use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationWidgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_widget_initializes_with_apartments()
    {
        Apartment::create([
            'name_en' => 'Widget Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        Livewire::test(ReservationWidget::class)
            ->assertSet('apartments', function ($val) {
                return is_array($val) || (is_object($val) && count($val) >= 1);
            });
    }
}

