<nav
    x-data="{ mobileMenuOpen: false }"
    class="border-border sticky top-0 z-50 flex h-16 items-center justify-between border-b bg-white px-8 shadow-sm md:px-14"
>
    @php
        $apartments = \App\Models\Apartment::where('active', true)->get();
        $currentRoute = Route::currentRouteName();
        $currentLocale = app()->getLocale();

        $targetSlug = null;
        if ($currentRoute === 'apartments.show') {
            $routeParam = request()->route('apartment');
            $targetSlug = is_object($routeParam) ? $routeParam->slug : $routeParam;
        }
        $targetSlug = $targetSlug ?? $apartments->first()?->slug;

        $hashPrefix = '';
        if ($currentRoute !== 'apartments.show' && $targetSlug) {
            $hashPrefix = route('apartments.show', $targetSlug);
        }
    @endphp

    <a class="relative z-50 flex items-center gap-3" href="{{ route('home') }}">
        <div
            class="bg-purple flex h-9 w-9 items-center justify-center rounded-full text-white"
        >
            S
        </div>
        <div class="text-text text-sm font-bold">
            {{ __('Apartment Stratos') }}
        </div>
    </a>

    <div class="hidden justify-between gap-4 lg:flex">
        @if ($currentRoute === 'home')
            @forelse ($apartments as $apartment )
                <a
                    class="hover:bg-purple text-purple border-border flex justify-center gap-1 rounded-lg border-[1px] bg-white pr-3 pl-2 transition-all duration-300 hover:cursor-pointer hover:text-white"
                    href="{{ route('apartments.show', $apartment->slug) }}"
                >
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full"
                    >
                        @if ($apartment->type === \App\Enums\ApartmentType::Mountains)
                            <svg width="18" height="18" viewBox="0 0 48 48" fill="none"><path d="M8 36 L18 16 L24 26 L30 16 L40 36" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        @else
                            <svg width="18" height="18" viewBox="0 0 48 48" fill="none"><path d="M6 20 C10 14,16 14,20 20 C24 26,30 26,34 20 C38 14,42 14,44 18" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" /></svg>
                        @endif
                    </div>
                    <div class="my-auto text-sm font-bold">
                        {{ $apartment->name }}
                    </div>
                </a>
            @empty
                <div
                    class="bg-purple flex justify-center gap-1 rounded-lg pr-3 pl-2"
                >
                    <div
                        class="bg-purple flex h-9 w-9 items-center justify-center rounded-full text-white"
                    >
                        H
                    </div>
                    <div class="my-auto text-sm font-bold text-white">
                        {{ __('Home') }}
                    </div>
                </div>
            @endforelse
        @else
            @php
                $apartmentsActive = \Illuminate\Support\Str::startsWith($currentRoute ?? '', 'apartments');
                $packagesActive = ($currentRoute === 'packages');
                $activitiesActive = ($currentRoute === 'activities');
                $pricingActive = ($currentRoute === 'pricing');
            @endphp
            <div
                class="text-muted flex items-center justify-center gap-4 text-sm tracking-[3%]"
            >
                <div x-data="{ open: false }" class="relative">
                    <button
                        @click="open = !open"
                        @keydown.escape="open = false"
                        @click.away="open = false"
                        type="button"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-300 hover:cursor-pointer {{ $apartmentsActive ? 'font-bold text-purple' : 'hover:text-purple' }}"
                    >
                        <span>{{ __('Apartments') }}</span>
                        <svg
                            class="h-3 w-3 transition-transform"
                            :class="{
                                '-rotate-180': open,
                            }"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.08z" />
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        x-cloak
                        x-transition.origin.top.scale
                        class="border-border absolute left-0 z-50 mt-2 w-56 rounded-lg border bg-white shadow-lg"
                    >
                        <ul class="py-1">
                            @foreach ($apartments as $apartment)
                                <li>
                                    @php
                                        $isActive = url()->current() === route('apartments.show', $apartment->slug)
                                            || ($currentRoute === 'apartments.show' && (
                                                request()->route('apartment') == $apartment->slug
                                                || (is_object(request()->route('apartment')) && optional(request()->route('apartment'))->slug == $apartment->slug)
                                            ));
                                    @endphp
                                    <a
                                        href="{{ route('apartments.show', $apartment->slug) }}"
                                        class="block px-4 py-2 text-sm transition-colors duration-150 {{ $isActive ? 'font-bold text-purple bg-purplePale' : 'text-text hover:bg-purplePale hover:text-purple' }}"
                                        >{{ $apartment->name }}</a
                                    >
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <a
                    href="{{ $currentRoute === 'apartments.show' ? $hashPrefix . '#packages' : route('packages') }}"
                    class="transition-all duration-300 hover:cursor-pointer {{ $packagesActive ? 'font-bold text-purple' : 'hover:text-purple' }}"
                    >{{ __('Packages') }}</a
                >
                <a
                    href="{{ $currentRoute === 'apartments.show' ? $hashPrefix . '#nearby' : route('activities') }}"
                    class="transition-all duration-300 hover:cursor-pointer {{ $activitiesActive ? 'font-bold text-purple' : 'hover:text-purple' }}"
                    >{{ __('Activities') }}</a
                >
                <a
                    href="{{ route('pricing') }}"
                    class="transition-all duration-300 hover:cursor-pointer {{ $pricingActive ? 'font-bold text-purple' : 'hover:text-purple' }}"
                    >{{ __('Pricing') }}</a
                >
                <a
                    href="{{ route('contact') }}"
                    class="transition-colors duration-300 hover:cursor-pointer {{ $currentRoute === 'contact' ? 'font-bold text-purple' : 'hover:text-purple' }}"
                    >{{ __('Contact') }}</a
                >
            </div>
        @endif
    </div>

    <div class="hidden items-center gap-2 lg:flex">
        <a
            href="{{ route('locale.switch', 'cs') }}"
            class="p-1 px-3 rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-purple {{ $currentLocale === 'cs' ? 'bg-purplePale border-[1px] border-border' : 'hover:bg-purplePale' }}"
        >
            <span class="mr-2 inline-block" aria-hidden>🇨🇿</span>{{ __('CZ') }}
        </a>
        <a
            href="{{ route('locale.switch', 'de') }}"
            class="p-1 px-3 rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-purple {{ $currentLocale === 'de' ? 'bg-purplePale border-[1px] border-border' : 'hover:bg-purplePale' }}"
        >
            <span class="mr-2 inline-block" aria-hidden>🇩🇪</span>{{ __('DE') }}
        </a>
        <a
            href="{{ route('locale.switch', 'en') }}"
            class="p-1 px-3 rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-purple {{ $currentLocale === 'en' ? 'bg-purplePale border-[1px] border-border' : 'hover:bg-purplePale' }}"
        >
            <span class="mr-2 inline-block" aria-hidden>🇬🇧</span>{{ __('EN') }}
        </a>
        <a
            href="{{ route('reservation', ['locale' => app()->getLocale()]) }}"
            class="bg-teal teal-shadow hover:bg-tealD rounded-lg px-5 py-2 text-sm font-bold text-white transition-all duration-200"
        >
            {{ __('Book') }}
        </a>
    </div>

    <div class="relative z-50 flex items-center gap-3 lg:hidden">
        <a
            href="{{ route('reservation', ['locale' => app()->getLocale()]) }}"
            class="bg-teal hover:bg-tealD rounded-lg px-4 py-1.5 text-xs font-bold text-white shadow-sm transition-colors"
        >
            {{ __('Book') }}
        </a>
        <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            type="button"
            class="text-navy hover:text-purple p-1 transition-colors focus:outline-none"
        >
            <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <div
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        x-cloak
        class="border-border absolute top-16 left-0 z-40 flex max-h-[calc(100vh-4rem)] w-full flex-col overflow-y-auto border-b bg-white px-8 py-8 shadow-xl lg:hidden"
    >
        <div class="flex flex-col gap-6">
            @if ($currentRoute === 'home')
                <div class="flex flex-col gap-3">
                    <p class="text-muted text-xs font-bold tracking-[8%] uppercase">{{ __('Select Apartment') }}</p>
                    @forelse ($apartments as $apartment)
                        <a
                            class="bg-gray hover:bg-purplePale text-purple border-border flex items-center gap-3 rounded-xl border-[1px] p-3 transition-all duration-300"
                            href="{{ route('apartments.show', $apartment->slug) }}"
                        >
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-sm"
                            >
                                @if ($apartment->type === \App\Enums\ApartmentType::Mountains)
                                    <svg width="20" height="20" viewBox="0 0 48 48" fill="none"><path d="M8 36 L18 16 L24 26 L30 16 L40 36" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                @else
                                    <svg width="20" height="20" viewBox="0 0 48 48" fill="none"><path d="M6 20 C10 14,16 14,20 20 C24 26,30 26,34 20 C38 14,42 14,44 18" stroke="currentColor" stroke-width="3" stroke-linecap="round" /></svg>
                                @endif
                            </div>
                            <div class="my-auto text-sm font-bold">
                                {{ $apartment->name }}
                            </div>
                        </a>
                    @empty
                        <div class="text-muted text-sm">
                            {{ __('No apartments available.') }}
                        </div>
                    @endforelse
                </div>
            @else
                <div class="text-navy flex flex-col gap-5 text-base font-bold">
                    <div>
                        <p class="text-muted mb-3 text-xs font-bold tracking-[8%] uppercase">{{ __('Apartments') }}</p>
                        <div
                            class="border-border flex flex-col gap-3 border-l-2 pl-3"
                        >
                            @foreach ($apartments as $apartment)
                                @php
                                    $isActive = url()->current() === route('apartments.show', $apartment->slug)
                                        || ($currentRoute === 'apartments.show' && (
                                            request()->route('apartment') == $apartment->slug
                                            || (is_object(request()->route('apartment')) && optional(request()->route('apartment'))->slug == $apartment->slug)
                                        ));
                                @endphp
                                <a
                                    href="{{ route('apartments.show', $apartment->slug) }}"
                                    class="{{ $isActive ? 'text-purple' : 'text-muted hover:text-purple' }} transition-colors"
                                    >{{ $apartment->name }}</a
                                >
                            @endforeach
                        </div>
                    </div>

                    <a
                        href="{{ $currentRoute === 'apartments.show' ? $hashPrefix . '#packages' : route('packages') }}"
                        @click="mobileMenuOpen = false"
                        class="transition-colors {{ $packagesActive ? 'text-purple' : 'hover:text-purple' }}"
                        >{{ __('Packages') }}</a
                    >
                    <a
                        href="{{ $currentRoute === 'apartments.show' ? $hashPrefix . '#nearby' : route('activities') }}"
                        @click="mobileMenuOpen = false"
                        class="transition-colors {{ $activitiesActive ? 'text-purple' : 'hover:text-purple' }}"
                        >{{ __('Activities') }}</a
                    >
                    <a
                        href="{{ $hashPrefix }}#reservation"
                        @click="mobileMenuOpen = false"
                        class="transition-colors {{ $pricingActive ? 'text-purple' : 'hover:text-purple' }}"
                        >{{ __('Pricing') }}</a
                    >
                    <a
                        href="{{ route('contact') }}"
                        class="transition-colors {{ $currentRoute === 'contact' ? 'text-purple' : 'hover:text-purple' }}"
                        >{{ __('Contact') }}</a
                    >
                </div>
            @endif

            <div class="border-border mt-2 border-t pt-6">
                <p class="text-muted mb-4 text-xs font-bold tracking-[8%] uppercase">{{ __('Language') }}</p>
                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('locale.switch', 'cs') }}"
                        class="py-2 px-4 rounded-xl text-sm font-bold transition-colors duration-300 text-purple {{ $currentLocale === 'cs' ? 'bg-purplePale border-[1px] border-purple/20' : 'bg-gray border-[1px] border-border hover:bg-purplePale' }}"
                    >
                        <span class="mr-2 inline-block">🇨🇿</span>{{ __('CZ') }}
                    </a>
                    <a
                        href="{{ route('locale.switch', 'de') }}"
                        class="py-2 px-4 rounded-xl text-sm font-bold transition-colors duration-300 text-purple {{ $currentLocale === 'de' ? 'bg-purplePale border-[1px] border-purple/20' : 'bg-gray border-[1px] border-border hover:bg-purplePale' }}"
                    >
                        <span class="mr-2 inline-block">🇩🇪</span>{{ __('DE') }}
                    </a>
                    <a
                        href="{{ route('locale.switch', 'en') }}"
                        class="py-2 px-4 rounded-xl text-sm font-bold transition-colors duration-300 text-purple {{ $currentLocale === 'en' ? 'bg-purplePale border-[1px] border-purple/20' : 'bg-gray border-[1px] border-border hover:bg-purplePale' }}"
                    >
                        <span class="mr-2 inline-block">🇬🇧</span>{{ __('EN') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
