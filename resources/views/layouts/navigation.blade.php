<nav
    class="flex items-center justify-between sticky top-0 z-50 bg-white border-b border-border px-8 md:px-14 h-16 shadow-sm">
    <a class="flex items-center gap-3" href="{{ route('home') }}">
        <div class="w-9 h-9 rounded-full bg-purple flex items-center justify-center text-white">S</div>
        <div class="font-bold text-sm text-text">{{ __('Apartment Stratos') }}</div>
    </a>
    <div class="flex justify-between gap-4">
        @php
            $apartments = \App\Models\Apartment::where('active', true)->get();
        @endphp
        @if (Route::currentRouteName() === 'home')
            @forelse ($apartments as $apartment )
                <a class="flex justify-center gap-1 bg-white hover:bg-purple text-purple hover:text-white duration-300 transition-all border-[1px] border-border rounded-lg pl-2 pr-3 hover:cursor-pointer"  href="{{ route('apartments.show', $apartment->slug) }}">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center">
                        @if ($apartment->type === \App\Enums\ApartmentType::Mountains)
                           <svg width="18" height="18" viewBox="0 0 48 48" fill="none"><path d="M8 36 L18 16 L24 26 L30 16 L40 36" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @else
                           <svg width="18" height="18" viewBox="0 0 48 48" fill="none"><path d="M6 20 C10 14,16 14,20 20 C24 26,30 26,34 20 C38 14,42 14,44 18" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"/></svg>
                        @endif
                    </div>
                    <div class="font-bold text-sm my-auto">{{ $apartment->name }}</div>
                </a>
            @empty
                <div class="flex justify-center gap-1 bg-purple rounded-lg pl-2 pr-3">
                    <div class="w-9 h-9 rounded-full bg-purple flex items-center justify-center text-white">H</div>
                    <div class="font-bold text-white text-sm my-auto">{{ __('Home') }}</div>
                </div>
            @endforelse
        @else
            @php
                $currentRoute = Route::currentRouteName();
                $apartmentsActive = \Illuminate\Support\Str::startsWith($currentRoute ?? '', 'apartments');
            @endphp
            <div class="flex justify-center gap-4 text-sm tracking-[3%] text-muted items-center">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @keydown.escape="open = false" type="button"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-300 {{ $apartmentsActive ? 'font-bold text-purple' : 'hover:text-purple' }}">
                        <span>{{ __('Apartments') }}</span>
                        <svg class="w-3 h-3 transition-transform" :class="{'-rotate-180': open}" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.08z" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition.origin.top.scale
                        class="absolute left-0 mt-2 w-56 bg-white border border-border rounded-lg shadow-lg z-50">
                        <ul class="py-1">
                            @foreach($apartments as $apartment)
                                <li>
                                    @php
                                        $isActive = url()->current() === route('apartments.show', $apartment->slug)
                                            || (Route::currentRouteName() === 'apartments.show' && (
                                                request()->route('apartment') == $apartment->slug
                                                || (is_object(request()->route('apartment')) && optional(request()->route('apartment'))->slug == $apartment->slug)
                                            ));
                                    @endphp
                                    <a href="{{ route('apartments.show', $apartment->slug) }}"
                                        class="block px-4 py-2 text-sm transition-colors duration-150 {{ $isActive ? 'font-bold text-purple bg-purplePale' : 'text-text hover:bg-purplePale hover:text-purple' }}">{{ $apartment->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <span class="hover:text-purple transition-all duration-300 hover:cursor-pointer">{{ __('Packages') }}</span>
                <span class="hover:text-purple transition-all duration-300 hover:cursor-pointer">{{ __('Activities') }}</span>
                <span class="hover:text-purple transition-all duration-300 hover:cursor-pointer">{{ __('Pricing') }}</span>
                <span class="hover:text-purple transition-all duration-300 hover:cursor-pointer">{{ __('Contact') }}</span>
            </div>
        @endif
    </div>
    <div class="flex items-center gap-2">
        @php $current = app()->getLocale(); @endphp
        <a href="{{ route('locale.switch', 'cs') }}"
            class="p-1 px-3 rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-purple {{ $current === 'cs' ? 'bg-purplePale border-[1px] border-border' : 'hover:bg-purplePale' }}">
            <span class="inline-block mr-2" aria-hidden>🇨🇿</span>{{ __('CZ') }}
        </a>
        <a href="{{ route('locale.switch', 'de') }}"
            class="p-1 px-3 rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-purple {{ $current === 'de' ? 'bg-purplePale border-[1px] border-border' : 'hover:bg-purplePale' }}">
            <span class="inline-block mr-2" aria-hidden>🇩🇪</span>{{ __('DE') }}
        </a>
        <a href="{{ route('locale.switch', 'en') }}"
            class="p-1 px-3 rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-purple {{ $current === 'en' ? 'bg-purplePale border-[1px] border-border' : 'hover:bg-purplePale' }}">
            <span class="inline-block mr-2" aria-hidden>🇬🇧</span>{{ __('EN') }}
        </a>
        <a href="#"
            class="bg-teal text-white teal-shadow px-5 py-2 rounded-lg font-bold text-sm duration-200 transition-all hover:bg-tealD">
            {{ __('Book') }}
        </a>
    </div>
</nav>
