<x-app-layout>
    <div class="flex flex-col justify-start gap-lg">
        <div x-data="{ shown: false }" x-intersect.once="shown = true" class="flex flex-col gap-lg">
            <h2 x-cloak x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="font-bold text-custom-xl">{{ __('Pricing') }}</h2>
            
            <div x-cloak x-show="shown" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="flex justify-between px-4">
                <h4 class="font-light text-custom-md tracking-low w-[860px]">
                    <span class="font-medium italic">{{ __('Shorter stays') }}</span> {{ __('are those lasting a total of') }} <span class="font-medium italic">{{ __('3 nights or less, from 4 nights or more') }}</span> {{ __('they are considered') }} <span class="font-medium italic">{{ __('longer stays') }}</span>.
                </h4>

                <div class="grid grid-cols-3 gap-4 font-light text-[20px] leading-[80px] tracking-low text-center mx-auto">
                    <div></div>
                    <div>
                        <span>{{ __('apartment price/night') }}</span>
                    </div>
                    <div>
                        <span>{{ __('cleaning fee') }}</span>
                    </div>

                    <div>{{ __('shorter stays') }}</div>
                    <div>
                        <span class="font-semibold">{{ __('from 1 790 CZK') }}</span>
                    </div>
                    <div>
                        <span class="font-semibold">{{ __('600 CZK') }}</span>
                    </div>

                    <div>{{ __('longer stays') }}</div>
                    <div>
                        <span class="font-semibold">{{ __('from 1 790 CZK') }}</span>
                    </div>
                    <div>
                        <span class="font-semibold">-</span>
                    </div>                
                </div>
            </div>
        </div>

        <div x-data="{ shown: false }" x-intersect.once.margin.-100px="shown = true" class="flex flex-col gap-lg mt-12">
            <h2 x-cloak x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="font-bold text-custom-xl">{{ __('Occupancy') }}</h2>

            <div class="flex justify-between px-4">
                <div x-cloak x-show="shown" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 -translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" class="flex flex-col">
                    <h6 class="font-light text-custom-md tracking-low w-[860px]">
                        {{ __('We still have') }} <span class="font-medium italic">{{ __('available dates') }}</span> {{ __('for the upcoming season, don\'t hesitate to contact us and arrange your stay.') }}
                    </h6>
                    <h5 class="font-medium italic text-custom-md tracking-low">
                        {{ __('We look forward to seeing you!') }}
                    </h5>
                    
                    <div x-cloak :class="shown ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-12 scale-90'" style="transition-delay: 600ms;" class="transition-all mt-4 duration-700 ease-out flex justify-center text-center bg-white w-[220px] h-[64px] rounded-[24px] cursor-pointer">
                        <a href="{{ route('reservation') }}" class="w-full h-full flex items-center justify-center">
                            <span class="text-primary font-light text-[36px] tracking-low hover:underline">
                                {{ __('reserve') }}
                            </span>
                        </a>
                    </div>
                </div>

                <div x-cloak x-show="shown" x-transition:enter="transition ease-out duration-700 delay-400" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" class="w-[920px]">
                    <div class="calendar bg-white text-primary p-4 rounded-lg shadow-sm">
                        <div class="calendar-header">December 2026</div>

                        <div class="calendar-weekdays">
                            <div class="weekday">Mon</div>
                            <div class="weekday">Tue</div>
                            <div class="weekday">Wed</div>
                            <div class="weekday">Thu</div>
                            <div class="weekday">Fri</div>
                            <div class="weekday">Sat</div>
                            <div class="weekday">Sun</div>
                        </div>

                        <div class="calendar-grid">
                            @php
                                $booked = [5,6,10,11,12,18,19,20,24,25,26,31];
                            @endphp

                            @for ($d = 1; $d <= 31; $d++)
                                <div class="calendar-cell @if(in_array($d, $booked)) booked @endif">
                                    <div class="date">{{ $d }}</div>
                                </div>
                            @endfor
                        </div>

                        <div class="calendar-legend">
                            <span class="flex items-center gap-2"><span class="dot" style="background:var(--color-primary);"></span> Booked</span>
                            <span class="flex items-center gap-2"><span class="dot" style="background:#e6e7eb;"></span> Available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>