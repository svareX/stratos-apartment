<?php

namespace App\Services\Ical;

use App\Enums\BookingSource;
use App\Enums\ReservationStatus;
use App\Models\Apartment;
use App\Models\Reservation;
use Carbon\CarbonImmutable;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class IcalExportService
{
    public function forApartment(Apartment $apartment): string
    {
        $calendar = Calendar::create("{$apartment->name} reservations")
            ->refreshInterval(15);

        $reservations = $apartment->reservations()
            ->whereIn('status', [ReservationStatus::Pending, ReservationStatus::Confirmed])
            ->where('booking_source', BookingSource::Local->value)
            ->get();

        foreach ($reservations as $reservation) {
            /** @var Reservation $reservation */
            $start = CarbonImmutable::parse($reservation->check_in)->startOfDay();

            $endExclusive = CarbonImmutable::parse($reservation->check_out)->startOfDay();

            $calendar->event(
                Event::create('Reserved')
                    ->uniqueIdentifier("reservation-{$reservation->id}@stratos-apartment")
                    ->startsAt($start, false)
                    ->endsAt($endExclusive, false)
                    ->fullDay()
            );
        }

        return $calendar->get();
    }
}
