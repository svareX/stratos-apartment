<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Carbon\Carbon;
use App\Models\Reservation;
use App\Enums\ReservationStatus;

class ReservationCalendarWidget extends Widget
{
    protected string $view = 'filament.widgets.reservation-calendar-widget';

    protected int | string | array $columnSpan = 'full';

    public $displayMonth;
    public $displayYear;
    public $bookedDates = [];

    public function mount()
    {
        $now = Carbon::now();
        $this->displayMonth = $now->month;
        $this->displayYear = $now->year;
        
        $this->loadBookedDates();
    }

    public function loadBookedDates()
    {
        $reservations = Reservation::whereIn('status', [ReservationStatus::Confirmed, ReservationStatus::Pending])->get();
        
        $dates = [];
        foreach ($reservations as $reservation) {
            $start = Carbon::parse($reservation->check_in);
            $end = Carbon::parse($reservation->check_out);
            
            while ($start->lte($end)) {
                $dates[] = [
                    'date' => $start->toDateString(),
                    'reservation_id' => $reservation->id,
                    'guest_name' => $reservation->user ? $reservation->user->name : 'N/A',
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
        ];
    }
}