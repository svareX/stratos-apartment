<?php

namespace App\Livewire;

use App\Enums\BookingSource;
use App\Enums\ReservationStatus;
use App\Mail\ReservationConfirmation;
use App\Models\Apartment;
use App\Models\ApartmentPackage;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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

    #[Url]
    public $package;

    public $pricePerNight = 0;

    public $pricePerNightEur = null;

    public $capacity = null;

    public $cleaningFee = 600;

    public $cleaningFeeEur = null;

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

    public $packagePriceEur = null;

    public $selectedPackageName = '';

    protected function rules()
    {
        $maxAdults = $this->capacity ?? 10;
        $maxChildren = $this->capacity ? (int) floor($this->capacity / 2) : 5;

        return [
            'apartment_id' => 'required|exists:apartments,id',
            'adults' => ['required', 'integer', 'min:1', 'max:'.$maxAdults],
            'children' => ['required', 'integer', 'min:0', 'max:'.$maxChildren],
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

        $this->apartment_package_id = '';
        $this->availablePackages = [];
        $this->packagePrice = 0;
        $this->selectedPackageName = '';

        $incoming = $this->apartment ?? $this->apartment_id;
        if ($incoming) {
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


        // If the URL provided a `package` query param, apply it to the form
        if (! empty($this->package)) {
            $this->apartment_package_id = $this->package;
            $this->updatedApartmentPackageId();
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
        if (empty($this->apartment_id)) {
            $this->availablePackages = [];
            $this->pricePerNight = 0;
            $this->pricePerNightEur = null;
            $this->cleaningFee = 600;
            $this->cleaningFeeEur = null;
            $this->daysForCleaningFee = 3;
            $this->capacity = null;

            $this->apartment_package_id = '';
            $this->packagePrice = 0;
            $this->selectedPackageName = '';

            return;
        }

        $apt = Apartment::with('packages')->find($this->apartment_id);
        if ($apt) {
            $this->pricePerNight = $apt->base_price ?? 0;
            $this->pricePerNightEur = $apt->base_price_eur ?? null;
            $this->cleaningFee = $apt->cleaning_fee ?? 600;
            $this->cleaningFeeEur = $apt->cleaning_fee_eur ?? null;
            $this->daysForCleaningFee = $apt->days_for_cleaning_fee ?? 3;
            $this->capacity = $apt->capacity ?? null;

            $packagesArr = [];
            foreach ($apt->packages as $p) {
                /** @var \App\Models\ApartmentPackage $p */
                $packagesArr[] = [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->price,
                    'price_eur' => $p->price_eur ?? null,
                    'features' => $p->translated_features,
                    'icon' => $p->icon,
                ];
            }
            $this->availablePackages = $packagesArr;
        } else {
            $this->availablePackages = [];
        }

        $this->apartment_package_id = '';
        $this->packagePrice = 0;
        $this->selectedPackageName = '';

        if ($this->capacity !== null) {
            if ($this->adults > $this->capacity) {
                $this->adults = (int) $this->capacity;
            }

            $maxChildren = (int) floor($this->capacity / 2);
            if ($this->children > $maxChildren) {
                $this->children = $maxChildren;
            }
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

                while ($start->lt($end)) {
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
            $this->packagePriceEur = $pkg ? $pkg->price_eur : null;
        } else {
            $this->packagePrice = 0;
            $this->selectedPackageName = '';
        }
    }

    public function updatedAdults($value)
    {
        $this->adults = (int) $value;

        if ($this->capacity !== null && $this->adults > $this->capacity) {
            $this->adults = (int) $this->capacity;
        }
    }

    public function updatedChildren($value)
    {
        $this->children = (int) $value;

        $maxChildren = $this->capacity !== null ? (int) floor($this->capacity / 2) : null;
        if ($maxChildren !== null && $this->children > $maxChildren) {
            $this->children = $maxChildren;
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

        $isBooked = in_array($date, $this->bookedDates);

        if (empty($this->start_date) || $this->end_date) {
            if ($isBooked) {
                return;
            }

            $this->start_date = $date;
            $this->end_date = null;
        } else {
            $start = Carbon::parse($this->start_date);
            $selected = Carbon::parse($date);

            if ($selected->lt($start)) {
                if ($isBooked) {
                    return;
                }

                $this->start_date = $date;
            } else {
                $tempDate = $start->copy();

                while ($tempDate->lt($selected)) {
                    if (in_array($tempDate->toDateString(), $this->bookedDates)) {
                        if ($isBooked) {
                            return;
                        }

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

        $total = $n * $this->pricePerNight;
        $total += $this->packagePrice ?? 0;

        if ($this->cleaningApplies()) {
            $total += $this->cleaningFee;
        }

        return $total;
    }

    private function getEurRate(): float
    {
        return Cache::remember('cnb_eur_rate', 3600, function () {
            try {
                $response = Http::get('https://www.cnb.cz/cs/financni-trhy/devizovy-trh/kurzy-devizoveho-trhu/kurzy-devizoveho-trhu/denni_kurz.txt');

                if ($response->successful()) {
                    $lines = explode("\n", $response->body());
                    foreach ($lines as $line) {
                        if (str_contains($line, 'EMU|euro|1|EUR')) {
                            $parts = explode('|', $line);

                            return (float) str_replace(',', '.', $parts[4]);
                        }
                    }
                }
            } catch (\Throwable $e) {
            }

            return 25.00;
        });
    }

    public function totalEur()
    {
        $n = $this->nights();
        if ($n === 0) {
            return 0;
        }

        if ($this->pricePerNightEur !== null) {
            $total = $n * $this->pricePerNightEur;

            if ($this->packagePriceEur !== null) {
                $total += $this->packagePriceEur;
            } elseif (! empty($this->packagePrice)) {
                $total += round($this->packagePrice / $this->getEurRate(), 2);
            }

            if ($this->cleaningApplies()) {
                if ($this->cleaningFeeEur !== null) {
                    $total += $this->cleaningFeeEur;
                } else {
                    $total += round($this->cleaningFee / $this->getEurRate(), 2);
                }
            }

            return round($total, 2);
        }

        $rate = $this->getEurRate();

        return round($this->total() / $rate, 2);
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

        $apartment = Apartment::findOrFail($this->apartment_id);

        if (! $apartment->active) {
            $this->addError('apartment_id', __('This apartment is temporarily unavailable for booking.'));

            return;
        }

        $exists = Reservation::where('apartment_id', $apartment->id)
            ->whereIn('status', [ReservationStatus::Pending, ReservationStatus::Confirmed])
            ->where(function ($query) {
                $query->where('check_in', '<', $this->end_date)
                    ->where('check_out', '>', $this->start_date);
            })
            ->exists();

        if ($exists) {
            $this->addError('dates', __('Tento termín byl v mezidobí obsazen. Vyberte si prosím jiný.'));
            $this->step = 1;
            $this->loadBookedDates();

            return;
        }

        $user = User::firstOrCreate(
            ['email' => $this->email],
            [
                'name' => trim($this->first_name.' '.$this->last_name),
                'password' => Hash::make(Str::random(12)),
            ]
        );

        $user->update([
            'name' => trim($this->first_name.' '.$this->last_name),
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
        ]);

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
