<div class="w-full">
    <div class="flex items-center justify-between pt-2 mb-6 px-4 sm:px-8">
        @foreach([__('Date'), __('Details'), __('Confirmation')] as $i => $label)
            <div class="flex-1 text-center relative">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full transition-colors duration-300 {{ $step == ($i + 1) ? 'bg-secondary text-primary' : 'bg-gray border border-border text-primary' }}">
                    <span class="font-bold">{{ $i + 1 }}</span>
                </div>
                <div class="mt-2 text-sm font-medium {{ $step == ($i + 1) ? 'text-secondary' : 'text-muted' }}">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-border p-6 sm:p-8 text-navy">
        @if ($step === 1)
            <div class="relative grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                
                <div class="col-span-2 bg-white p-6 rounded-lg border border-border">
                    
                    <div class="mb-8 p-5 bg-purpleGhost rounded-xl border border-purplePale">
                        <label class="block text-sm font-bold text-primary mb-2 uppercase tracking-tight">{{ __('Select Apartment') }}</label>
                        <div class="relative">
                            <select wire:model.live="apartment_id" class="w-full rounded-lg border border-border px-4 py-3 bg-white text-navy focus:border-primary focus:ring-primary appearance-none cursor-pointer">
                                <option value="">{{ __('Select an apartment...') }}</option>
                                @foreach($apartments as $apt)
                                    <option value="{{ $apt->id }}">{{ $apt->name }}</option>
                                @endforeach
                            </select>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-purple pointer-events-none">▼</span>
                        </div>
                        @error('apartment_id') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="transition-all duration-500 ease-in-out {{ $apartment_id ? 'opacity-100 translate-y-0' : 'opacity-30 pointer-events-none -translate-y-2' }}">
                        <div class="flex flex-col gap-y-2 md:flex-row items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <button wire:click="prevMonth" class="p-2 hover:bg-purplePale rounded-full text-primary">&larr;</button>
                                <h3 class="text-2xl font-bold text-primary">{{ \Carbon\Carbon::create($displayYear, $displayMonth, 1)->translatedFormat('F Y') }}</h3>
                                <button wire:click="nextMonth" class="p-2 hover:bg-purplePale rounded-full text-primary">&rarr;</button>
                            </div>
                            <div class="text-sm text-muted">{{ __('Tip: pick start and end dates') }}</div>
                        </div>

                        <div class="calendar w-full bg-white">
                            <div class="calendar-weekdays grid grid-cols-7 mb-2">
                                @foreach([__('Mo'), __('Tu'), __('We'), __('Th'), __('Fr'), __('Sa'), __('Su')] as $dayName)
                                    <div class="text-center text-xs font-bold text-muted uppercase py-2">{{ $dayName }}</div>
                                @endforeach
                            </div>
                            <div class="grid grid-cols-7 gap-2">
                                @foreach($calendarCells as $date)
                                    @if(!$date)
                                        <div class="h-14"></div>
                                    @else
                                        @php
                                            $isBooked = in_array($date, $bookedDates);
                                            $isStart = $start_date === $date;
                                            $isEnd = $end_date === $date;
                                            $isInRange = $start_date && $end_date && $date > $start_date && $date < $end_date;
                                            $isSelected = $isStart || $isEnd;
                                        @endphp
                                        <button 
                                            wire:click="selectDate('{{ $date }}')"
                                            @if($isBooked) disabled @endif
                                            class="h-14 relative flex flex-col items-center justify-center rounded-md transition-all text-sm hover:cursor-pointer hover:text-purple!
                                            {{ $isBooked ? 'bg-purpleGhost text-muted opacity-50 cursor-not-allowed' : 'hover:bg-purplePale text-purple' }}
                                            {{ $isSelected ? 'bg-purple text-white shadow-md z-10 scale-105' : '' }}
                                            {{ $isInRange ? 'bg-purplePale text-purple' : '' }}
                                            {{ $isStart ? 'rounded-l-lg text-white!' : '' }}
                                            {{ $isEnd ? 'rounded-r-lg text-white!' : '' }}
                                            "
                                        >
                                            <span class="font-bold relative z-20">{{ \Carbon\Carbon::parse($date)->day }}</span>
                                            @if($isBooked) <span class="text-[10px] uppercase font-bold opacity-50">{{ __('Booked') }}</span> @endif
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @error('dates') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="col-span-1 relative lg:-mt-12 z-20">
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-lg shadow-xl border border-border transition-all duration-500 {{ $apartment_id ? 'opacity-100 translate-y-0' : 'opacity-30 pointer-events-none translate-y-2' }}">
                            <h4 class="font-bold text-[13px] mb-4 uppercase tracking-wider text-sm">{{ __('Stay summary') }}</h4>
                            <div class="space-y-3 border-b border-border pb-4 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted">{{ __('Accommodation:') }}</span>
                                    <span class="font-bold">{{ number_format($this->nights() * $pricePerNight, 0, ',', ' ') }} {{ __('CZK') }}</span>
                                </div>
                                @if($this->cleaningApplies())
                                    <div class="flex justify-between text-sm">
                                        <span class="text-muted">{{ __('Cleaning:') }}</span>
                                        <span class="font-bold">+ {{ number_format($cleaningFee, 0, ',', ' ') }} {{ __('CZK') }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-sm pt-2">
                                    <span class="text-muted">{{ __('Number of nights:') }}</span>
                                    <span class="font-bold">{{ $this->nights() }}</span>
                                </div>
                            </div>
                            <div class="text-2xl font-bold mb-4 text-primary">
                                {{ number_format($this->total(), 0, ',', ' ') }} {{ __('CZK') }}
                            </div>
                            <button wire:click="nextStep" class="w-full py-3 bg-teal text-white rounded-lg font-bold hover:opacity-90 transition-opacity hover:cursor-pointer">{{ __('Continue') }}</button>
                        </div>

                        <div class="transition-all duration-500 delay-100 {{ $apartment_id ? 'opacity-100 translate-y-0' : 'opacity-0 pointer-events-none translate-y-4' }}">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="flex flex-col p-3 bg-purpleGhost rounded-xl border border-border">
                                    <span class="text-xs font-bold text-primary uppercase mb-2">{{ __('Adults') }}</span>
                                    <div class="flex items-center justify-between">
                                        <button wire:click="$set('adults', {{ max(1, (int)$adults - 1) }})" class="w-8 h-8 rounded-full bg-white border border-border flex items-center justify-center font-bold text-primary hover:bg-purplePale transition hover:cursor-pointer">-</button>
                                        <span class="font-bold text-navy">{{ $adults ?? 1 }}</span>
                                        <button wire:click="$set('adults', {{ (int)$adults + 1 }})" class="w-8 h-8 rounded-full bg-white border border-border flex items-center justify-center font-bold text-primary hover:bg-purplePale transition hover:cursor-pointer">+</button>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col p-3 bg-purpleGhost rounded-xl border border-border">
                                    <span class="text-xs font-bold text-primary uppercase mb-2">{{ __('Children') }}</span>
                                    <div class="flex items-center justify-between">
                                        <button wire:click="$set('children', {{ max(0, (int)$children - 1) }})" class="w-8 h-8 rounded-full bg-white border border-border flex items-center justify-center font-bold text-primary hover:bg-purplePale transition hover:cursor-pointer">-</button>
                                        <span class="font-bold text-navy">{{ $children ?? 0 }}</span>
                                        <button wire:click="$set('children', {{ (int)$children + 1 }})" class="w-8 h-8 rounded-full bg-white border border-border flex items-center justify-center font-bold text-primary hover:bg-purplePale transition hover:cursor-pointer">+</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col p-4 bg-purpleGhost rounded-xl border border-border justify-center">
                                <label class="flex items-center gap-3 cursor-pointer h-full px-2">
                                    <flux:checkbox wire:model.live="pets" class="bg-white! border border-border"/>
                                    <span class="font-bold text-sm text-navy">{{ __('With pet') }} 🐶</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif ($step === 2)
            <div class="max-w-3xl mx-auto">
                <h3 class="text-2xl font-bold text-primary mb-8 text-center">{{ __('Personal details') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">{{ __('First name') }}</label>
                        <input type="text" wire:model.defer="first_name" class="w-full rounded-lg border border-border px-3 py-2 bg-white text-navy focus:border-primary focus:ring-primary" />
                        @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">{{ __('Last name') }}</label>
                        <input type="text" wire:model.defer="last_name" class="w-full rounded-lg border border-border px-3 py-2 bg-white text-navy focus:border-primary focus:ring-primary" />
                        @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">{{ __('Email') }}</label>
                        <input type="email" wire:model.defer="email" class="w-full rounded-lg border border-border px-3 py-2 bg-white text-navy focus:border-primary focus:ring-primary" />
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">{{ __('Phone') }}</label>
                        <input type="text" wire:model.defer="phone" class="w-full rounded-lg border border-border px-3 py-2 bg-white text-navy focus:border-primary focus:ring-primary" />
                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">{{ __('Address') }}</label>
                        <input type="text" wire:model.defer="address" class="w-full rounded-lg border border-border px-3 py-2 bg-white text-navy focus:border-primary focus:ring-primary" />
                        @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">{{ __('City') }}</label>
                        <input type="text" wire:model.defer="city" class="w-full rounded-lg border border-border px-3 py-2 bg-white text-navy focus:border-primary focus:ring-primary" />
                        @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">{{ __('ZIP code') }}</label>
                        <input type="text" wire:model.defer="postal_code" class="w-full rounded-lg border border-border px-3 py-2 bg-white text-navy focus:border-primary focus:ring-primary" />
                        @error('postal_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">{{ __('Country') }}</label>
                        <input type="text" wire:model.defer="country" class="w-full rounded-lg border border-border px-3 py-2 bg-white text-navy focus:border-primary focus:ring-primary" />
                        @error('country') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-12 flex justify-between">
                    <button wire:click="prevStep" class="px-8 py-3 border-2 border-primary text-primary font-bold rounded-lg hover:bg-purplePale transition hover:cursor-pointer">{{ __('Back') }}</button>
                    <button wire:click="nextStep" class="px-8 py-3 bg-primary text-white font-bold rounded-lg hover:opacity-90 transition hover:cursor-pointer">{{ __('Next step') }}</button>
                </div>
            </div>

        @elseif ($step === 3)
            <div class="max-w-2xl mx-auto">
                <h3 class="text-2xl font-bold text-primary mb-6 sm:mb-8 text-center">{{ __('Reservation recap') }}</h3>
                
                <div class="space-y-6 bg-purpleGhost p-6 sm:p-8 rounded-xl border border-border">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4">
                        <div class="col-span-1 sm:col-span-2">
                            <span class="block text-xs font-bold text-muted uppercase">{{ __('Apartment') }}</span>
                            <span class="font-bold text-lg text-primary">{{ $apartments->firstWhere('id', $apartment_id)?->name }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-muted uppercase">{{ __('Date') }}</span>
                            <span class="font-medium text-navy">{{ \Carbon\Carbon::parse($start_date)->format('d. m. Y') }} – {{ \Carbon\Carbon::parse($end_date)->format('d. m. Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-muted uppercase">{{ __('Guest') }}</span>
                            <span class="font-medium text-navy">{{ $first_name }} {{ $last_name }}</span>
                        </div>
                        
                        <div class="col-span-1 sm:col-span-2 border-t border-border pt-4"></div>
                        
                        <div>
                            <span class="block text-xs font-bold text-muted uppercase">{{ __('Price breakdown') }}</span>
                            <div class="text-sm font-medium text-navy space-y-1 mt-1">
                                <div>{{ __('Accommodation:') }} {{ number_format($this->nights() * $pricePerNight, 0, ',', ' ') }} {{ __('CZK') }}</div>
                                @if($this->cleaningApplies())
                                    <div class="text-muted italic">{{ __('Cleaning fee:') }} + {{ number_format($cleaningFee, 0, ',', ' ') }} {{ __('CZK') }}</div>
                                @else
                                    <div class="text-tealD italic">{{ __('Cleaning fee:') }} <span class="line-through text-muted text-xs mx-1">{{ number_format($cleaningFee, 0, ',', ' ') }}</span>+ 0 {{ __('CZK') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="sm:text-right flex flex-col justify-end">
                            <span class="block text-xs font-bold text-muted uppercase">{{ __('Total price') }}</span>
                            <span class="font-bold text-2xl text-primary">{{ number_format($this->total(), 0, ',', ' ') }} {{ __('CZK') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex flex-col-reverse sm:flex-row justify-between gap-4">
                    <button wire:click="prevStep" class="w-full sm:w-auto px-8 py-3 border-2 border-primary text-primary font-bold rounded-lg hover:bg-purplePale transition hover:cursor-pointer text-center">{{ __('Edit details') }}</button>
                    <button wire:click="confirm" class="w-full sm:w-auto px-8 py-3 bg-primary text-white font-bold rounded-lg shadow-lg hover:opacity-90 transition hover:cursor-pointer text-center">{{ __('Confirm and pay') }}</button>
                </div>

                @if (session()->has('message'))
                    <div class="mt-8 p-4 bg-tealL text-tealD rounded-lg text-center font-bold">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>