<?php

namespace Tests\Unit\Services;

use App\Models\Apartment;
use App\Services\Ical\IcalExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IcalExportEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_returns_calendar_even_when_no_reservations()
    {
        $apartment = Apartment::create([
            'name_en' => 'Empty Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
        ]);

        $svc = new IcalExportService();
        $ical = $svc->forApartment($apartment);

        $this->assertStringContainsString('BEGIN:VCALENDAR', $ical);
        // no VEVENT expected
        $this->assertStringNotContainsString('BEGIN:VEVENT', $ical);
    }
}
