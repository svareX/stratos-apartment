<?php

namespace App\Livewire;

use App\Enums\BookingSource;
use App\Enums\ReservationStatus;
use App\Mail\ReservationConfirmation;
use App\Models\Apartment;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class ReservationForm extends Component
{
    public $step = 1;

    public $apartment_id;

    public $apartments;

    public $displayMonth;

    public $displayYear;

    public $bookedDates = [];

    public $start_date;

    public $end_date;

    public $adults = 1;

    public $children = 0;

    public $pets = false;

    public $pricePerNight = 0;

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
        'apartment_id' => 'required|exists:apartments,id',
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
        $this->apartments = Apartment::where('active', true)->get();

        $now = Carbon::now();
        $this->displayMonth = $now->month;
        $this->displayYear = $now->year;

        $rq = request();
        
        $this->apartment_id = $rq->query('apartment_id');
        
        if ($this->apartment_id) {
            $this->updateApartmentDetails();
            $this->loadBookedDates();
        }

        if ($rq->filled('start_date')) {
            $this->start_date = $rq->query('start_date');
        }
        
        if ($rq->filled('end_date')) {
            $this->end_date = $rq->query('end_date');
        }

        $this->adults = $rq->filled('adults') ? (int) $rq->query('adults') : 1;
        $this->children = $rq->filled('children') ? (int) $rq->query('children') : 0;
        $this->pets = $rq->filled('pets') ? (bool) $rq->query('pets') : false;
    }

    public function updatedApartmentId()
    {
        $this->updateApartmentDetails();
        $this->start_date = null;
        $this->end_date = null;
        $this->loadBookedDates();
    }

    public function updateApartmentDetails()
    {
        $apt = $this->apartments->firstWhere('id', $this->apartment_id);
        if ($apt) {
            $this->pricePerNight = $apt->base_price ?? 0;
        }
    }

    public function loadBookedDates()
    {
        if (! $this->apartment_id) {
            $this->bookedDates = [];
            return;
        }

        $this->bookedDates = Reservation::where('apartment_id', $this->apartment_id)
            ->whereIn('status', [ReservationStatus::Confirmed, ReservationStatus::Pending])
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
            'calendarCells' => $this->generateCalendar(),
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
        if (!$this->apartment_id) {
            return;
        }

        if (in_array($date, $this->bookedDates)) {
            return;
        }

        if (! $this->start_date || ($this->start_date && $this->end_date)) {
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
        if (! $this->start_date || ! $this->end_date) {
            return 0;
        }

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
        if ($n === 0) {
            return 0;
        }

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
            if (! $this->apartment_id) {
                $this->addError('apartment_id', __('Please select an apartment.'));
                return;
            }
            if (! $this->start_date || ! $this->end_date) {
                $this->addError('dates', __('Please select a date from and to.'));
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
                'name' => trim($this->first_name.' '.$this->last_name),
                'password' => Hash::make(Str::random(12)),
            ]
        );

        $apartment = Apartment::findOrFail($this->apartment_id);

        $reservation = Reservation::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user->id,
            'check_in' => $this->start_date,
            'check_out' => $this->end_date,
            'price' => $this->total(),
            'status' => ReservationStatus::Pending,
            'booking_source' => BookingSource::Local,
        ]);

        Mail::to($user->email)->queue(new ReservationConfirmation($reservation));

        session()->put('reservation_completed', true);
        $this->redirectRoute('reservation.result', navigate: true);
    }
}