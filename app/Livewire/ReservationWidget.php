<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class ReservationWidget extends Component
{
    public $location = 'Ramzová';
    public $dateStart;
    public $dateEnd;
    public $adults = 2;
    public $children = 0;
    public $pets = true;
    
    public $displayMonth;
    public $displayYear;

    public function mount()
    {
        $now = Carbon::now();
        $this->displayMonth = $now->month;
        $this->displayYear = $now->year;
        
        $this->dateStart = $now->format('Y-m-d');
        $this->dateEnd = $now->addDays(2)->format('Y-m-d');
    }

    public function prevMonth()
    {
        $date = Carbon::create($this->displayYear, $this->displayMonth, 1)->subMonth();
        $this->displayMonth = $date->month;
        $this->displayYear = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->displayYear, $this->displayMonth, 1)->addMonth();
        $this->displayMonth = $date->month;
        $this->displayYear = $date->year;
    }

    public function selectDate($date)
    {
        if (!$this->dateStart || ($this->dateStart && $this->dateEnd)) {
            $this->dateStart = $date;
            $this->dateEnd = null;
        } else {
            if (Carbon::parse($date)->lt(Carbon::parse($this->dateStart))) {
                $this->dateStart = $date;
                $this->dateEnd = null;
            } else {
                $this->dateEnd = $date;
            }
        }
    }

    private function generateCalendar()
    {
        $startOfMonth = Carbon::create($this->displayYear, $this->displayMonth, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        $calendar = [];

        $startOfCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $currentDay = $startOfCalendar->copy();

        while ($currentDay->lte($endOfCalendar)) {
            $calendar[] = [
                'date' => $currentDay->format('Y-m-d'),
                'day' => $currentDay->day,
                'isCurrentMonth' => $currentDay->month === $this->displayMonth,
            ];
            $currentDay->addDay();
        }

        return $calendar;
    }

    public function render()
    {
        return view('livewire.reservation-widget', [
            'monthName' => Carbon::create($this->displayYear, $this->displayMonth, 1)->translatedFormat('F'),
            'calendarDays' => $this->generateCalendar(),
        ]);
    }
}