<?php

namespace App\Filament\Widgets;

use App\Enums\ReservationStatus;
use App\Models\Apartment;
use App\Models\Reservation;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class ReservationCalendarWidget extends Widget
{
    protected string $view = 'filament.widgets.reservation-calendar-widget';

    protected int|string|array $columnSpan = 'full';

    public $displayMonth;

    public $displayYear;

    public $bookedDates = [];

    public $apartmentId = 'all';

    public array $apartmentColors = [];

    public function mount()
    {
        $now = Carbon::now();
        $this->displayMonth = $now->month;
        $this->displayYear = $now->year;

        $colors = ['#ef4444', '#8bc34a', '#9c27b0', '#00bcd4', '#ff9800', '#2196f3', '#e91e63', '#3f51b5', '#009688'];
        $apartments = Apartment::all();

        foreach ($apartments as $index => $apt) {
            $this->apartmentColors[$apt->id] = $colors[$index % count($colors)];
        }

        $this->loadBookedDates();
    }

    public function updatedApartmentId()
    {
        $this->loadBookedDates();
    }

    public function loadBookedDates()
    {
        $query = Reservation::with(['user', 'apartment'])
            ->whereIn('status', [ReservationStatus::Confirmed, ReservationStatus::Pending]);

        if ($this->apartmentId !== 'all') {
            $query->where('apartment_id', $this->apartmentId);
        }

        $reservations = $query->get();

        $dates = [];
        foreach ($reservations as $reservation) {
            $start = Carbon::parse($reservation->check_in);
            $end = Carbon::parse($reservation->check_out);
            $color = $this->apartmentColors[$reservation->apartment_id] ?? '#414CE8';

            while ($start->lte($end)) {
                $dates[] = [
                    'date' => $start->toDateString(),
                    'reservation_id' => $reservation->id,
                    'guest_name' => $reservation->user ? $reservation->user->name : 'N/A',
                    'apartment_name' => $reservation->apartment ? $reservation->apartment->name : 'N/A',
                    'color' => $color,
                ];
                $start->addDay();
            }
        }

        $this->bookedDates = collect($dates)->groupBy('date')->toArray();
    }

    public function generateCalendar()
    {
        $firstDay = Carbon::create($this->displayYear, $this->displayMonth, 1);
        $daysInMonth = $firstDay->daysInMonth;
        $startOffset = $firstDay->dayOfWeekIso - 1;

        $cells = [];

        for ($i = 0; $i < $startOffset; $i++) {
            $cells[] = null;
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $cells[] = Carbon::create($this->displayYear, $this->displayMonth, $day)->toDateString();
        }

        return $cells;
    }

    public function prevMonth()
    {
        $dt = Carbon::create($this->displayYear, $this->displayMonth, 1)->subMonth();
        $this->displayMonth = $dt->month;
        $this->displayYear = $dt->year;
    }

    public function nextMonth()
    {
        $dt = Carbon::create($this->displayYear, $this->displayMonth, 1)->addMonth();
        $this->displayMonth = $dt->month;
        $this->displayYear = $dt->year;
    }

    protected function getViewData(): array
    {
        return [
            'calendarCells' => $this->generateCalendar(),
            'apartments' => Apartment::all(),
            'apartmentColors' => $this->apartmentColors,
        ];
    }
}
