<div class="w-full">
    <div class="flex items-center justify-between mb-12">
        @foreach(['Datum', 'Údaje', 'Potvrzení'] as $i => $label)
            <div class="flex-1 text-center relative">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full transition-colors duration-300 {{ $step == ($i + 1) ? 'bg-secondary text-primary' : 'bg-white text-primary' }}">
                    <span class="font-bold">{{ $i + 1 }}</span>
                </div>
                <div class="mt-2 text-sm font-medium {{ $step == ($i + 1) ? 'text-secondary' : 'text-white' }}">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        @if ($step === 1)
            <div class="flex flex-col lg:flex-row gap-12">
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-6">
                        <button wire:click="prevMonth" class="p-2 hover:bg-gray-100 rounded-full text-primary">&larr;</button>
                        <h3 class="text-2xl font-bold text-primary">{{ \Carbon\Carbon::create($displayYear, $displayMonth, 1)->format('F Y') }}</h3>
                        <button wire:click="nextMonth" class="p-2 hover:bg-gray-100 rounded-full text-primary">&rarr;</button>
                    </div>

                    <div class="calendar w-full">
                        <div class="calendar-weekdays grid grid-cols-7 mb-2">
                            @foreach(['Po', 'Út', 'St', 'Čt', 'Pá', 'So', 'Ne'] as $dayName)
                                <div class="text-center text-xs font-bold text-gray-400 uppercase py-2">{{ $dayName }}</div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            @foreach($calendarCells as $date)
                                @if(!$date)
                                    <div class="h-16"></div>
                                @else
                                    @php
                                        $isBooked = in_array($date, $bookedDates);
                                        $isStart = $start_date === $date;
                                        $isEnd = $end_date === $date;
                                        $isInRange = $start_date && $end_date && $date > $start_date && $date < $end_date;
                                    @endphp
                                    <button 
                                        wire:click="selectDate('{{ $date }}')"
                                        @if($isBooked) disabled @endif
                                        class="h-16 relative flex flex-col items-center justify-center rounded-md transition-all
                                        {{ $isBooked ? 'bg-gray-100 text-gray-300 cursor-not-allowed' : 'hover:bg-blue-50 text-primary' }}
                                        {{ $isStart || $isEnd ? 'bg-primary text-white hover:bg-primary shadow-md z-10' : '' }}
                                        {{ $isInRange ? 'bg-blue-100' : '' }}
                                        "
                                    >
                                        <span class="text-sm font-bold">{{ \Carbon\Carbon::parse($date)->day }}</span>
                                        @if($isBooked) <span class="text-[10px] uppercase font-bold opacity-50">Obsazeno</span> @endif
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @error('dates') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div class="w-full lg:w-80 bg-gray-50 p-6 rounded-lg h-fit">
                    <h4 class="font-bold text-primary mb-4 uppercase tracking-wider text-sm">Shrnutí pobytu</h4>
                    <div class="space-y-3 border-b border-gray-200 pb-4 mb-4">
                        <div class="flex justify-between text-sm text-primary">
                            <span class="text-gray-500">Ubytování:</span>
                            <span class="font-bold">{{ number_format($this->nights() * $pricePerNight, 0, ',', ' ') }} Kč</span>
                        </div>
                        
                        @if($this->cleaningApplies())
                            <div class="flex justify-between text-sm text-primary">
                                <span class="text-gray-500">Úklid:</span>
                                <span class="font-bold">+ {{ number_format($cleaningFee, 0, ',', ' ') }} Kč</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-sm text-primary pt-2">
                            <span class="text-gray-500">Počet nocí:</span>
                            <span class="font-bold">{{ $this->nights() }}</span>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-primary mb-6">
                        {{ number_format($this->total(), 0, ',', ' ') }} Kč
                    </div>
                    <button wire:click="nextStep" class="w-full py-3 bg-primary text-white rounded-lg font-bold hover:opacity-90 transition-opacity">Pokračovat</button>
                </div>
            </div>

        @elseif ($step === 2)
            <div class="max-w-3xl mx-auto">
                <h3 class="text-2xl font-bold text-primary mb-8 text-center">Osobní údaje</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">Křestní jméno</label>
                        <input type="text" wire:model.defer="first_name" class="w-full rounded-lg border-gray-300 text-primary focus:border-primary focus:ring-primary" />
                        @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">Příjmení</label>
                        <input type="text" wire:model.defer="last_name" class="w-full rounded-lg border-gray-300 text-primary focus:border-primary focus:ring-primary" />
                        @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">Email</label>
                        <input type="email" wire:model.defer="email" class="w-full rounded-lg border-gray-300 text-primary focus:border-primary focus:ring-primary" />
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">Telefon</label>
                        <input type="text" wire:model.defer="phone" class="w-full rounded-lg border-gray-300 text-primary focus:border-primary focus:ring-primary" />
                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">Adresa</label>
                        <input type="text" wire:model.defer="address" class="w-full rounded-lg border-gray-300 text-primary focus:border-primary focus:ring-primary" />
                        @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">Město</label>
                        <input type="text" wire:model.defer="city" class="w-full rounded-lg border-gray-300 text-primary focus:border-primary focus:ring-primary" />
                        @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">PSČ</label>
                        <input type="text" wire:model.defer="postal_code" class="w-full rounded-lg border-gray-300 text-primary focus:border-primary focus:ring-primary" />
                        @error('postal_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-primary mb-1 uppercase tracking-tight">Země</label>
                        <input type="text" wire:model.defer="country" class="w-full rounded-lg border-gray-300 text-primary focus:border-primary focus:ring-primary" />
                        @error('country') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-12 flex justify-between">
                    <button wire:click="prevStep" class="px-8 py-3 border-2 border-primary text-primary font-bold rounded-lg hover:bg-gray-50 transition">Zpět</button>
                    <button wire:click="nextStep" class="px-8 py-3 bg-primary text-white font-bold rounded-lg hover:opacity-90 transition">Další krok</button>
                </div>
            </div>

        @elseif ($step === 3)
            <div class="max-w-2xl mx-auto">
                <h3 class="text-2xl font-bold text-primary mb-8 text-center">Rekapitulace rezervace</h3>
                
                <div class="space-y-6 bg-gray-50 p-8 rounded-xl border border-gray-100 text-primary">
                    <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Termín</span>
                            <span class="font-medium">{{ $start_date }} – {{ $end_date }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Host</span>
                            <span class="font-medium">{{ $first_name }} {{ $last_name }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Rozpis ceny</span>
                            <div class="text-sm">
                                <div>Ubytování: {{ number_format($this->nights() * $pricePerNight, 0, ',', ' ') }} Kč</div>
                                @if($this->cleaningApplies())
                                    <div class="text-gray-500 italic">Poplatek za úklid: {{ number_format($cleaningFee, 0, ',', ' ') }} Kč</div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Celková cena</span>
                            <span class="font-bold text-xl">{{ number_format($this->total(), 0, ',', ' ') }} Kč</span>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex justify-between">
                    <button wire:click="prevStep" class="px-8 py-3 border-2 border-primary text-primary font-bold rounded-lg transition">Upravit údaje</button>
                    <button wire:click="confirm" class="px-8 py-3 bg-primary text-white font-bold rounded-lg shadow-lg hover:opacity-90 transition">Potvrdit a zaplatit</button>
                </div>

                @if (session()->has('message'))
                    <div class="mt-8 p-4 bg-green-50 text-green-700 rounded-lg text-center font-bold">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>