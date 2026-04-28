<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\BookingSource;
use App\Enums\ReservationStatus;
use App\Models\Apartment;
use App\Models\Reservation;
use App\Services\Ical\IcalExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IcalExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_includes_local_reservations_and_excludes_external(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Export Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        // Local reservation (should be exported)
        $r1 = Reservation::create([
            'apartment_id' => $apt->id,
            'check_in' => '2026-06-01',
            'check_out' => '2026-06-05',
            'booking_source' => BookingSource::Local->value,
            'status' => ReservationStatus::Confirmed,
            'price' => 200.00,
        ]);

        // Another local reservation
        $r2 = Reservation::create([
            'apartment_id' => $apt->id,
            'check_in' => '2026-07-10',
            'check_out' => '2026-07-12',
            'booking_source' => BookingSource::Local->value,
            'status' => ReservationStatus::Pending,
            'price' => 150.00,
        ]);

        // External reservation (should NOT be exported)
        $r3 = Reservation::create([
            'apartment_id' => $apt->id,
            'check_in' => '2026-08-01',
            'check_out' => '2026-08-04',
            'booking_source' => BookingSource::External->value,
            'status' => ReservationStatus::Confirmed,
            'price' => 220.00,
        ]);

        $service = new IcalExportService();

        $calendar = $service->forApartment($apt);

        // Should produce a VCALENDAR
        $this->assertStringContainsString('BEGIN:VCALENDAR', $calendar);

        // Should contain two VEVENTs (r1 and r2) and not r3
        $this->assertEquals(2, substr_count($calendar, 'BEGIN:VEVENT'));

        // Each exported reservation should have the expected UID
        $this->assertStringContainsString("reservation-{$r1->id}@stratos-apartment", $calendar);
        $this->assertStringContainsString("reservation-{$r2->id}@stratos-apartment", $calendar);
        $this->assertStringNotContainsString("reservation-{$r3->id}@stratos-apartment", $calendar);
    }

    public function test_export_one_night_reservation_has_correct_dtstart_and_dtend(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Export Apt OneNight',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $r = Reservation::create([
            'apartment_id' => $apt->id,
            'check_in' => '2026-05-14',
            'check_out' => '2026-05-15',
            'booking_source' => BookingSource::Local->value,
            'status' => ReservationStatus::Confirmed,
            'price' => 100.00,
        ]);

        $service = new IcalExportService();
        $ics = $service->forApartment($apt);

        $this->assertStringContainsString('DTSTART;VALUE=DATE:20260514', $ics);
        $this->assertStringContainsString('DTEND;VALUE=DATE:20260515', $ics);
    }

    public function test_export_multi_night_reservation_has_correct_dtstart_and_dtend(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Export Apt Multi',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $r = Reservation::create([
            'apartment_id' => $apt->id,
            'check_in' => '2026-05-14',
            'check_out' => '2026-05-17',
            'booking_source' => BookingSource::Local->value,
            'status' => ReservationStatus::Confirmed,
            'price' => 300.00,
        ]);

        $service = new IcalExportService();
        $ics = $service->forApartment($apt);

        $this->assertStringContainsString('DTSTART;VALUE=DATE:20260514', $ics);
        $this->assertStringContainsString('DTEND;VALUE=DATE:20260517', $ics);
    }
}
