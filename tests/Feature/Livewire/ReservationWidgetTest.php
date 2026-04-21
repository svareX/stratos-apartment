<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationWidgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_widget_initializes_and_redirects(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Widget Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $test = Livewire::test(\App\Livewire\ReservationWidget::class);

        $instance = $test->instance();
        $this->assertNotNull($instance->apartment_id);

        // nights calculation based on default mount values (2 days)
        $this->assertEquals(2, $instance->getNightsProperty());

        // Ensure goToReservation triggers a redirect
        $test->call('goToReservation')->assertRedirect();
    }
}
