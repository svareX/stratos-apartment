<?php

namespace App\Services\Ical;

use App\Enums\BookingSource;
use App\Enums\ReservationStatus;
use App\Models\Apartment;
use App\Models\Reservation;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Sabre\VObject\Reader;

class IcalImportService
{
    public function syncApartment(Apartment $apartment): array
    {
        if (blank($apartment->external_ical_url)) {
            return ['created' => 0, 'updated' => 0, 'cancelled' => 0];
        }

        $response = Http::timeout(20)->get($apartment->external_ical_url);

        if (! $response->successful()) {
            throw new \RuntimeException("Unable to download ICS for apartment {$apartment->id}");
        }

        $vcalendar = Reader::read($response->body());

        $rows = [];
        $seenExternalIds = [];

        foreach ($vcalendar->select('VEVENT') as $event) {
            $dates = $this->extractDateRange($event);

            if (! $dates) {
                continue;
            }

            [$checkIn, $checkOut] = $dates;

            $uid = (string) ($event->UID ?? '');
            $summary = (string) ($event->SUMMARY ?? '');
            $externalId = $uid !== ''
                ? "ical:{$uid}"
                : 'ical:'.sha1($apartment->external_ical_url.'|'.$checkIn->toDateString().'|'.$checkOut->toDateString().'|'.$summary);

            $seenExternalIds[] = $externalId;

            $rows[$externalId] = [
                'apartment_id' => $apartment->id,
                'booking_source' => BookingSource::External->value,
                'external_booking_id' => $externalId,
                'price' => 0,
                'status' => ReservationStatus::Confirmed->value,
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'external_last_synced_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $created = 0;
        $updated = 0;

        if (! empty($rows)) {
            $externalIds = array_keys($rows);

            $existing = Reservation::query()
                ->where('apartment_id', $apartment->id)
                ->where('booking_source', BookingSource::External->value)
                ->whereIn('external_booking_id', $externalIds)
                ->get()
                ->keyBy('external_booking_id');

            $updated = $existing->count();
            $created = max(0, count($rows) - $updated);

            $values = array_values($rows);

            Reservation::upsert(
                $values,
                ['external_booking_id', 'apartment_id'],
                ['status', 'check_in', 'check_out', 'external_last_synced_at', 'updated_at']
            );
        }

        $cancelled = 0;
        if (! empty($seenExternalIds)) {
            $cancelled = Reservation::query()
                ->where('apartment_id', $apartment->id)
                ->where('booking_source', BookingSource::External->value)
                ->whereNotIn('external_booking_id', $seenExternalIds)
                ->whereIn('status', [ReservationStatus::Pending, ReservationStatus::Confirmed])
                ->update([
                    'status' => ReservationStatus::Cancelled->value,
                    'external_last_synced_at' => now(),
                ]);
        }

        return compact('created', 'updated', 'cancelled');
    }

    public function syncAll(): array
    {
        $result = ['apartments' => 0, 'created' => 0, 'updated' => 0, 'cancelled' => 0, 'errors' => 0];

        Apartment::query()
            ->whereNotNull('external_ical_url')
            ->where('external_ical_url', '!=', '')
            ->chunkById(50, function ($apartments) use (&$result): void {
                foreach ($apartments as $apartment) {
                    $result['apartments']++;

                    try {
                        $synced = $this->syncApartment($apartment);
                        $result['created'] += $synced['created'];
                        $result['updated'] += $synced['updated'];
                        $result['cancelled'] += $synced['cancelled'];
                    } catch (\Throwable $exception) {
                        $result['errors']++;
                        Log::warning('ICS sync failed', [
                            'apartment_id' => $apartment->id,
                            'message' => $exception->getMessage(),
                        ]);
                    }
                }
            });

        return $result;
    }

    private function extractDateRange(object $event): ?array
    {
        if (! isset($event->DTSTART) || ! isset($event->DTEND)) {
            return null;
        }

        $start = CarbonImmutable::instance($event->DTSTART->getDateTime());
        $end = CarbonImmutable::instance($event->DTEND->getDateTime());

        if ($end->lessThanOrEqualTo($start)) {
            return null;
        }

        $checkOut = $end->startOfDay();
        $checkIn = $start->startOfDay();

        if ($checkOut->lessThan($checkIn)) {
            return null;
        }

        return [$checkIn, $checkOut];
    }
}
