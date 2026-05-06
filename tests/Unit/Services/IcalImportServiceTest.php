<?php

namespace Tests\Unit\Services;

use App\Models\Apartment;
use App\Services\Ical\IcalImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IcalImportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_apartment_parses_ics_and_creates_reservation()
    {
        $apt = Apartment::create([
            'name_en' => 'ICS Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'slug' => 'ics-apt',
            'active' => true,
            'external_ical_url' => 'https://example.com/ics/1',
        ]);

        $ics = "BEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//Test//EN\nBEGIN:VEVENT\nUID:uid-123\nDTSTAMP:20260501T120000Z\nDTSTART:20260601T000000Z\nDTEND:20260604T000000Z\nSUMMARY:Booked\nEND:VEVENT\nEND:VCALENDAR";

        Http::fake(['https://example.com/*' => Http::response($ics, 200)]);

        $svc = new IcalImportService;
        $res = $svc->syncApartment($apt);

        $this->assertEquals(1, $res['created']);
        $this->assertDatabaseHas('reservations', ['external_booking_id' => 'ical:uid-123']);
    }

    public function test_sync_apartment_throws_on_http_error()
    {
        $apt = Apartment::create([
            'name_en' => 'ICS Apt 2',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'slug' => 'ics-apt-2',
            'active' => true,
            'external_ical_url' => 'https://example.com/ics/2',
        ]);

        Http::fake(['https://example.com/*' => Http::response('error', 500)]);

        $svc = new IcalImportService;

        $this->expectException(\RuntimeException::class);
        $svc->syncApartment($apt);
    }
}
