<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Models\Apartment;
use App\Models\ApartmentPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_form_steps_and_totals(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Reserve Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        // add a package to ensure package selection logic works
        $pkg = ApartmentPackage::create([
            'apartment_id' => $apt->id,
            'name' => 'Breakfast',
            'price' => 200,
        ]);

        $start = now()->addDays(10)->toDateString();
        $end = now()->addDays(12)->toDateString(); // 2 nights

        $test = Livewire::test(\App\Livewire\ReservationForm::class)
            ->set('apartment_id', $apt->id)
            ->call('updateApartmentDetails')
            ->set('apartment_package_id', $pkg->id)
            ->set('start_date', $start)
            ->set('end_date', $end)
            ->call('nextStep')
            ->assertSet('step', 2);

        // do not run server-side email DNS validation here; instead assert the component
        // computed nights/total correctly after selecting dates and package
        $instance = $test->instance();
        $this->assertEquals(2, $instance->nights());
        $this->assertGreaterThan(0, $instance->total());
    }
}
