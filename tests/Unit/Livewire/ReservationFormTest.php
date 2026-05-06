<?php

namespace Tests\Unit\Livewire;

use App\Livewire\ReservationForm;
use App\Models\Apartment;
use App\Models\ApartmentPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReservationFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_apartment_details_populates_prices_and_packages()
    {
        $apt = Apartment::create([
            'name_en' => 'Test Apt',
            'address' => 'Addr',
            'capacity' => 4,
            'base_price' => 150,
            'slug' => 'test-apt',
            'active' => true,
        ]);

        $pkg = ApartmentPackage::create([
            'apartment_id' => $apt->id,
            'name' => 'Breakfast',
            'price' => 200,
        ]);

        $comp = new ReservationForm;
        $comp->apartment_id = $apt->id;
        $comp->updateApartmentDetails();

        $this->assertEquals(150, $comp->pricePerNight);
        $this->assertEquals(4, $comp->capacity);
        $this->assertNotEmpty($comp->availablePackages);
    }

    public function test_select_date_nights_and_totals_with_rate()
    {
        $apt = Apartment::create([
            'name_en' => 'Calc Apt',
            'address' => 'Addr',
            'capacity' => 4,
            'base_price' => 100,
            'slug' => 'calc-apt',
            'active' => true,
        ]);

        $comp = new ReservationForm;
        $comp->apartment_id = $apt->id;
        $comp->pricePerNight = 100;
        $comp->cleaningFee = 600;
        $comp->daysForCleaningFee = 3;
        $comp->bookedDates = [];

        $comp->selectDate('2026-06-01');
        $this->assertEquals('2026-06-01', $comp->start_date);

        $comp->selectDate('2026-06-04');
        $this->assertEquals('2026-06-04', $comp->end_date);

        $this->assertEquals(3, $comp->nights());

        $this->assertEquals(900, $comp->total());

        Http::fake(['*' => Http::response("EMU|euro|1|EUR|25,50\n")]);

        $eur = $comp->totalEur();
        $this->assertEquals(round(900 / 25.5, 2), $eur);
    }
}
