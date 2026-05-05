<?php

namespace Tests\Unit\Services;

use App\Models\Apartment;
use App\Models\Reservation;
use App\Services\Ical\IcalImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IcalImportEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_handles_duplicate_uids_without_creating_duplicates()
    {
        $apartment = Apartment::create([
            'name_en' => 'Dup Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'external_ical_url' => 'https://example.com/dup.ics',
        ]);

        $ics = "BEGIN:VCALENDAR\nVERSION:2.0\nBEGIN:VEVENT\nUID:dup-uid\nDTSTART:20260510\nDTEND:20260512\nSUMMARY:Booked\nEND:VEVENT\nEND:VCALENDAR\n";

        Http::fake(['https://example.com/*' => Http::response($ics, 200)]);

        $svc = new IcalImportService();
        $first = $svc->syncApartment($apartment);
        $this->assertGreaterThanOrEqual(1, $first['created']);

        // run again — should not create another reservation for same UID
        $second = $svc->syncApartment($apartment);
        $this->assertEquals(0, $second['created']);

        $this->assertDatabaseCount('reservations', 1);
        $res = Reservation::first();
        $this->assertStringContainsString('ical:dup-uid', $res->external_booking_id);
    }
}
