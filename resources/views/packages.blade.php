<x-app-layout>
    <section
        class="bg-purpleGhost/50 flex flex-col px-8 py-12 md:px-14 md:pt-14 md:pb-12"
    >
        <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase">{{ __('Vacation packages') }}</p>
        <h1 class="text-navy mb-3 font-serif text-4xl md:text-5xl">
            {{ __('Pick a stay with extras included.') }}
        </h1>
        <p class="text-muted max-w-3xl text-sm md:text-base">
            {{ __('All offers are loaded directly from apartment packages.') }}
        </p>
    </section>

    @if (!empty($apartments) && $apartments->count())
        @foreach ($apartments as $apartment)
            <section
                class="border-border/70 flex flex-col border-t px-8 py-10 md:px-14 md:py-12"
            >
                <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase">{{ __($apartment->address) }}</p>
                <h2 class="text-navy mb-2 font-serif text-3xl md:text-4xl">
                    {{ $apartment->name }}
                </h2>
                <p class="text-muted mb-6 text-sm">{{ __('Special offers available for this apartment.') }}</p>

                @if ($apartment->packages->isNotEmpty())
                    @if ($apartment->packages->count() > 3)
                        <div
                            class="[&::-webkit-scrollbar]:h-2 flex touch-pan-x snap-x snap-mandatory gap-5 overflow-x-auto pb-2 [scrollbar-width:thin]"
                        >
                            @foreach ($apartment->packages as $package)
                                <div
                                    class="border-border flex min-w-[280px] snap-start flex-col rounded-2xl border bg-white p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg md:min-w-[330px]"
                                >
                                    <span
                                        class="text-3xl"
                                        >{{ $package->icon }}</span
                                    >
                                    <p class="text-navy mt-2 text-lg font-bold">{{ $package->name }}</p>

                                    <div
                                        class="text-muted mt-3 space-y-1 text-sm"
                                    >
                                        @foreach ($package->translated_features as $feature)
                                            <p>
                                                <span>-</span>
                                                <span>{{ $feature }}</span>
                                            </p>
                                        @endforeach
                                    </div>

                                    <p class="text-tealD mt-4 text-xs font-bold">
                                        {{ __('from') }} {{ number_format($package->price, 0, ',', ' ') }} {{ __('CZK per night') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div
                            class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3"
                        >
                            @foreach ($apartment->packages as $package)
                                <div
                                    class="border-border flex flex-col rounded-2xl border bg-white p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                                >
                                    <span
                                        class="text-3xl"
                                        >{{ $package->icon }}</span
                                    >
                                    <p class="text-navy mt-2 text-lg font-bold">{{ $package->name }}</p>

                                    <div
                                        class="text-muted mt-3 space-y-1 text-sm"
                                    >
                                        @foreach ($package->translated_features as $feature)
                                            <p>
                                                <span>-</span>
                                                <span>{{ $feature }}</span>
                                            </p>
                                        @endforeach
                                    </div>

                                    <p class="text-tealD mt-4 text-xs font-bold">
                                        {{ __('from') }} {{ number_format($package->price, 0, ',', ' ') }} {{ __('CZK per night') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <p class="text-muted text-sm">{{ __('No packages are available for this apartment yet.') }}</p>
                @endif

                <div class="mt-6">
                    <a
                        href="{{ route('apartments.show', $apartment->slug) }}"
                        class="bg-teal teal-shadow hover:bg-tealD w-fit rounded-xl px-5 py-2 text-sm font-bold transition-all duration-300 hover:-translate-y-1"
                    >
                        {{ __('Explore apartment') }}
                    </a>
                </div>
            </section>
        @endforeach
    @endif
</x-app-layout>
