<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\BookingSource;
use App\Enums\ReservationStatus;
use App\Models\Apartment;
use App\Models\Reservation;
use App\Services\Ical\IcalImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IcalImportTest extends TestCase
{
    use RefreshDatabase;

    private function sampleIcs(array $uidsDates): string
    {
        $events = '';

        foreach ($uidsDates as $uid => [$dtstart, $dtend, $summary]) {
            $events .= "BEGIN:VEVENT\r\n";
            $events .= "UID:{$uid}\r\n";
            $events .= "DTSTAMP:20260401T000000Z\r\n";
            $events .= "DTSTART;VALUE=DATE:{$dtstart}\r\n";
            $events .= "DTEND;VALUE=DATE:{$dtend}\r\n";
            $events .= "SUMMARY:{$summary}\r\n";
            $events .= "END:VEVENT\r\n";
        }

        return "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//Test\r\n" . $events . "END:VCALENDAR\r\n";
    }

    public function test_import_creates_reservation_from_ics(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Import Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
            'external_ical_url' => 'https://example.test/feed.ics',
        ]);

        $ics = $this->sampleIcs([
            'evt-1' => ['20260501', '20260505', 'Test booking'],
        ]);

        Http::fake(['https://example.test/feed.ics' => Http::response($ics, 200)]);

        $service = new IcalImportService();

        $result = $service->syncApartment($apt);

        $this->assertSame(1, $result['created']);
        $this->assertSame(0, $result['updated']);
        $this->assertSame(0, $result['cancelled']);

        $res = Reservation::where('apartment_id', $apt->id)
            ->where('booking_source', BookingSource::External->value)
            ->where('external_booking_id', 'ical:evt-1')
            ->first();

        $this->assertNotNull($res);
        $this->assertSame('2026-05-01', $res->check_in->toDateString());
        // DTEND is exclusive; import should set check_out equal to DTEND value (first free day)
        $this->assertSame('2026-05-05', $res->check_out->toDateString());
        $this->assertTrue($res->status === ReservationStatus::Confirmed);
        $this->assertNotNull($res->external_last_synced_at);
    }

    public function test_import_updates_and_cancels_existing_reservations(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Import Apt 2',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
            'external_ical_url' => 'https://example.test/feed2.ics',
        ]);

        // Existing reservation that should be updated
        $existing = Reservation::create([
            'apartment_id' => $apt->id,
            'booking_source' => BookingSource::External->value,
            'external_booking_id' => 'ical:evt-1',
            'check_in' => '2026-05-01',
            'check_out' => '2026-05-04',
            'status' => ReservationStatus::Pending,
            'price' => 0.00,
        ]);

        // Existing reservation that should be cancelled because it's not in feed
        $toCancel = Reservation::create([
            'apartment_id' => $apt->id,
            'booking_source' => BookingSource::External->value,
            'external_booking_id' => 'ical:evt-2',
            'check_in' => '2026-06-01',
            'check_out' => '2026-06-03',
            'status' => ReservationStatus::Confirmed,
            'price' => 0.00,
        ]);

        // Feed contains evt-1 but with changed dates
        $ics = $this->sampleIcs([
            'evt-1' => ['20260502', '20260506', 'Test booking updated'],
        ]);

        Http::fake(['https://example.test/feed2.ics' => Http::response($ics, 200)]);

        $service = new IcalImportService();

        $result = $service->syncApartment($apt);

        $this->assertSame(0, $result['created']);
        $this->assertSame(1, $result['updated']);
        $this->assertSame(1, $result['cancelled']);

        $existing->refresh();
        $toCancel->refresh();

        $this->assertSame('2026-05-02', $existing->check_in->toDateString());
        // DTEND is exclusive; import should set check_out equal to DTEND value
        $this->assertSame('2026-05-06', $existing->check_out->toDateString());

        $this->assertTrue($toCancel->status === ReservationStatus::Cancelled);
        $this->assertNotNull($toCancel->external_last_synced_at);
    }
}
