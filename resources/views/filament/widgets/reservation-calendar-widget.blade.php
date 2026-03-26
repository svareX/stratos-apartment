<x-filament-widgets::widget>
    <x-filament::section>
        
        <style>
            .fc-wrapper { display: flex; flex-direction: column; gap: 1.5rem; width: 100%; }
            .fc-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
            .fc-header-left { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
            .fc-title { font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0; line-height: 1.75rem; }
            .dark .fc-title { color: #ffffff; }
            
            .fc-select { padding: 0.375rem 2rem 0.375rem 1rem; border-radius: 0.5rem; border: 1px solid #d1d5db; font-size: 0.875rem; font-weight: 500; background-color: #ffffff; color: #374151; outline: none; cursor: pointer; min-width: 200px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); transition: border-color 0.2s;}
            .dark .fc-select { background-color: #18181b; border-color: #3f3f46; color: #e4e4e7; }
            .fc-select:focus { border-color: #414CE8; }

            .fc-controls { display: flex; align-items: center; gap: 1rem; }
            .fc-month-label { font-size: 1.125rem; font-weight: 700; color: #111827; text-transform: uppercase; letter-spacing: 0.05em; text-align: center; min-width: 140px; margin: 0; }
            .dark .fc-month-label { color: #ffffff; }
            .fc-btn { display: flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; border-radius: 9999px; border: none; background: transparent; cursor: pointer; color: #4b5563; transition: all 0.2s ease; outline: none; }
            .dark .fc-btn { color: #9ca3af; }
            .fc-btn:hover { background-color: #f3f4f6; color: #111827; }
            .dark .fc-btn:hover { background-color: #374151; color: #ffffff; }
            .fc-btn svg { width: 1.25rem; height: 1.25rem; }

            .fc-grid-wrap { border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; background: #ffffff; }
            .dark .fc-grid-wrap { border-color: #374151; background: #18181b; }
            .fc-weekdays { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
            .dark .fc-weekdays { background: #27272a; border-color: #374151; }
            .fc-weekday { text-align: center; padding: 0.75rem 0; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; border-right: 1px solid #e5e7eb; }
            .dark .fc-weekday { color: #a1a1aa; border-color: #374151; }
            .fc-weekday:last-child { border-right: none; }
            .fc-days { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 1px; background: #e5e7eb; }
            .dark .fc-days { background: #374151; }
            
            .fc-cell-empty { min-height: 110px; background: #f9fafb; }
            .dark .fc-cell-empty { background: rgba(39, 39, 42, 0.5); }
            .fc-cell { min-height: 110px; padding: 0.5rem; display: flex; flex-direction: column; background: #ffffff; transition: background-color 0.2s; }
            .dark .fc-cell { background: #18181b; }
            .fc-cell.is-booked { background-color: rgba(65, 76, 232, 0.05); }
            .dark .fc-cell.is-booked { background-color: rgba(65, 76, 232, 0.15); }
            
            .fc-date-num { font-size: 0.875rem; font-weight: 700; color: #374151; align-self: flex-start; margin-bottom: 0.25rem; }
            .dark .fc-date-num { color: #d4d4d8; }
            .fc-date-num.is-today { color: #414CE8; text-decoration: underline; text-underline-offset: 4px; }
            .dark .fc-date-num.is-today { color: #818cf8; }
            
            .fc-tags-container { display: flex; flex-direction: column; gap: 4px; margin-top: 4px; }
            .fc-tag { font-size: 0.7rem; padding: 0.25rem 0.4rem; border-radius: 0.25rem; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); cursor: default; position: relative; }
            .fc-tag-sub { opacity: 0.9; font-size: 0.65rem; font-weight: 400; margin-left: 2px; }

            .fc-legend { display: flex; gap: 1.5rem; padding-top: 0.5rem; align-items: center; flex-wrap: wrap; }
            .fc-legend-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #4b5563; }
            .dark .fc-legend-item { color: #9ca3af; }
            .fc-dot { width: 1rem; height: 1rem; border-radius: 0.25rem; flex-shrink: 0; }
            .fc-dot.free { background-color: #ffffff; border: 1px solid #d1d5db; }
            .dark .fc-dot.free { background-color: #18181b; border-color: #4b5563; }
            
            .fc-tooltip { visibility: hidden; opacity: 0; position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); background-color: #111827; color: #fff; text-align: center; padding: 0.5rem 0.75rem; border-radius: 0.375rem; z-index: 50; transition: opacity 0.2s; white-space: pre-wrap; min-width: max-content; font-size: 0.75rem; font-weight: 400; margin-bottom: 4px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
            .dark .fc-tooltip { background-color: #f3f4f6; color: #111827; }
            .fc-tooltip::after { content: ""; position: absolute; top: 100%; left: 50%; margin-left: -5px; border-width: 5px; border-style: solid; border-color: #111827 transparent transparent transparent; }
            .dark .fc-tooltip::after { border-color: #f3f4f6 transparent transparent transparent; }
            .fc-tag:hover .fc-tooltip { visibility: visible; opacity: 1; }
        </style>

        <div class="fc-wrapper">
            
            <div class="fc-header">
                <div class="fc-header-left">
                    <h2 class="fc-title">{{ __('Reservation overview') }}</h2>
                    <select wire:model.live="apartmentId" class="fc-select">
                        <option value="all">{{ __('All apartments') }}</option>
                        @foreach($apartments as $apt)
                            <option value="{{ $apt->id }}">{{ $apt->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="fc-controls">
                    <button type="button" wire:click="prevMonth" class="fc-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    
                    <h3 class="fc-month-label">
                        {{ \Carbon\Carbon::create($displayYear, $displayMonth, 1)->translatedFormat('F Y') }}
                    </h3>
                    
                    <button type="button" wire:click="nextMonth" class="fc-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="fc-grid-wrap">
                <div class="fc-weekdays">
                    @foreach([__('Mo'), __('Tu'), __('We'), __('Th'), __('Fr'), __('Sa'), __('Su')] as $dayName)
                        <div class="fc-weekday">{{ $dayName }}</div>
                    @endforeach
                </div>
                
                <div class="fc-days">
                    @foreach($calendarCells as $date)
                        @if(!$date)
                            <div class="fc-cell-empty"></div>
                        @else
                            @php
                                $isBooked = isset($bookedDates[$date]);
                                $isToday = $date === \Carbon\Carbon::now()->toDateString();
                            @endphp
                            
                            <div class="fc-cell {{ $isBooked ? 'is-booked' : '' }}">
                                <span class="fc-date-num {{ $isToday ? 'is-today' : '' }}">
                                    {{ \Carbon\Carbon::parse($date)->day }}
                                </span>
                                
                                @if($isBooked)
                                    <div class="fc-tags-container">
                                        @foreach($bookedDates[$date] as $reservationData)
                                            <div class="fc-tag" style="background-color: {{ $reservationData['color'] }};">
                                                {{ $reservationData['guest_name'] }}
                                                @if($apartmentId === 'all')
                                                    <span class="fc-tag-sub">({{ $reservationData['apartment_name'] }})</span>
                                                @endif
                                                <div class="fc-tooltip"><strong>{{ __('Guest') }}:</strong> {{ $reservationData['guest_name'] }}<br><strong>{{ __('Apartment') }}:</strong> {{ $reservationData['apartment_name'] }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="fc-legend">
                @if($apartmentId === 'all')
                    @foreach($apartments as $apt)
                        <div class="fc-legend-item">
                            <div class="fc-dot" style="background-color: {{ $apartmentColors[$apt->id] ?? '#414CE8' }};"></div>
                            <span>{{ $apt->name }}</span>
                        </div>
                    @endforeach
                @else
                    <div class="fc-legend-item">
                        <div class="fc-dot" style="background-color: {{ $apartmentColors[$apartmentId] ?? '#414CE8' }};"></div>
                        <span>{{ $apartments->firstWhere('id', $apartmentId)?->name ?? __('Booked') }}</span>
                    </div>
                @endif
                <div class="fc-legend-item">
                    <div class="fc-dot free"></div>
                    <span>{{ __('Available') }}</span>
                </div>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>