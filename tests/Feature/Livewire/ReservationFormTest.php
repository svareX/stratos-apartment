<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ReservationForm;
use App\Models\Apartment;
use App\Models\ApartmentPackage;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_mount_prefills_from_query_and_updates_details()
    {
        $apt = Apartment::create([
            'name_en' => 'Live Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $pkg = ApartmentPackage::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Std',
            'price' => 20,
        ]);

        Livewire::test(ReservationForm::class, [
            'apartment' => $apt->slug,
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-03',
            'package' => $pkg->id,
        ])
            ->assertSet('apartment_id', $apt->id)
            ->assertSet('start_date', '2026-06-01')
            ->assertSet('end_date', '2026-06-03')
            ->assertSet('apartment_package_id', (string) $pkg->id)
            ->assertSet('pricePerNight', 100);
    }

    public function test_load_booked_dates_populates_dates_from_reservations()
    {
        $apt = Apartment::create([
            'name_en' => 'Book Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 80,
            'active' => true,
        ]);

        Reservation::create([
            'apartment_id' => $apt->id,
            'check_in' => '2026-06-10',
            'check_out' => '2026-06-13',
            'price' => 200,
            'status' => 'confirmed',
        ]);

        Livewire::test(ReservationForm::class)
            ->set('apartment_id', $apt->id)
            ->call('loadBookedDates')
            ->assertSet('bookedDates', ['2026-06-10', '2026-06-11', '2026-06-12']);
    }
}
