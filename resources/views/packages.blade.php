<x-app-layout>
    <section class="flex flex-col px-8 md:px-14 py-12 md:pt-14 md:pb-12 bg-purpleGhost/50">
        <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-2">{{ __('Vacation packages') }}</p>
        <h1 class="text-4xl md:text-5xl text-navy font-serif mb-3">{{ __('Pick a stay with extras included.') }}</h1>
        <p class="text-sm md:text-base text-muted max-w-3xl">
            {{ __('All offers are loaded directly from apartment packages.') }}
        </p>
    </section>

    @foreach ($apartments as $apartment)
        <section class="flex flex-col px-8 md:px-14 py-10 md:py-12 border-t border-border/70">
            <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-2">{{ __($apartment->address) }}</p>
            <h2 class="text-3xl md:text-4xl text-navy font-serif mb-2">{{ __($apartment->name) }}</h2>
            <p class="text-sm text-muted mb-6">{{ __('Special offers available for this apartment.') }}</p>

            @if ($apartment->packages->isNotEmpty())
                @if ($apartment->packages->count() > 3)
                    <div class="flex overflow-x-auto gap-5 pb-2 snap-x snap-mandatory touch-pan-x [&::-webkit-scrollbar]:h-2 [scrollbar-width:thin]">
                        @foreach ($apartment->packages as $package)
                            <div class="min-w-[280px] md:min-w-[330px] snap-start flex flex-col border border-border rounded-2xl bg-white p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                                <span class="text-3xl">{{ $package->icon }}</span>
                                <p class="text-navy text-lg font-bold mt-2">{{ $package->name }}</p>

                                <div class="text-muted text-sm mt-3 space-y-1">
                                    @foreach ($package->translated_features as $feature)
                                        <p>
                                            <span>-</span>
                                            <span>{{ $feature }}</span>
                                        </p>
                                    @endforeach
                                </div>

                                <p class="text-xs font-bold text-tealD mt-4">
                                    {{ __('from') }} {{ number_format($package->price, 0, ',', ' ') }} {{ __('CZK per night') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($apartment->packages as $package)
                            <div class="flex flex-col border border-border rounded-2xl bg-white p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                                <span class="text-3xl">{{ $package->icon }}</span>
                                <p class="text-navy text-lg font-bold mt-2">{{ $package->name }}</p>

                                <div class="text-muted text-sm mt-3 space-y-1">
                                    @foreach ($package->translated_features as $feature)
                                        <p>
                                            <span>-</span>
                                            <span>{{ $feature }}</span>
                                        </p>
                                    @endforeach
                                </div>

                                <p class="text-xs font-bold text-tealD mt-4">
                                    {{ __('from') }} {{ number_format($package->price, 0, ',', ' ') }} {{ __('CZK per night') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <p class="text-sm text-muted">{{ __('No packages are available for this apartment yet.') }}</p>
            @endif

            <div class="mt-6">
                <a href="{{ route('apartments.show', $apartment->slug) }}" class="bg-teal px-5 py-2 rounded-xl w-fit font-bold text-sm teal-shadow duration-300 transition-all hover:-translate-y-1 hover:bg-tealD">
                    {{ __('Explore apartment') }}
                </a>
            </div>
        </section>
    @endforeach
</x-app-layout>

