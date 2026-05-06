<?php

namespace Tests\Unit\Services;

use App\Models\Apartment;
use App\Services\Ical\IcalImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IcalSyncAllErrorTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_all_handles_errors_and_returns_counts()
    {
        // create two apartments: one good, one bad
        Apartment::create([
            'name_en' => 'Good',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'external_ical_url' => 'https://example.com/good.ics',
        ]);

        Apartment::create([
            'name_en' => 'Bad',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'external_ical_url' => 'https://example.com/bad.ics',
        ]);

        // good returns valid ics
        $goodIcs = "BEGIN:VCALENDAR\nVERSION:2.0\nBEGIN:VEVENT\nUID:g1\nDTSTART:20260510\nDTEND:20260512\nSUMMARY:Booked\nEND:VEVENT\nEND:VCALENDAR\n";

        Http::fake([
            'https://example.com/good.ics' => Http::response($goodIcs, 200),
            'https://example.com/bad.ics' => Http::response('', 500),
        ]);

        $svc = new IcalImportService;
        $result = $svc->syncAll();

        $this->assertArrayHasKey('errors', $result);
        $this->assertGreaterThanOrEqual(1, $result['errors']);
    }
}
