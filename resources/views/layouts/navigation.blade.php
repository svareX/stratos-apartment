<nav
    class="flex items-center justify-between sticky top-0 z-50 bg-white border-b border-border px-8 md:px-14 h-16 shadow-sm">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-purple flex items-center justify-center text-white">S</div>
        <div class="font-bold text-sm text-text">{{ __('Apartment Stratos') }}</div>
    </div>
    <div class="flex justify-between gap-4">
        @php
            $apartments = \App\Models\Apartment::where('active', true)->get();
        @endphp
        @if (Route::currentRouteName() === 'home')
            @forelse ($apartments as $apartment )
                <a class="flex justify-center gap-1 bg-white hover:bg-purple text-purple hover:text-white duration-300 transition-all border-[1px] border-border rounded-lg pl-2 pr-3 hover:cursor-pointer"  href="{{ route('apartments.show', $apartment) }}">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center">
                        {{-- @if ($apartment->type === \App\Enums\ApartmentType::Mountains)
                           <svg width="18" height="18" viewBox="0 0 48 48" fill="none"><path d="M8 36 L18 16 L24 26 L30 16 L40 36" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @else
                           <svg width="18" height="18" viewBox="0 0 48 48" fill="none"><path d="M6 20 C10 14,16 14,20 20 C24 26,30 26,34 20 C38 14,42 14,44 18" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"/></svg>
                        @endif --}}
                        <svg width="18" height="18" viewBox="0 0 48 48" fill="none"><path d="M6 20 C10 14,16 14,20 20 C24 26,30 26,34 20 C38 14,42 14,44 18" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"/></svg>
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
            <div class="flex justify-center gap-1">
                <span>Apartmány</span>
                <span>Balíčky</span>
                <span>Aktivity</span>
                <span>Ceník</span>
                <span>Kontakt</span>
            </div>
        @endif
    </div>
    <div class="flex items-center gap-2">
        @php $current = app()->getLocale(); @endphp
        <a href="{{ route('locale.switch', 'cs') }}"
            class="p-1 px-3 rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-purple {{ $current === 'cs' ? 'bg-purplePale border-[1px] border-border' : 'hover:bg-purplePale' }}">
            <span class="inline-block mr-2" aria-hidden>🇨🇿</span>CZ
        </a>
        <a href="{{ route('locale.switch', 'de') }}"
            class="p-1 px-3 rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-purple {{ $current === 'de' ? 'bg-purplePale border-[1px] border-border' : 'hover:bg-purplePale' }}">
            <span class="inline-block mr-2" aria-hidden>🇩🇪</span>DE
        </a>
        <a href="{{ route('locale.switch', 'en') }}"
            class="p-1 px-3 rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-purple {{ $current === 'en' ? 'bg-purplePale border-[1px] border-border' : 'hover:bg-purplePale' }}">
            <span class="inline-block mr-2" aria-hidden>🇬🇧</span>EN
        </a>
        <a href="#"
            class="bg-teal text-white teal-shadow px-5 py-2 rounded-lg font-bold text-sm duration-200 transition-all hover:bg-tealD">
            {{ __('Book') }}
        </a>
    </div>
</nav>
