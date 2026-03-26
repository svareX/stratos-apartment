<section class="-mt-40 sm:-mt-32 md:-mt-24 px-8 md:px-14 py-10 relative z-30" x-data="{ open: false }">
    <div class="max-w-[94vw] min-h-[30vh] mx-auto grid grid-cols-5 gap-6 gap-y-1 bg-white border border-border rounded-2xl pt-4 p-6 shadow-lg">
        
        <div class="col-span-5 mt-auto mb-2">
            <span class="text-purple text-xs font-bold uppercase tracking-[8%] inline-block">📅 {{ __('Check availability') }}</span>
        </div>

        <div class="col-span-5 lg:col-span-2">
            <div class="border-[1.5px] border-border rounded-xl overflow-hidden bg-white shadow-sm">
                <div class="bg-red text-white p-3 flex items-center justify-between">
                    <button type="button" wire:click="prevMonth" class="hover:opacity-80 hover:cursor-pointer transition-all pl-2 pr-10">‹</button>
                    <span class="font-bold text-sm uppercase tracking-wide">
                        {{ $monthName }} {{ $displayYear }}
                    </span>
                    <button type="button" wire:click="nextMonth" class="hover:opacity-80 hover:cursor-pointer transition-all pr-2 pl-10">›</button>
                </div>
                <div class="grid grid-cols-7 bg-[#B71C1C] text-[10px] font-bold text-white/70 py-1 text-center">
                    <span>{{ __('Mo') }}</span><span>{{ __('Tu') }}</span><span>{{ __('We') }}</span><span>{{ __('Th') }}</span><span>{{ __('Fr') }}</span><span>{{ __('Sa') }}</span><span>{{ __('Su') }}</span>
                </div>
                <div class="grid grid-cols-7 gap-y-1 p-2">
                    @foreach($calendarDays as $day)
                        @php
                            $isStart = $dateStart === $day['date'];
                            $isEnd = $dateEnd === $day['date'];
                            $isInRange = $dateStart && $dateEnd && $day['date'] > $dateStart && $day['date'] < $dateEnd;
                            $isSelected = $isStart || $isEnd;
                        @endphp
                        <div 
                            wire:click="selectDate('{{ $day['date'] }}')"
                            class="h-10 flex items-center justify-center text-xs font-semibold text-navy transition-all duration-200 relative cursor-pointer
                            {{ !$day['isCurrentMonth'] ? 'opacity-20' : '' }}
                            {{ $isSelected ? 'bg-purple text-white! z-10 shadow-md scale-105' : '' }}
                            {{ $isInRange ? 'bg-purplePale text-purple' : '' }}
                            {{ !$isSelected && !$isInRange ? 'bg-white hover:bg-purpleGhost' : '' }}
                            {{ $isStart ? 'rounded-l-lg' : '' }}
                            {{ $isEnd ? 'rounded-r-lg' : '' }}
                            "
                        >
                            <span class="relative z-20">{{ $day['day'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-span-5 lg:col-span-3 mt-3 lg:mt-0">
            <div class="grid grid-cols-2 gap-4 text-black">
                <div class="col-span-2 flex flex-col p-3 px-5 bg-gray border-[1.5px] border-border rounded-xl duration-300 transition-all hover:border-purple cursor-pointer relative"
                    @click="$refs.locSelect.focus()">
                    <span class="text-xxs text-muted font-bold uppercase mb-1">{{ __('Apartment') }}</span>
                    <div class="flex justify-between items-center">
                        <select x-ref="locSelect" wire:model.live="apartment_id" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-10">
                            @foreach($apartments as $apt)
                                <option value="{{ $apt->id }}">{{ $apt->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-[14px] text-navy font-bold truncate pr-4">
                            {{ $apartments->firstWhere('id', $apartment_id)?->name ?? __('Select') }}
                        </span>
                        <span class="text-purple text-xs">▼</span>
                    </div>
                </div>

                <div class="col-span-2 sm:col-span-1 flex flex-col p-3 px-5 bg-gray border-[1.5px] border-border rounded-xl duration-300 transition-all hover:border-purple cursor-pointer relative"
                     @click="$refs.startInput.showPicker()">
                    <span class="text-xxs text-muted font-bold uppercase mb-1">{{ __('Arrival') }}</span>
                    <div class="flex justify-between items-center">
                        <input type="date" x-ref="startInput" wire:model.live="dateStart" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-10" />
                        <span class="text-[14px] text-navy font-bold">
                            {{ $dateStart ? \Carbon\Carbon::parse($dateStart)->format('d. m. Y') : '---' }}
                        </span>
                        <span class="text-muted">📅</span>
                    </div>
                </div>

                <div class="col-span-2 sm:col-span-1 flex flex-col p-3 px-5 bg-gray border-[1.5px] border-border rounded-xl duration-300 transition-all hover:border-purple cursor-pointer relative"
                     @click="$refs.endInput.showPicker()">
                    <span class="text-xxs text-muted font-bold uppercase mb-1">{{ __('Departure') }}</span>
                    <div class="flex justify-between items-center">
                        <input type="date" x-ref="endInput" wire:model.live="dateEnd" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-10" />
                        <span class="text-[14px] text-navy font-bold">
                            {{ $dateEnd ? \Carbon\Carbon::parse($dateEnd)->format('d. m. Y') : '---' }}
                        </span>
                        @if($this->nights > 0)
                            <span class="text-xxs text-muted">{{ trans_choice('nights_count', $this->nights) }}</span>
                        @endif
                        <span class="text-muted">📅</span>
                    </div>
                </div>

                <div class="col-span-2 flex flex-col p-3 px-5 bg-gray border-[1.5px] border-border rounded-xl duration-300 transition-all hover:border-purple cursor-pointer group"
                     @click="open = true">
                    <span class="text-xxs text-muted font-bold uppercase mb-1">{{ __('Guests') }}</span>
                    <div class="flex justify-between items-center">
                        <span class="text-[14px] text-navy font-bold">
                            {{ trans_choice('adults_count', $adults) }}@if($children > 0), {{ trans_choice('children_count', $children) }}@endif @if($pets) + 🐶 @endif
                        </span>
                        <span class="text-purple group-hover:translate-y-0.5 transition-transform">▼</span>
                    </div>
                </div>

                <div class="col-span-2">
                    <button type="button" wire:click="goToReservation" class="w-full inline-flex justify-center px-4 py-3 rounded-xl bg-teal teal-shadow text-white font-bold duration-300 transition-all hover:bg-tealD hover:cursor-pointer">
                        {{ __('Check availability') }}
                    </button>
                </div>

                <div class="col-span-2 flex">
                    <span class="text-teal border-l-[3px] bg-urgent w-full py-0.5 pl-2 border-teal">⚡ {{ __('Only 3 free weekends in February - dates are filling up fast!') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div x-show="open" x-cloak class="fixed inset-0 z-100 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-navy/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 border border-border" @click.stop>
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-serif text-xl text-navy">{{ __('Guests') }}</h3>
                <button @click="open = false" class="text-muted hover:text-navy text-2xl hover:cursor-pointer">&times;</button>
            </div>
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold text-sm text-navy">{{ __('Adults') }}</p>
                        <p class="text-xs text-muted">{{ __('Age 13+') }}</p>
                    </div>
                    <div class="flex items-center gap-4 text-navy">
                        <button type="button" wire:click="$set('adults', {{ max(1, $adults - 1) }})" class="w-9 h-9 rounded-full border border-border flex items-center justify-center hover:bg-gray duration-300 hover:cursor-pointer">-</button>
                        <span class="font-bold w-4 text-center">{{ $adults }}</span>
                        <button type="button" wire:click="$set('adults', {{ $adults + 1 }})" class="w-9 h-9 rounded-full border border-border flex items-center justify-center hover:bg-gray duration-300 hover:cursor-pointer">+</button>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold text-sm text-navy">{{ __('Children') }}</p>
                        <p class="text-xs text-muted">{{ __('Age 0-12') }}</p>
                    </div>
                    <div class="flex items-center gap-4 text-navy">
                        <button type="button" wire:click="$set('children', {{ max(0, $children - 1) }})" class="w-9 h-9 rounded-full border border-border flex items-center justify-center hover:bg-gray duration-300 hover:cursor-pointer">-</button>
                        <span class="font-bold w-4 text-center">{{ $children }}</span>
                        <button type="button" wire:click="$set('children', {{ $children + 1 }})" class="w-9 h-9 rounded-full border border-border flex items-center justify-center hover:bg-gray duration-300 hover:cursor-pointer">+</button>
                    </div>
                </div>
                <label class="flex items-center gap-4 p-4 bg-purpleGhost rounded-xl cursor-pointer border border-purplePale mt-4">
                    <flux:checkbox wire:model.live="pets" class="bg-white! border-border!" />
                    <div class="flex flex-col">
                        <span class="font-bold text-sm text-navy">{{ __("I'm bringing a dog") }} 🐶</span>
                        <span class="text-[10px] text-purple font-medium uppercase tracking-wider">{{ __('No extra charge!') }}</span>
                    </div>
                </label>
            </div>
            <button @click="open = false" class="w-full mt-8 py-4 bg-purple text-white rounded-xl font-bold hover:bg-purpleMid shadow-lg hover:cursor-pointer">
                {{ __('Confirm selection') }}
            </button>
        </div>
    </div>
</section>