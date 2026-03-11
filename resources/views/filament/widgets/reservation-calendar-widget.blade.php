<x-filament-widgets::widget>
    <x-filament::section>
        
        <style>
            .fc-wrapper { display: flex; flex-direction: column; gap: 1.5rem; width: 100%; }
            .fc-header { display: flex; justify-content: space-between; align-items: center; }
            .fc-title { font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0; line-height: 1.75rem; }
            .dark .fc-title { color: #ffffff; }
            
            /* Month Navigation Controls */
            .fc-controls { display: flex; align-items: center; gap: 1rem; }
            .fc-month-label { font-size: 1.125rem; font-weight: 700; color: #111827; text-transform: uppercase; letter-spacing: 0.05em; text-align: center; min-width: 140px; margin: 0; }
            .dark .fc-month-label { color: #ffffff; }
            .fc-btn { display: flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; border-radius: 9999px; border: none; background: transparent; cursor: pointer; color: #4b5563; transition: all 0.2s ease; outline: none; }
            .dark .fc-btn { color: #9ca3af; }
            .fc-btn:hover { background-color: #f3f4f6; color: #111827; }
            .dark .fc-btn:hover { background-color: #374151; color: #ffffff; }
            .fc-btn svg { width: 1.25rem; height: 1.25rem; }

            /* Grid Layout */
            .fc-grid-wrap { border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; background: #ffffff; }
            .dark .fc-grid-wrap { border-color: #374151; background: #18181b; }
            .fc-weekdays { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
            .dark .fc-weekdays { background: #27272a; border-color: #374151; }
            .fc-weekday { text-align: center; padding: 0.75rem 0; font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; border-right: 1px solid #e5e7eb; }
            .dark .fc-weekday { color: #a1a1aa; border-color: #374151; }
            .fc-weekday:last-child { border-right: none; }
            .fc-days { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 1px; background: #e5e7eb; }
            .dark .fc-days { background: #374151; }
            
            /* Cells */
            .fc-cell-empty { min-height: 100px; background: #f9fafb; }
            .dark .fc-cell-empty { background: rgba(39, 39, 42, 0.5); }
            .fc-cell { min-height: 100px; padding: 0.5rem; display: flex; flex-direction: column; background: #ffffff; transition: background-color 0.2s; }
            .dark .fc-cell { background: #18181b; }
            .fc-cell.is-booked { background-color: rgba(65, 76, 232, 0.05); }
            .dark .fc-cell.is-booked { background-color: rgba(65, 76, 232, 0.15); }
            
            /* Typography & Badges */
            .fc-date-num { font-size: 0.875rem; font-weight: 700; color: #374151; align-self: flex-start; margin-bottom: 0.25rem; }
            .dark .fc-date-num { color: #d4d4d8; }
            .fc-date-num.is-today { color: #414CE8; text-decoration: underline; text-underline-offset: 4px; }
            .dark .fc-date-num.is-today { color: #818cf8; }
            .fc-tag { margin-top: 0.25rem; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 0.375rem; background-color: #414CE8; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); cursor: default; }

            /* Legend */
            .fc-legend { display: flex; gap: 1.5rem; padding-top: 0.5rem; align-items: center; }
            .fc-legend-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #4b5563; }
            .dark .fc-legend-item { color: #9ca3af; }
            .fc-dot { width: 1rem; height: 1rem; border-radius: 0.25rem; flex-shrink: 0; }
            .fc-dot.booked { background-color: #414CE8; }
            .fc-dot.free { background-color: #ffffff; border: 1px solid #d1d5db; }
            .dark .fc-dot.free { background-color: #18181b; border-color: #4b5563; }
        </style>

        <div class="fc-wrapper">
            
            <div class="fc-header">
                <h2 class="fc-title">Přehled rezervací</h2>
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
                    @foreach(['Po', 'Út', 'St', 'Čt', 'Pá', 'So', 'Ne'] as $dayName)
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
                                $reservationData = $isBooked ? $bookedDates[$date][0] : null;
                                $isToday = $date === \Carbon\Carbon::now()->toDateString();
                            @endphp
                            
                            <div class="fc-cell {{ $isBooked ? 'is-booked' : '' }}">
                                <span class="fc-date-num {{ $isToday ? 'is-today' : '' }}">
                                    {{ \Carbon\Carbon::parse($date)->day }}
                                </span>
                                
                                @if($isBooked)
                                    <div class="fc-tag" title="{{ $reservationData['guest_name'] }}">
                                        {{ $reservationData['guest_name'] }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="fc-legend">
                <div class="fc-legend-item">
                    <div class="fc-dot booked"></div>
                    <span>Obsazeno</span>
                </div>
                <div class="fc-legend-item">
                    <div class="fc-dot free"></div>
                    <span>Volno</span>
                </div>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>