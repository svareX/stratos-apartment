<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\ReservationForm;
use App\Models\Apartment;
use App\Models\ApartmentPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationFormBehaviorTest extends TestCase
{
    use RefreshDatabase;

    public function test_package_selection_sets_price_and_selected_name(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Pkg Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 500,
            'active' => true,
        ]);

        $pkg = ApartmentPackage::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Breakfast',
            'price' => 200.0,
            'icon' => '☕',
        ]);

        Livewire::test(ReservationForm::class)
            ->set('apartment_id', $apt->id)
            ->call('updateApartmentDetails')
            ->set('apartment_package_id', $pkg->id)
            ->assertSet('packagePrice', 200.0);
    }

    public function test_select_date_sets_start_end_and_nights(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Dates Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 500,
            'active' => true,
        ]);

        $test = Livewire::test(ReservationForm::class)
            ->set('apartment_id', $apt->id)
            ->call('updateApartmentDetails')
            ->call('selectDate', '2026-06-10')
            ->call('selectDate', '2026-06-12');

        $instance = $test->instance();

        $this->assertEquals('2026-06-10', $instance->start_date);
        $this->assertEquals('2026-06-12', $instance->end_date);
        $this->assertEquals(2, $instance->nights());
    }

    public function test_next_step_validates_and_adds_errors_when_missing(): void
    {
        $test = Livewire::test(ReservationForm::class)
            ->call('nextStep')
            ->assertHasErrors('apartment_id');

        // create apartment and ensure package error occurs next
        $apt = Apartment::create([
            'name_en' => 'Seq Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $test->set('apartment_id', $apt->id)
            ->call('nextStep')
            ->assertHasErrors('apartment_package_id');
    }
}
