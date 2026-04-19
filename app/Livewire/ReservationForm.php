<?php

namespace App\Livewire;

use App\Enums\BookingSource;
use App\Enums\ReservationStatus;
use App\Mail\ReservationConfirmation;
use App\Models\Apartment;
use App\Models\Reservation;
use App\Models\User;
use App\Models\ApartmentPackage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;

class ReservationForm extends Component
{
    public $step = 1;

    #[Url]
    public $apartment_id;

    #[Url]
    public $apartment;

    public $apartments;

    public $displayMonth;

    public $displayYear;

    public $bookedDates = [];

    #[Url]
    public $start_date;

    #[Url]
    public $end_date;

    #[Url]
    public $adults = 1;

    #[Url]
    public $children = 0;

    #[Url]
    public $pets = false;

    public $pricePerNight = 0;

    public $cleaningFee = 600;

    public $daysForCleaningFee = 3;

    public $first_name;

    public $last_name;

    public $email;

    public $phone;

    public $address;

    public $city;

    public $postal_code;

    public $country;

    public $apartment_package_id;

    public $availablePackages = [];

    public $packagePrice = 0;

    public $selectedPackageName = '';

    protected function rules()
    {
        return [
            'apartment_id' => 'required|exists:apartments,id',
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'email' => 'required|email:rfc,dns',
            'phone' => ['required', 'string', 'min:9', 'max:20', 'regex:/^\+?[0-9\s\-\(\)]+$/'],
            'address' => ['required', 'string', 'max:255', 'regex:/[\pL]/u'],
            'city' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-\.]+$/u'],
            'postal_code' => ['required', 'string', 'min:4', 'max:10', 'regex:/^(?=.*[0-9])[a-zA-Z0-9\s\-]+$/'],
            'country' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-\.]+$/u'],
        ];
    }

    protected function messages()
    {
        return [
            'apartment_id.required' => __('Please select an apartment.'),
            'first_name.required' => __('First name is required.'),
            'first_name.max' => __('First name is too long.'),
            'first_name.regex' => __('First name cannot contain numbers or special characters.'),
            'last_name.required' => __('Last name is required.'),
            'last_name.max' => __('Last name is too long.'),
            'last_name.regex' => __('Last name cannot contain numbers or special characters.'),
            'email.required' => __('Email address is required.'),
            'email.email' => __('Please enter a valid email address.'),
            'phone.required' => __('Phone number is required.'),
            'phone.regex' => __('Please enter a valid phone number (e.g. +420 123 456 789).'),
            'phone.min' => __('Phone number is too short.'),
            'phone.max' => __('Phone number is too long.'),
            'address.required' => __('Address is required.'),
            'address.regex' => __('Address must contain a street name, not just numbers.'),
            'city.required' => __('City is required.'),
            'city.regex' => __('City cannot contain numbers or special characters.'),
            'postal_code.required' => __('ZIP code is required.'),
            'postal_code.regex' => __('Please enter a valid ZIP code containing at least one number.'),
            'country.required' => __('Country is required.'),
            'country.regex' => __('Country cannot contain numbers or special characters.'),
        ];
    }

    public function mount()
    {
        // eager-load packages so initial selection can access them without extra reloads
        $this->apartments = Apartment::where('active', true)->with('packages')->get();
        $now = Carbon::now();

        if ($this->start_date) {
            try {
                $start = Carbon::parse($this->start_date);
                $this->displayMonth = $start->month;
                $this->displayYear = $start->year;
            } catch (\Exception $e) {
                $this->displayMonth = $now->month;
                $this->displayYear = $now->year;
            }
        } else {
            $this->displayMonth = $now->month;
            $this->displayYear = $now->year;
        }

        // default package values — set before attempting to load apartment-specific packages
        $this->apartment_package_id = '';
        $this->availablePackages = [];
        $this->packagePrice = 0;
        $this->selectedPackageName = '';

        // Support incoming apartment value from either `apartment` (slug) or `apartment_id` (id)
        $incoming = $this->apartment ?? $this->apartment_id;
        if ($incoming) {
            // attempt to resolve slug or id to a numeric apartment id
            $query = Apartment::where('active', true);
            $query->where(function ($q) use ($incoming) {
                if (is_numeric($incoming)) {
                    $q->where('id', $incoming)->orWhere('slug', $incoming);
                } else {
                    $q->where('slug', $incoming);
                }
            });

            $found = $query->first();
            if ($found) {
                $this->apartment_id = $found->id;
                $this->updateApartmentDetails();
                $this->loadBookedDates();
            }
        }


        $this->adults = $this->adults ? (int) $this->adults : 1;
        $this->children = $this->children ? (int) $this->children : 0;
        $this->pets = filter_var($this->pets, FILTER_VALIDATE_BOOLEAN);
    }

    public function render()
    {
        return view('livewire.reservation-form', [
            'calendarCells' => $this->generateCalendar(),
        ]);
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
        // Ensure we load a fresh Apartment model with packages to avoid hydration/serialization issues
        $apt = Apartment::with('packages')->find($this->apartment_id);
        if ($apt) {
            $this->pricePerNight = $apt->base_price ?? 0;
            $this->cleaningFee = $apt->cleaning_fee ?? 600;
            $this->daysForCleaningFee = $apt->days_for_cleaning_fee ?? 3;

            // include features and icon so the frontend can render rich package cards
            $this->availablePackages = $apt->packages->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->price,
                    'features' => $p->translated_features,
                    'icon' => $p->icon,
                ];
            })->toArray();
        } else {
            $this->availablePackages = [];
        }

        // reset package selection when apartment changes
        $this->apartment_package_id = '';
        $this->packagePrice = 0;
        $this->selectedPackageName = '';
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

    public function updatedApartmentPackageId()
    {
        if ($this->apartment_package_id === 'standard') {
            $this->packagePrice = 0;
            $this->selectedPackageName = __('Standard');
        } elseif (is_numeric($this->apartment_package_id) && $this->apartment_package_id) {
            $pkg = ApartmentPackage::find((int) $this->apartment_package_id);
            $this->packagePrice = $pkg ? $pkg->price : 0;
            $this->selectedPackageName = $pkg ? $pkg->name : '';
        } else {
            $this->packagePrice = 0;
            $this->selectedPackageName = '';
        }
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
        if (! $this->apartment_id) {
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

        return $n > 0 && $n <= $this->daysForCleaningFee;
    }

    public function total()
    {
        $n = $this->nights();
        if ($n === 0) {
            return 0;
        }
        // package price is a flat fee (not per-night)
        $total = $n * $this->pricePerNight;
        $total += $this->packagePrice ?? 0;

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
            if ($this->apartment_package_id === null || $this->apartment_package_id === '') {
                $this->addError('apartment_package_id', __('Please select a package.'));

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
            'apartment_package_id' => is_numeric($this->apartment_package_id) ? (int) $this->apartment_package_id : null,
            'package_price' => $this->packagePrice,
            'status' => ReservationStatus::Pending,
            'booking_source' => BookingSource::Local,
        ]);

        Mail::to($user->email)->queue(new ReservationConfirmation($reservation));

        session()->put('reservation_completed', true);
        $this->redirectRoute('reservation.result', ['locale' => app()->getLocale()], navigate: true);
    }
}
