<div class="w-full">
    <div class="mb-6 flex items-center justify-between px-4 pt-2 sm:px-8">
        @foreach ([__('Date'), __('Details'), __('Confirmation')] as $i => $label)
            <div class="relative flex-1 text-center">
                <div
                    class="inline-flex items-center justify-center w-12 h-12 rounded-full transition-colors duration-300 {{ $step == ($i + 1) ? 'bg-secondary text-primary' : 'bg-gray border border-border text-primary' }}"
                >
                    <span class="font-bold">{{ $i + 1 }}</span>
                </div>
                <div
                    class="mt-2 text-sm font-medium {{ $step == ($i + 1) ? 'text-secondary' : 'text-muted' }}"
                >
                    {{ $label }}
                </div>
            </div>
        @endforeach
    </div>

    <div
        class="border-border text-navy rounded-xl border bg-white p-6 shadow-sm sm:p-8"
    >
        @if ($step === 1)
            <div
                class="relative grid grid-cols-1 items-start gap-6 lg:grid-cols-3"
            >
                <div
                    class="border-border col-span-2 rounded-lg border bg-white p-6"
                >
                    <div
                        class="mb-8 p-5 bg-purpleGhost rounded-xl border {{ $errors->has('apartment_id') ? 'border-red' : 'border-purplePale' }}"
                    >
                        <label
                            class="text-primary mb-2 block text-sm font-bold tracking-tight uppercase"
                            >{{ __('Select Apartment') }}</label
                        >
                        <div class="relative">
                            <select
                                wire:model.live="apartment_id"
                                class="w-full rounded-lg border px-4 py-3 bg-white text-navy appearance-none cursor-pointer transition-colors {{ $errors->has('apartment_id') ? 'border-red focus:border-red focus:ring-red' : 'border-border focus:border-primary focus:ring-primary' }}"
                            >
                                <option value="">
                                    {{ __('Select an apartment...') }}
                                </option>
                                @foreach ($apartments as $apt)
                                    <option value="{{ $apt->id }}">
                                        {{ $apt->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span
                                class="text-purple pointer-events-none absolute top-1/2 right-4 -translate-y-1/2"
                                >▼</span
                            >
                        </div>
                        @error ('apartment_id')
                            <p class="text-red mt-2 flex items-center gap-1 text-xs font-medium">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    @php $packagesDisabled = empty($apartment_id); @endphp
                    <div
                        class="mb-6 p-5 {{ $packagesDisabled ? 'bg-white' : 'bg-purpleGhost' }} rounded-xl border {{ $errors->has('apartment_package_id') ? 'border-red' : 'border-purplePale' }}"
                    >
                        <label
                            class="text-primary mb-2 block text-sm font-bold tracking-tight uppercase"
                            >{{ __('Select Package') }}</label
                        >
                        <div class="relative">
                            <div
                                class="flex gap-4 overflow-x-auto py-2 -mx-2 px-2 {{ $packagesDisabled ? 'opacity-30 pointer-events-none' : '' }}"
                            >
                                <div
                                    role="button"
                                    tabindex="0"
                                    wire:click="$set('apartment_package_id', 'standard')"
                                    class="min-w-[220px] flex flex-col p-4 rounded-2xl border border-border bg-white transition-transform duration-200 {{ $packagesDisabled ? 'opacity-30 pointer-events-none cursor-not-allowed' : 'cursor-pointer ' . ($apartment_package_id === 'standard' ? 'border-primary scale-105 shadow-md' : 'hover:shadow-lg hover:-translate-y-1') }}"
                                >
                                    <div
                                        class="flex items-center justify-start"
                                    >
                                        <span
                                            class="text-tealD text-2xl font-extrabold"
                                            >{{ number_format(0, 0, ',', ' ') }} {{ __('CZK') }}</span
                                        >
                                    </div>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-sm">🏷️</span>
                                        <span
                                            class="text-navy text-sm font-bold"
                                            >{{ __('Standard') }}</span
                                        >
                                    </div>
                                    <div class="text-muted mt-2 text-xs">
                                        <p><span>✓</span> <span>{{ __('Basic booking without extras') }}</span></p>
                                    </div>
                                </div>

                                @foreach ($availablePackages as $pkg)
                                    <div
                                        role="button"
                                        tabindex="0"
                                        wire:click="$set('apartment_package_id', {{ $pkg['id'] }})"
                                        class="min-w-[220px] flex flex-col p-4 rounded-2xl border border-border bg-white transition-transform duration-200 {{ $packagesDisabled ? 'opacity-30 pointer-events-none cursor-not-allowed' : 'cursor-pointer ' . ($apartment_package_id == $pkg['id'] ? 'border-primary scale-105 shadow-md' : 'hover:shadow-lg hover:-translate-y-1') }}"
                                    >
                                        <div
                                            class="flex items-center justify-start"
                                        >
                                            @php
                                                $locale = app()->getLocale();
                                                $pkgEur = $pkg['price_eur'] ?? null;
                                                $showPkgEurPrimary = in_array($locale, ['en', 'de']) && $pkgEur !== null;
                                            @endphp
                                            @if ($showPkgEurPrimary)
                                                <span
                                                    class="text-tealD text-2xl font-extrabold"
                                                    >{{ number_format($pkgEur, 2, ',', ' ') }} {{ __('EUR') }}</span
                                                >
                                            @else
                                                <span
                                                    class="text-tealD text-2xl font-extrabold"
                                                    >{{ number_format($pkg['price'], 0, ',', ' ') }} {{ __('CZK') }}</span
                                                >
                                            @endif
                                        </div>
                                        <div
                                            class="mt-2 flex items-center gap-2"
                                        >
                                            <span
                                                class="text-sm"
                                                >{!! $pkg['icon'] ?? '' !!}</span
                                            >
                                            <span
                                                class="text-navy text-sm font-bold"
                                                >{{ $pkg['name'] }}</span
                                            >
                                        </div>
                                        <div class="text-muted mt-2 text-xs">
                                            @foreach ($pkg['features'] ?? [] as $feature)
                                                <p class="leading-tight"><span>✓</span> <span>{{ $feature }}</span></p>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @error ('apartment_package_id')
                            <p class="text-red mt-2 flex items-center gap-1 text-xs font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div
                        class="transition-all duration-500 ease-in-out {{ $apartment_id && $apartment_package_id !== null && $apartment_package_id !== '' ? 'opacity-100 translate-y-0' : 'opacity-30 pointer-events-none -translate-y-2' }}"
                    >
                        <div
                            class="mb-6 flex flex-col items-center justify-between gap-y-2 md:flex-row"
                        >
                            <div class="flex items-center gap-3">
                                <button
                                    wire:click="prevMonth"
                                    class="hover:bg-purplePale text-primary rounded-full p-2 hover:cursor-pointer"
                                >
                                    &larr;
                                </button>
                                <h3 class="text-primary text-2xl font-bold">
                                    {{ \Carbon\Carbon::create($displayYear, $displayMonth, 1)->translatedFormat('F Y') }}
                                </h3>
                                <button
                                    wire:click="nextMonth"
                                    class="hover:bg-purplePale text-primary rounded-full p-2 hover:cursor-pointer"
                                >
                                    &rarr;
                                </button>
                            </div>
                            <div class="text-muted text-sm">
                                {{ __('Tip: pick start and end dates') }}
                            </div>
                        </div>

                        <div
                            class="calendar w-full bg-white rounded-lg {{ $errors->has('dates') ? 'ring-2 ring-red ring-offset-2' : '' }}"
                        >
                            <div
                                class="calendar-weekdays mb-2 grid grid-cols-7"
                            >
                                @foreach ([__('Mo'), __('Tu'), __('We'), __('Th'), __('Fr'), __('Sa'), __('Su')] as $dayName)
                                    <div
                                        class="text-muted py-2 text-center text-xs font-bold uppercase"
                                    >
                                        {{ $dayName }}
                                    </div>
                                @endforeach
                            </div>
                            <div class="grid grid-cols-7 gap-2">
                                @foreach ($calendarCells as $date)
                                    @if (!$date)
                                        <div class="h-14"></div>
                                    @else
                                        @php
                                            $isBooked = in_array($date, $bookedDates);
                                            $isStart = $start_date === $date;
                                            $isEnd = $end_date === $date;
                                            $isInRange = $start_date && $end_date && $date > $start_date && $date < $end_date;
                                            $isSelected = $isStart || $isEnd;
                                            $isClickableCheckout = $isBooked && $start_date && !$end_date && $date > $start_date;
                                            $isDisabled = $isBooked && !$isClickableCheckout;
                                        @endphp
                                        <button
                                            wire:click="selectDate('{{ $date }}')"
                                            @if ($isDisabled) disabled @endif
                                            class="h-14 relative flex flex-col items-center justify-center rounded-md transition-all text-sm hover:cursor-pointer hover:text-purple!
                                            {{ $isDisabled && !$isSelected ? 'bg-purpleGhost text-muted opacity-50 cursor-not-allowed' : 'hover:bg-purplePale text-purple' }}
                                            {{ $isSelected ? 'bg-purple text-white shadow-md z-10 scale-105' : '' }}
                                            {{ $isInRange ? 'bg-purplePale text-purple' : '' }}
                                            {{ $isStart ? 'rounded-l-lg text-white!' : '' }}
                                            {{ $isEnd ? 'rounded-r-lg text-white!' : '' }}
                                            "
                                        >
                                            <span
                                                class="relative z-20 font-bold"
                                                >{{ \Carbon\Carbon::parse($date)->day }}</span
                                            >
                                            @if ($isBooked && !$isEnd)
                                                <span
                                                    class="text-[10px] font-bold uppercase opacity-50"
                                                    >{{ __('Booked') }}</span
                                                >
                                            @endif
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @error ('dates')
                            <p class="text-red mt-3 flex items-center justify-center gap-1 text-xs font-medium">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="relative z-20 col-span-1 lg:-mt-12">
                    <div class="space-y-6">
                        <div
                            class="bg-white p-6 rounded-lg shadow-xl border border-border transition-all duration-500 {{ $apartment_id ? 'opacity-100 translate-y-0' : 'opacity-30 pointer-events-none translate-y-2' }}"
                        >
                            <h4
                                class="mb-4 text-sm text-[13px] font-bold tracking-wider uppercase"
                            >
                                {{ __('Stay summary') }}
                            </h4>
                            <div
                                class="border-border mb-4 space-y-3 border-b pb-4"
                            >
                                @php
                                    $locale = app()->getLocale();
                                    $showEurPrimary = in_array($locale, ['en', 'de']) && $pricePerNightEur !== null;
                                @endphp

                                <div class="flex justify-between text-sm">
                                    <span
                                        class="text-muted"
                                        >{{ __('Accommodation:') }}</span
                                    >
                                    @if ($showEurPrimary)
                                        <span class="font-bold"
                                            >{{ number_format($this->nights() * $pricePerNightEur, 2, ',', ' ') }} {{ __('EUR') }}</span
                                        >
                                    @else
                                        <span class="font-bold"
                                            >{{ number_format($this->nights() * $pricePerNight, 0, ',', ' ') }} {{ __('CZK') }}</span
                                        >
                                    @endif
                                </div>

                                @if ($showEurPrimary)
                                    <div
                                        class="text-muted flex justify-between text-xs"
                                    >
                                        <span></span>
                                        <span>
                                            {{ number_format($this->nights() * $pricePerNight, 0, ',', ' ') }} {{ __('CZK') }}</span
                                        >
                                    </div>
                                @elseif ($pricePerNightEur)
                                    <div
                                        class="text-muted flex justify-between text-xs"
                                    >
                                        <span></span>
                                        <span
                                            >{{ number_format($this->nights() * $pricePerNightEur, 2, ',', ' ') }} {{ __('EUR') }}</span
                                        >
                                    </div>
                                @endif

                                @php $showPkgPrimary = in_array($locale, ['en', 'de']) && $packagePriceEur !== null; @endphp
                                <div class="flex justify-between text-sm">
                                    <span
                                        class="text-muted"
                                        >{{ __('Package:') }}</span
                                    >
                                    @if ($showPkgPrimary)
                                        <span class="font-bold"
                                            >+ {{ number_format($packagePriceEur, 2, ',', ' ') }} {{ __('EUR') }}</span
                                        >
                                    @else
                                        <span class="font-bold"
                                            >+ {{ number_format($packagePrice, 0, ',', ' ') }} {{ __('CZK') }}</span
                                        >
                                    @endif
                                </div>

                                @if ($showPkgPrimary)
                                    <div
                                        class="text-muted flex justify-between text-xs"
                                    >
                                        <span></span>
                                        <span
                                            >+ {{ number_format($packagePrice, 0, ',', ' ') }} {{ __('CZK') }}</span
                                        >
                                    </div>
                                @elseif ($packagePriceEur)
                                    <div
                                        class="text-muted flex justify-between text-xs"
                                    >
                                        <span></span>
                                        <span
                                            >+ {{ number_format($packagePriceEur, 2, ',', ' ') }} {{ __('EUR') }}</span
                                        >
                                    </div>
                                @endif

                                @if ($this->cleaningApplies())
                                    <div class="flex justify-between text-sm">
                                        <span
                                            class="text-muted"
                                            >{{ __('Cleaning:') }}</span
                                        >
                                        @if (in_array($locale, ['en', 'de']) && $cleaningFeeEur !== null)
                                            <span class="font-bold"
                                                >+ {{ number_format($cleaningFeeEur, 2, ',', ' ') }} {{ __('EUR') }}</span
                                            >
                                        @else
                                            <span class="font-bold"
                                                >+ {{ number_format($cleaningFee, 0, ',', ' ') }} {{ __('CZK') }}</span
                                            >
                                        @endif
                                    </div>
                                    @if (in_array($locale, ['en', 'de']) && $cleaningFeeEur !== null)
                                        <div
                                            class="text-muted flex justify-between text-xs"
                                        >
                                            <span></span>
                                            <span
                                                >+ {{ number_format($cleaningFee, 0, ',', ' ') }} {{ __('CZK') }}</span
                                            >
                                        </div>
                                    @elseif ($cleaningFeeEur !== null)
                                        <div
                                            class="text-muted flex justify-between text-xs"
                                        >
                                            <span></span>
                                            <span
                                                >+ {{ number_format($cleaningFeeEur, 2, ',', ' ') }} {{ __('EUR') }}</span
                                            >
                                        </div>
                                    @endif
                                @endif

                                <div class="flex justify-between pt-2 text-sm">
                                    <span
                                        class="text-muted"
                                        >{{ __('Number of nights:') }}</span
                                    >
                                    <span
                                        class="font-bold"
                                        >{{ $this->nights() }}</span
                                    >
                                </div>
                            </div>
                            <div class="text-primary mb-4 text-2xl font-bold">
                                @php $locale = app()->getLocale(); $showEurPrimary = in_array($locale, ['en', 'de']) && $pricePerNightEur !== null; @endphp
                                @if ($showEurPrimary)
                                    {{ number_format($this->totalEur(), 2, ',', ' ') }} {{ __('EUR') }}
                                    <div class="text-muted mt-1 text-sm">
                                        {{ number_format($this->total(), 0, ',', ' ') }} {{ __('CZK') }}
                                    </div>
                                @else
                                    {{ number_format($this->total(), 0, ',', ' ') }} {{ __('CZK') }}
                                    @if ($pricePerNightEur !== null)
                                        <div class="text-muted mt-1 text-sm">
                                            {{ number_format($this->totalEur(), 2, ',', ' ') }} {{ __('EUR') }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <button
                                wire:click="nextStep"
                                class="bg-teal w-full rounded-lg py-3 font-bold text-white transition-opacity hover:cursor-pointer hover:opacity-90"
                            >
                                {{ __('Continue') }}
                            </button>
                        </div>

                        <div
                            class="transition-all duration-500 delay-100 {{ $apartment_id ? 'opacity-100 translate-y-0' : 'opacity-0 pointer-events-none translate-y-4' }}"
                        >
                            <div class="mb-4 grid grid-cols-2 gap-4">
                                <div
                                    class="bg-purpleGhost border-border flex flex-col rounded-xl border p-3"
                                >
                                    <span
                                        class="text-primary mb-2 text-xs font-bold uppercase"
                                        >{{ __('Adults') }}</span
                                    >
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <button
                                            wire:click="$set('adults', {{ max(1, (int)$adults - 1) }})"
                                            class="border-border text-primary hover:bg-purplePale flex h-8 w-8 items-center justify-center rounded-full border bg-white font-bold transition hover:cursor-pointer"
                                        >
                                            -
                                        </button>
                                        <span
                                            class="text-navy font-bold"
                                            >{{ $adults ?? 1 }}</span
                                        >
                                        <button
                                            wire:click="$set('adults', {{ (int)$adults + 1 }})"
                                            class="border-border text-primary hover:bg-purplePale flex h-8 w-8 items-center justify-center rounded-full border bg-white font-bold transition hover:cursor-pointer"
                                        >
                                            +
                                        </button>
                                    </div>
                                </div>

                                <div
                                    class="bg-purpleGhost border-border flex flex-col rounded-xl border p-3"
                                >
                                    <span
                                        class="text-primary mb-2 text-xs font-bold uppercase"
                                        >{{ __('Children') }}</span
                                    >
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <button
                                            wire:click="$set('children', {{ max(0, (int)$children - 1) }})"
                                            class="border-border text-primary hover:bg-purplePale flex h-8 w-8 items-center justify-center rounded-full border bg-white font-bold transition hover:cursor-pointer"
                                        >
                                            -
                                        </button>
                                        <span
                                            class="text-navy font-bold"
                                            >{{ $children ?? 0 }}</span
                                        >
                                        <button
                                            wire:click="$set('children', {{ (int)$children + 1 }})"
                                            class="border-border text-primary hover:bg-purplePale flex h-8 w-8 items-center justify-center rounded-full border bg-white font-bold transition hover:cursor-pointer"
                                        >
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-purpleGhost border-border flex flex-col justify-center rounded-xl border p-4"
                            >
                                <label
                                    class="flex h-full cursor-pointer items-center gap-3 px-2"
                                >
                                    <flux:checkbox
                                        wire:model.live="pets"
                                        class="border-border border bg-white!"
                                    />
                                    <span class="text-navy text-sm font-bold"
                                        >{{ __('With pet') }} 🐶</span
                                    >
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif ($step === 2)
            <div class="mx-auto max-w-3xl">
                <h3 class="text-primary mb-8 text-center text-2xl font-bold">
                    {{ __('Personal details') }}
                </h3>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label
                            class="text-primary mb-1 block text-sm font-bold tracking-tight uppercase"
                            >{{ __('First name') }}</label
                        >
                        <input
                            type="text"
                            wire:model.defer="first_name"
                            class="w-full rounded-lg border px-3 py-2 bg-white text-navy transition-colors {{ $errors->has('first_name') ? 'border-red focus:border-red focus:ring-red' : 'border-border focus:border-primary focus:ring-primary' }}"
                        />
                        @error ('first_name')
                            <p class="text-red mt-1.5 flex items-center gap-1 text-xs font-medium">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="text-primary mb-1 block text-sm font-bold tracking-tight uppercase"
                            >{{ __('Last name') }}</label
                        >
                        <input
                            type="text"
                            wire:model.defer="last_name"
                            class="w-full rounded-lg border px-3 py-2 bg-white text-navy transition-colors {{ $errors->has('last_name') ? 'border-red focus:border-red focus:ring-red' : 'border-border focus:border-primary focus:ring-primary' }}"
                        />
                        @error ('last_name')
                            <p class="text-red mt-1.5 flex items-center gap-1 text-xs font-medium">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="text-primary mb-1 block text-sm font-bold tracking-tight uppercase"
                            >{{ __('Email') }}</label
                        >
                        <input
                            type="email"
                            wire:model.defer="email"
                            class="w-full rounded-lg border px-3 py-2 bg-white text-navy transition-colors {{ $errors->has('email') ? 'border-red focus:border-red focus:ring-red' : 'border-border focus:border-primary focus:ring-primary' }}"
                        />
                        @error ('email')
                            <p class="text-red mt-1.5 flex items-center gap-1 text-xs font-medium">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="text-primary mb-1 block text-sm font-bold tracking-tight uppercase"
                            >{{ __('Phone') }}</label
                        >
                        <input
                            type="text"
                            wire:model.defer="phone"
                            class="w-full rounded-lg border px-3 py-2 bg-white text-navy transition-colors {{ $errors->has('phone') ? 'border-red focus:border-red focus:ring-red' : 'border-border focus:border-primary focus:ring-primary' }}"
                        />
                        @error ('phone')
                            <p class="text-red mt-1.5 flex items-center gap-1 text-xs font-medium">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label
                            class="text-primary mb-1 block text-sm font-bold tracking-tight uppercase"
                            >{{ __('Address') }}</label
                        >
                        <input
                            type="text"
                            wire:model.defer="address"
                            class="w-full rounded-lg border px-3 py-2 bg-white text-navy transition-colors {{ $errors->has('address') ? 'border-red focus:border-red focus:ring-red' : 'border-border focus:border-primary focus:ring-primary' }}"
                        />
                        @error ('address')
                            <p class="text-red mt-1.5 flex items-center gap-1 text-xs font-medium">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="text-primary mb-1 block text-sm font-bold tracking-tight uppercase"
                            >{{ __('City') }}</label
                        >
                        <input
                            type="text"
                            wire:model.defer="city"
                            class="w-full rounded-lg border px-3 py-2 bg-white text-navy transition-colors {{ $errors->has('city') ? 'border-red focus:border-red focus:ring-red' : 'border-border focus:border-primary focus:ring-primary' }}"
                        />
                        @error ('city')
                            <p class="text-red mt-1.5 flex items-center gap-1 text-xs font-medium">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="text-primary mb-1 block text-sm font-bold tracking-tight uppercase"
                            >{{ __('ZIP code') }}</label
                        >
                        <input
                            type="text"
                            wire:model.defer="postal_code"
                            class="w-full rounded-lg border px-3 py-2 bg-white text-navy transition-colors {{ $errors->has('postal_code') ? 'border-red focus:border-red focus:ring-red' : 'border-border focus:border-primary focus:ring-primary' }}"
                        />
                        @error ('postal_code')
                            <p class="text-red mt-1.5 flex items-center gap-1 text-xs font-medium">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label
                            class="text-primary mb-1 block text-sm font-bold tracking-tight uppercase"
                            >{{ __('Country') }}</label
                        >
                        <input
                            type="text"
                            wire:model.defer="country"
                            class="w-full rounded-lg border px-3 py-2 bg-white text-navy transition-colors {{ $errors->has('country') ? 'border-red focus:border-red focus:ring-red' : 'border-border focus:border-primary focus:ring-primary' }}"
                        />
                        @error ('country')
                            <p class="text-red mt-1.5 flex items-center gap-1 text-xs font-medium">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="mt-12 flex justify-between">
                    <button
                        wire:click="prevStep"
                        class="border-primary text-primary hover:bg-purplePale rounded-lg border-2 px-8 py-3 font-bold transition hover:cursor-pointer"
                    >
                        {{ __('Back') }}
                    </button>
                    <button
                        wire:click="nextStep"
                        class="bg-primary rounded-lg px-8 py-3 font-bold text-white transition hover:cursor-pointer hover:opacity-90"
                    >
                        {{ __('Next step') }}
                    </button>
                </div>
            </div>

        @elseif ($step === 3)
            <div class="mx-auto max-w-2xl">
                <h3
                    class="text-primary mb-6 text-center text-2xl font-bold sm:mb-8"
                >
                    {{ __('Reservation recap') }}
                </h3>

                <div
                    class="bg-purpleGhost border-border space-y-6 rounded-xl border p-6 sm:p-8"
                >
                    <div
                        class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2"
                    >
                        <div class="col-span-1 sm:col-span-2">
                            <span
                                class="text-muted block text-xs font-bold uppercase"
                                >{{ __('Apartment') }}</span
                            >
                            <span
                                class="text-primary text-lg font-bold"
                                >{{ $apartments->firstWhere('id', $apartment_id)?->name }}</span
                            >
                        </div>
                        <div>
                            <span
                                class="text-muted block text-xs font-bold uppercase"
                                >{{ __('Date') }}</span
                            >
                            <span class="text-navy font-medium"
                                >{{ \Carbon\Carbon::parse($start_date)->format('d. m. Y') }} – {{ \Carbon\Carbon::parse($end_date)->format('d. m. Y') }}</span
                            >
                            <p>
                                <span class="text-navy font-medium"
                                    >{{ $apartments->firstWhere('id', $apartment_id)?->check_in_time_formatted ?? '15:00' }} – {{ $apartments->firstWhere('id', $apartment_id)?->check_out_time_formatted ?? '10:00' }}</span
                                >
                            </p>
                        </div>
                        <div>
                            <span
                                class="text-muted block text-xs font-bold uppercase"
                                >{{ __('Guest') }}</span
                            >
                            <span class="text-navy font-medium"
                                >{{ $first_name }} {{ $last_name }}</span
                            >
                        </div>

                        <div
                            class="border-border col-span-1 border-t pt-4 sm:col-span-2"
                        ></div>

                        <div>
                            <span
                                class="text-muted block text-xs font-bold uppercase"
                                >{{ __('Price breakdown') }}</span
                            >
                            <div
                                class="text-navy mt-1 space-y-1 text-sm font-medium"
                            >
                                @php $locale = app()->getLocale(); $showEurPrimary = in_array($locale, ['en', 'de']) && $pricePerNightEur !== null; @endphp
                                <div>
                                    {{ __('Accommodation:') }}
                                    @if ($showEurPrimary)
                                        {{ number_format($this->nights() * $pricePerNightEur, 2, ',', ' ') }} {{ __('EUR') }}
                                        <span class="text-muted text-xs"
                                            >({{ number_format($this->nights() * $pricePerNight, 0, ',', ' ') }} CZK)</span
                                        >
                                    @else
                                        {{ number_format($this->nights() * $pricePerNight, 0, ',', ' ') }} {{ __('CZK') }}
                                        @if ($pricePerNightEur !== null)
                                            <span class="text-muted text-xs"
                                                >({{ number_format($this->nights() * $pricePerNightEur, 2, ',', ' ') }} EUR)</span
                                            >
                                        @endif
                                    @endif
                                </div>

                                @php $showPkgPrimary = in_array($locale, ['en', 'de']) && $packagePriceEur !== null; @endphp
                                <div>
                                    {{ __('Package:') }} {{ $this->selectedPackageName ?: __('Standard') }} —
                                    @if ($showPkgPrimary)
                                        {{ number_format($packagePriceEur, 2, ',', ' ') }} {{ __('EUR') }}
                                        <span class="text-muted text-xs"
                                            >({{ number_format($packagePrice, 0, ',', ' ') }} CZK)</span
                                        >
                                    @else
                                        {{ number_format($packagePrice, 0, ',', ' ') }} {{ __('CZK') }}
                                        @if ($packagePriceEur)
                                            <span class="text-muted text-xs"
                                                >({{ number_format($packagePriceEur, 2, ',', ' ') }} EUR)</span
                                            >
                                        @endif
                                    @endif
                                </div>

                                @if ($this->cleaningApplies())
                                    <div class="text-muted italic">
                                        {{ __('Cleaning fee:') }}
                                        @if (in_array($locale, ['en', 'de']) && $cleaningFeeEur !== null)
                                            + {{ number_format($cleaningFeeEur, 2, ',', ' ') }} {{ __('EUR') }}
                                            <span class="text-muted text-xs"
                                                >({{ number_format($cleaningFee, 0, ',', ' ') }} CZK)</span
                                            >
                                        @else
                                            + {{ number_format($cleaningFee, 0, ',', ' ') }} {{ __('CZK') }}
                                            @if ($cleaningFeeEur !== null)
                                                <span class="text-muted text-xs"
                                                    >({{ number_format($cleaningFeeEur, 2, ',', ' ') }} EUR)</span
                                                >
                                            @endif
                                        @endif
                                    </div>
                                @else
                                    <div class="text-tealD italic">
                                        {{ __('Cleaning fee:') }}
                                        <span
                                            class="text-muted mx-1 text-xs line-through"
                                            >{{ number_format($cleaningFee, 0, ',', ' ') }}</span
                                        >+ 0 {{ __('CZK') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col justify-end sm:text-right">
                            <span
                                class="text-muted block text-xs font-bold uppercase"
                                >{{ __('Total price') }}</span
                            >
                            @php $locale = app()->getLocale(); $showEurTotal = in_array($locale, ['en', 'de']) && $pricePerNightEur !== null; @endphp
                            @if ($showEurTotal)
                                <span class="text-primary text-2xl font-bold"
                                    >{{ number_format($this->totalEur(), 2, ',', ' ') }} {{ __('EUR') }}</span
                                >
                                <span class="text-muted text-sm">
                                    {{ number_format($this->total(), 0, ',', ' ') }} {{ __('CZK') }}</span
                                >
                            @else
                                <span class="text-primary text-2xl font-bold"
                                    >{{ number_format($this->total(), 0, ',', ' ') }} {{ __('CZK') }}</span
                                >
                                @if ($pricePerNightEur !== null)
                                    <span class="text-muted text-sm">
                                        {{ number_format($this->totalEur(), 2, ',', ' ') }} {{ __('EUR') }}</span
                                    >
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div
                    class="mt-8 flex flex-col-reverse justify-between gap-4 sm:flex-row"
                >
                    <button
                        wire:click="prevStep"
                        class="border-primary text-primary hover:bg-purplePale w-full rounded-lg border-2 px-8 py-3 text-center font-bold transition hover:cursor-pointer sm:w-auto"
                    >
                        {{ __('Edit details') }}
                    </button>
                    <button
                        wire:click="confirm"
                        class="bg-primary w-full rounded-lg px-8 py-3 text-center font-bold text-white shadow-lg transition hover:cursor-pointer hover:opacity-90 sm:w-auto"
                    >
                        {{ __('Confirm and pay') }}
                    </button>
                </div>

                @if (session()->has('message'))
                    <div
                        class="bg-tealL text-tealD mt-8 rounded-lg p-4 text-center font-bold"
                    >
                        {{ session('message') }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
