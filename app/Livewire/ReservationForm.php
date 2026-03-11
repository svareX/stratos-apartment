<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Apartment;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use App\Enums\BookingSource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ReservationForm extends Component
{
    public $step = 1;
    public $displayMonth;
    public $displayYear;
    public $bookedDates = [];

    public $start_date;
    public $end_date;
    public $pricePerNight = 1790;
    public $cleaningFee = 600;

    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $postal_code;
    public $country;

    protected $rules = [
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'email' => 'required|email',
        'phone' => 'required|string',
        'address' => 'required',
        'city' => 'required',
        'postal_code' => 'required',
        'country' => 'required',
    ];

    public function mount()
    {
        $now = Carbon::now();
        $this->displayMonth = $now->month;
        $this->displayYear = $now->year;
        
        $this->loadBookedDates();
    }

    public function loadBookedDates()
    {
        $this->bookedDates = Reservation::whereIn('status', [ReservationStatus::Confirmed, ReservationStatus::Pending])
            ->get()
            ->flatMap(function ($reservation) {
                $dates = [];
                $start = Carbon::parse($reservation->check_in);
                $end = Carbon::parse($reservation->check_out);
                
                while ($start->lte($end)) {
                    $dates[] = $start->toDateString();
                    $start->addDay();
                }
                return $dates;
            })
            ->unique()
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.reservation-form', [
            'calendarCells' => $this->generateCalendar()
        ]);
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

    public function selectDate($date)
    {
        if (in_array($date, $this->bookedDates)) return;

        if (!$this->start_date || ($this->start_date && $this->end_date)) {
            $this->start_date = $date;
            $this->end_date = null;
        } else {
            $start = Carbon::parse($this->start_date);
            $selected = Carbon::parse($date);

            if ($selected->lt($start)) {
                $this->start_date = $date;
            } else {
                $tempDate = $start->copy();
                while ($tempDate->lte($selected)) {
                    if (in_array($tempDate->toDateString(), $this->bookedDates)) {
                        $this->start_date = $date;
                        $this->end_date = null;
                        return;
                    }
                    $tempDate->addDay();
                }
                $this->end_date = $date;
            }
        }
    }

    public function nights()
    {
        if (!$this->start_date || !$this->end_date) return 0;
        return Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date));
    }

    public function cleaningApplies()
    {
        $n = $this->nights();
        return $n > 0 && $n <= 3;
    }

    public function total()
    {
        $n = $this->nights();
        if ($n === 0) return 0;
        
        $total = $n * $this->pricePerNight;
        if ($this->cleaningApplies()) {
            $total += $this->cleaningFee;
        }
        return $total;
    }

    public function nextStep()
    {
        $this->resetErrorBag();

        if ($this->step === 1) {
            if (!$this->start_date || !$this->end_date) {
                $this->addError('dates', 'Prosím vyberte datum od a do.');
                return;
            }
        }

        if ($this->step === 2) {
            $this->validate();
        }

        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function confirm()
    {
        $this->validate();

        $user = User::firstOrCreate(
            ['email' => $this->email],
            [
                'name' => trim($this->first_name . ' ' . $this->last_name),
                'password' => Hash::make(Str::random(12)),
            ]
        );

        $apartment = Apartment::first();

        if (!$apartment) {
            $apartment = Apartment::create([
                'name' => 'Main Apartment',
                'address' => 'Default Address',
                'capacity' => 2,
                'base_price' => $this->pricePerNight,
                'active' => true,
            ]);
        }

        $reservation = Reservation::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user->id,
            'check_in' => $this->start_date,
            'check_out' => $this->end_date,
            'price' => $this->total(),
            'status' => ReservationStatus::Pending,
            'booking_source' => BookingSource::Local,
        ]);

        session()->flash('reservation_completed', true);
        
        $this->redirectRoute('reservation.success', navigate: true);
    }
}