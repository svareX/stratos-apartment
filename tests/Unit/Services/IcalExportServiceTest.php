<?php

namespace Tests\Unit\Services;

use App\Enums\BookingSource;
use App\Enums\ReservationStatus;
use App\Models\Apartment;
use App\Models\Reservation;
use App\Services\Ical\IcalExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IcalExportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_for_apartment_returns_ical_with_reservation_uids()
    {
        $apt = Apartment::create([
            'name_en' => 'Export Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'slug' => 'export-apt',
            'active' => true,
        ]);

        $res = Reservation::create([
            'apartment_id' => $apt->id,
            'user_id' => null,
            'check_in' => '2026-07-01',
            'check_out' => '2026-07-04',
            'price' => 300,
            'status' => ReservationStatus::Confirmed->value,
            'booking_source' => BookingSource::Local->value,
        ]);

        $svc = new IcalExportService;
        $ical = $svc->forApartment($apt);

        $this->assertStringContainsString("reservation-{$res->id}", $ical);
        $this->assertStringContainsString('DTSTART', $ical);
    }
}
