<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Enums\ReservationStatus;
use App\Models\Apartment;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationFormBookedDatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_load_booked_dates_includes_existing_reservation_days(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Booked Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $reservation = Reservation::create([
            'apartment_id' => $apt->id,
            'check_in' => '2026-05-01',
            'check_out' => '2026-05-03',
            'price' => 100.00,
            'status' => ReservationStatus::Confirmed->value,
        ]);

        $test = Livewire::test(\App\Livewire\ReservationForm::class)
            ->set('apartment_id', $apt->id)
            ->call('updateApartmentDetails')
            ->call('loadBookedDates');

        $instance = $test->instance();

        $this->assertContains('2026-05-01', $instance->bookedDates);
        $this->assertContains('2026-05-02', $instance->bookedDates);
        $this->assertContains('2026-05-03', $instance->bookedDates);
    }
}
