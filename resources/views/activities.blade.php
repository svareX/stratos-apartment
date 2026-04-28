<x-app-layout>
    <section
        class="bg-purpleGhost/50 flex flex-col px-8 py-12 md:px-14 md:pt-14 md:pb-12"
    >
        <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase">{{ __('Activities and tips') }}</p>
        <h1 class="text-navy mb-3 font-serif text-4xl md:text-5xl">
            {{ __('Hikes and places worth your time.') }}
        </h1>
        <p class="text-muted max-w-3xl text-sm md:text-base">
            {{ __('For each apartment, hikes and nearby places are shown in separate blocks below each other.') }}
        </p>
    </section>

    @foreach ($apartments as $apartment)
        <section
            class="border-border/70 flex flex-col border-t px-8 py-10 md:px-14 md:py-12"
        >
            <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase">{{ __($apartment->address) }}</p>
            <h2 class="text-navy mb-2 font-serif text-3xl md:text-4xl">
                {{ $apartment->name }}
            </h2>

            <div class="mt-6">
                <p class="text-teal mb-3 text-xs font-bold tracking-[8%] uppercase">{{ __('Trails and skiing') }}</p>

                @if ($apartment->hikes->isNotEmpty())
                    <div
                        class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3"
                    >
                        @foreach ($apartment->hikes as $hike)
                            <div
                                class="border-border flex flex-col rounded-2xl border bg-white p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                            >
                                @if ($hike->difficulty)
                                    <div
                                        class="flex gap-x-1 py-[2.5px] mb-3 px-3 w-fit rounded-2xl {{ $hike->difficulty->bgColor() }}"
                                    >
                                        <span
                                            class="text-xxs"
                                            >{{ $hike->difficulty->icon() }}</span
                                        >
                                        <span
                                            class="text-xxs font-bold uppercase {{ $hike->difficulty->textColor() }}"
                                            >{{ $hike->difficulty->label() }}</span
                                        >
                                    </div>
                                @endif

                                <p class="text-navy mb-1 text-sm font-bold">{{ $hike->name }}</p>

                                @if ($hike->description)
                                    <p class="text-muted mb-2 text-xs">{{ $hike->description }}</p>
                                @endif

                                <div
                                    class="text-xxs text-muted flex flex-wrap gap-x-3 gap-y-1 font-semibold"
                                >
                                    <span
                                        >{{ $hike->length }} {{ __('km') }}</span
                                    >
                                    @if ($hike->distance_tx)
                                        <span>{{ $hike->distance_tx }}</span>
                                    @endif
                                    @if ($hike->is_for_families)
                                        <span>{{ __('For families') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-sm">{{ __('No hikes are available for this apartment yet.') }}</p>
                @endif
            </div>

            <div class="mt-10">
                <p class="text-teal mb-3 text-xs font-bold tracking-[8%] uppercase">{{ __('Nearby places') }}</p>

                @if ($apartment->places->isNotEmpty())
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        @foreach ($apartment->places as $place)
                            <{{ $place->url ? 'a' : 'div' }}
                                {!! $place->url ? 'href="' . $place->url . '" target="_blank" rel="noopener noreferrer"' : '' !!}
                                class="border-border hover:border-purple flex overflow-hidden rounded-2xl border bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                            >
                                <div
                                    class="bg-purpleGhost m-4 flex h-20 w-20 shrink-0 items-center justify-center rounded-xl text-3xl"
                                >
                                    {{ $place->icon }}
                                </div>

                                <div
                                    class="flex flex-col justify-center p-4 pl-0"
                                >
                                    <p class="text-navy mb-1 text-sm font-bold">{{ $place->name }}</p>
                                    <p class="text-muted text-xs">{{ $place->distance_text }}</p>
                                    @if ($place->description)
                                        <p class="text-muted mt-2 text-xs">{{ $place->description }}</p>
                                    @endif
                                </div>
                            </{{ $place->url ? 'a' : 'div' }}>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-sm">{{ __('No places are available for this apartment yet.') }}</p>
                @endif
            </div>

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
</x-app-layout>
