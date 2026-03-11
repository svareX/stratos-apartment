<x-app-layout>
    <div class="flex flex-col justify-start gap-lg">
        <h2 class="font-bold text-custom-xl">Ceník</h2>
        
        <div class="flex justify-between px-4">
            <h4 class="font-light text-custom-md tracking-low w-[860px]">
                <span class="font-medium italic">Kratší pobyty</span> jsou ty, trvající celkem <span class="font-medium italic">3 a méně nocí, od 4 a více nocí</span> se jedná o <span class="font-medium italic">pobyty delší</span>.
            </h4>

            <div class="grid grid-cols-3 gap-4 font-light text-[20px] leading-[80px] tracking-low text-center mx-auto">
                <div></div>
                <div>
                    <span>cena apartmánu/noc</span>
                </div>
                <div>
                    <span>poplatek za úklid</span>
                </div>

                <div>kratší pobyty</div>
                <div>
                    <span class="font-semibold">od 1 790 Kč</span>
                </div>
                <div>
                    <span class="font-semibold">600 Kč</span>
                </div>

                <div>delší pobyty</div>
                <div>
                    <span class="font-semibold">od 1 790 Kč</span>
                </div>
                <div>
                    <span class="font-semibold">-</span>
                </div>                
            </div>
        </div>

        <h2 class="font-bold text-custom-xl">Obsazenost</h2>

        <div class="flex justify-between px-4">
            <div class="flex flex-col">
                <h6 class="font-light text-custom-md tracking-low w-[860px]">
                    Na nadcházející sezónu máme pořád <span class="font-medium italic">volné termíny</span>, neváhejte se nám ozvat a domluvit svůj pobyt.
                </h6>
                <h5 class="font-medium italic text-custom-md tracking-low">
                    Těšíme se na vás!
                </h5>
            </div>

            <div class="w-[920px]">
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
</x-app-layout>