<?php

namespace Tests\Unit\Services;

use App\Models\Apartment;
use App\Models\Reservation;
use App\Services\Ical\IcalImportService;
use App\Enums\ReservationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IcalImportCancellationTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_external_reservations_are_cancelled_when_missing_in_feed()
    {
        $apartment = Apartment::create([
            'name_en' => 'Cancel Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'external_ical_url' => 'https://example.com/cancel.ics',
        ]);

        // existing reservation that should be cancelled
        Reservation::create([
            'apartment_id' => $apartment->id,
            'booking_source' => 'external',
            'external_booking_id' => 'ical:old-uid',
            'status' => ReservationStatus::Confirmed->value,
            'price' => 0,
            'check_in' => now()->addDays(1)->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
        ]);

        // ICS only contains new UID
        $ics = "BEGIN:VCALENDAR\nVERSION:2.0\nBEGIN:VEVENT\nUID:new-uid\nDTSTART:20260510\nDTEND:20260512\nSUMMARY:Booked\nEND:VEVENT\nEND:VCALENDAR\n";

        Http::fake(['https://example.com/*' => Http::response($ics, 200)]);

        $svc = new IcalImportService();
        $res = $svc->syncApartment($apartment);

        $this->assertEquals(1, $res['cancelled']);
        $this->assertDatabaseHas('reservations', ['external_booking_id' => 'ical:old-uid', 'status' => ReservationStatus::Cancelled->value]);
    }
}
