<section
    class="relative z-30 -mt-40 px-8 py-10 sm:-mt-32 md:-mt-24 md:px-14"
    x-data="{ open: false }"
>
    <div
        class="border-border mx-auto grid min-h-[30vh] max-w-[94vw] grid-cols-5 gap-6 gap-y-1 rounded-2xl border bg-white p-6 pt-4 shadow-lg"
    >
        <div class="col-span-5 mt-auto mb-2">
            <span
                class="text-purple inline-block text-xs font-bold tracking-[8%] uppercase"
                >📅 {{ __('Check availability') }}</span
            >
        </div>

        <div class="col-span-5 lg:col-span-2">
            <div
                class="border-border overflow-hidden rounded-xl border-[1.5px] bg-white shadow-sm"
            >
                <div
                    class="bg-red flex items-center justify-between p-3 text-white"
                >
                    <button
                        type="button"
                        wire:click="prevMonth"
                        class="pr-10 pl-2 transition-all hover:cursor-pointer hover:opacity-80"
                    >
                        ‹
                    </button>
                    <span class="text-sm font-bold tracking-wide uppercase">
                        {{ $monthName }} {{ $displayYear }}
                    </span>
                    <button
                        type="button"
                        wire:click="nextMonth"
                        class="pr-2 pl-10 transition-all hover:cursor-pointer hover:opacity-80"
                    >
                        ›
                    </button>
                </div>
                <div
                    class="grid grid-cols-7 bg-[#B71C1C] py-1 text-center text-[10px] font-bold text-white/70"
                >
                    <span>{{ __('Mo') }}</span><span>{{ __('Tu') }}</span
                    ><span>{{ __('We') }}</span><span>{{ __('Th') }}</span
                    ><span>{{ __('Fr') }}</span><span>{{ __('Sa') }}</span
                    ><span>{{ __('Su') }}</span>
                </div>
                <div class="grid grid-cols-7 gap-y-1 p-2">
                    @foreach ($calendarDays as $day)
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

        <div class="col-span-5 mt-3 lg:col-span-3 lg:mt-0">
            <div class="grid grid-cols-2 gap-4 text-black">
                <div
                    class="bg-gray border-border hover:border-purple relative col-span-2 flex cursor-pointer flex-col rounded-xl border-[1.5px] p-3 px-5 transition-all duration-300"
                    @click="$refs.locSelect.focus()"
                >
                    <span
                        class="text-xxs text-muted mb-1 font-bold uppercase"
                        >{{ __('Apartment') }}</span
                    >
                    <div class="flex items-center justify-between">
                        <select
                            x-ref="locSelect"
                            wire:model.live="apartment_id"
                            class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0"
                        >
                            @foreach ($apartments as $apt)
                                <option value="{{ $apt->id }}">
                                    {{ $apt->name }}
                                </option>
                            @endforeach
                        </select>
                        <span
                            class="text-navy truncate pr-4 text-[14px] font-bold"
                        >
                            {{ $apartments->firstWhere('id', $apartment_id)?->name ?? __('Select') }}
                        </span>
                        <span class="text-purple text-xs">▼</span>
                    </div>
                </div>

                <div
                    class="bg-gray border-border hover:border-purple relative col-span-2 flex cursor-pointer flex-col rounded-xl border-[1.5px] p-3 px-5 transition-all duration-300 sm:col-span-1"
                    @click="$refs.startInput.showPicker()"
                >
                    <span
                        class="text-xxs text-muted mb-1 font-bold uppercase"
                        >{{ __('Arrival') }}</span
                    >
                    <div class="flex items-center justify-between">
                        <input
                            type="date"
                            x-ref="startInput"
                            wire:model.live="dateStart"
                            class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0"
                        />
                        <span class="text-navy text-[14px] font-bold">
                            {{ $dateStart ? \Carbon\Carbon::parse($dateStart)->format('d. m. Y') : '---' }}
                        </span>
                        <span class="text-muted">📅</span>
                    </div>
                </div>

                <div
                    class="bg-gray border-border hover:border-purple relative col-span-2 flex cursor-pointer flex-col rounded-xl border-[1.5px] p-3 px-5 transition-all duration-300 sm:col-span-1"
                    @click="$refs.endInput.showPicker()"
                >
                    <span
                        class="text-xxs text-muted mb-1 font-bold uppercase"
                        >{{ __('Departure') }}</span
                    >
                    <div class="flex items-center justify-between">
                        <input
                            type="date"
                            x-ref="endInput"
                            wire:model.live="dateEnd"
                            class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0"
                        />
                        <span class="text-navy text-[14px] font-bold">
                            {{ $dateEnd ? \Carbon\Carbon::parse($dateEnd)->format('d. m. Y') : '---' }}
                        </span>
                        @if ($this->nights > 0)
                            <span
                                class="text-xxs text-muted"
                                >{{ trans_choice('nights_count', $this->nights) }}</span
                            >
                        @endif
                        <span class="text-muted">📅</span>
                    </div>
                </div>

                <div
                    class="bg-gray border-border hover:border-purple group col-span-2 flex cursor-pointer flex-col rounded-xl border-[1.5px] p-3 px-5 transition-all duration-300"
                    @click="open = true"
                >
                    <span
                        class="text-xxs text-muted mb-1 font-bold uppercase"
                        >{{ __('Guests') }}</span
                    >
                    <div class="flex items-center justify-between">
                        <span class="text-navy text-[14px] font-bold">
                            {{ trans_choice('adults_count', $adults) }}
                            @if ($children > 0) ,{{ trans_choice('children_count', $children) }}@endif
                            @if ($pets) + 🐶 @endif
                        </span>
                        <span
                            class="text-purple transition-transform group-hover:translate-y-0.5"
                            >▼</span
                        >
                    </div>
                </div>

                <div class="col-span-2">
                    <button
                        type="button"
                        wire:click="goToReservation"
                        class="bg-teal teal-shadow hover:bg-tealD inline-flex w-full justify-center rounded-xl px-4 py-3 font-bold text-white transition-all duration-300 hover:cursor-pointer"
                    >
                        {{ __('Check availability') }}
                    </button>
                </div>

                <div class="col-span-2 flex">
                    <span
                        class="text-teal bg-urgent border-teal w-full border-l-[3px] py-0.5 pl-2"
                        >⚡ {{ __('Only 3 free weekends in February - dates are filling up fast!') }}</span
                    >
                </div>
            </div>
        </div>
    </div>

    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-100 flex items-center justify-center px-4"
    >
        <div
            class="bg-navy/60 absolute inset-0 backdrop-blur-sm"
            @click="open = false"
        ></div>
        <div
            class="border-border relative w-full max-w-sm rounded-2xl border bg-white p-6 shadow-2xl"
            @click.stop
        >
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-navy font-serif text-xl">{{ __('Guests') }}</h3>
                <button
                    @click="open = false"
                    class="text-muted hover:text-navy text-2xl hover:cursor-pointer"
                >
                    &times;
                </button>
            </div>
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-navy text-sm font-bold">{{ __('Adults') }}</p>
                        <p class="text-muted text-xs">{{ __('Age 13+') }}</p>
                    </div>
                    <div class="text-navy flex items-center gap-4">
                        <button
                            type="button"
                            wire:click="$set('adults', {{ max(1, $adults - 1) }})"
                            class="border-border hover:bg-gray flex h-9 w-9 items-center justify-center rounded-full border duration-300 hover:cursor-pointer"
                        >
                            -
                        </button>
                        <span
                            class="w-4 text-center font-bold"
                            >{{ $adults }}</span
                        >
                        <button
                            type="button"
                            wire:click="$set('adults', {{ $adults + 1 }})"
                            class="border-border hover:bg-gray flex h-9 w-9 items-center justify-center rounded-full border duration-300 hover:cursor-pointer"
                        >
                            +
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-navy text-sm font-bold">{{ __('Children') }}</p>
                        <p class="text-muted text-xs">{{ __('Age 0-12') }}</p>
                    </div>
                    <div class="text-navy flex items-center gap-4">
                        <button
                            type="button"
                            wire:click="$set('children', {{ max(0, $children - 1) }})"
                            class="border-border hover:bg-gray flex h-9 w-9 items-center justify-center rounded-full border duration-300 hover:cursor-pointer"
                        >
                            -
                        </button>
                        <span
                            class="w-4 text-center font-bold"
                            >{{ $children }}</span
                        >
                        <button
                            type="button"
                            wire:click="$set('children', {{ $children + 1 }})"
                            class="border-border hover:bg-gray flex h-9 w-9 items-center justify-center rounded-full border duration-300 hover:cursor-pointer"
                        >
                            +
                        </button>
                    </div>
                </div>
                <label
                    class="bg-purpleGhost border-purplePale mt-4 flex cursor-pointer items-center gap-4 rounded-xl border p-4"
                >
                    <flux:checkbox
                        wire:model.live="pets"
                        class="border-border! bg-white!"
                    />
                    <div class="flex flex-col">
                        <span class="text-navy text-sm font-bold"
                            >{{ __("I'm bringing a dog") }} 🐶</span
                        >
                        <span
                            class="text-purple text-[10px] font-medium tracking-wider uppercase"
                            >{{ __('No extra charge!') }}</span
                        >
                    </div>
                </label>
            </div>
            <button
                @click="open = false"
                class="bg-purple hover:bg-purpleMid mt-8 w-full rounded-xl py-4 font-bold text-white shadow-lg hover:cursor-pointer"
            >
                {{ __('Confirm selection') }}
            </button>
        </div>
    </div>
</section>
