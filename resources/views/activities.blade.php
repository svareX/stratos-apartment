<x-app-layout>
    <section class="flex flex-col px-8 md:px-14 py-12 md:pt-14 md:pb-12 bg-purpleGhost/50">
        <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-2">{{ __('Activities and tips') }}</p>
        <h1 class="text-4xl md:text-5xl text-navy font-serif mb-3">{{ __('Hikes and places worth your time.') }}</h1>
        <p class="text-sm md:text-base text-muted max-w-3xl">
            {{ __('For each apartment, hikes and nearby places are shown in separate blocks below each other.') }}
        </p>
    </section>

    @foreach ($apartments as $apartment)
        <section class="flex flex-col px-8 md:px-14 py-10 md:py-12 border-t border-border/70">
            <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-2">{{ __($apartment->address) }}</p>
            <h2 class="text-3xl md:text-4xl text-navy font-serif mb-2">{{ __($apartment->name) }}</h2>

            <div class="mt-6">
                <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-3">{{ __('Trails and skiing') }}</p>

                @if ($apartment->hikes->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($apartment->hikes as $hike)
                            <div class="flex flex-col p-5 border border-border rounded-2xl bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                                @if ($hike->difficulty)
                                    <div class="flex gap-x-1 py-[2.5px] mb-3 px-3 w-fit rounded-2xl {{ $hike->difficulty->bgColor() }}">
                                        <span class="text-xxs">{{ $hike->difficulty->icon() }}</span>
                                        <span class="text-xxs font-bold uppercase {{ $hike->difficulty->textColor() }}">{{ $hike->difficulty->label() }}</span>
                                    </div>
                                @endif

                                <p class="text-sm font-bold text-navy mb-1">{{ $hike->name }}</p>

                                @if ($hike->description)
                                    <p class="text-xs text-muted mb-2">{{ $hike->description }}</p>
                                @endif

                                <div class="flex flex-wrap gap-x-3 gap-y-1 text-xxs text-muted font-semibold">
                                    <span>{{ $hike->length }} {{ __('km') }}</span>
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
                    <p class="text-sm text-muted">{{ __('No hikes are available for this apartment yet.') }}</p>
                @endif
            </div>

            <div class="mt-10">
                <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-3">{{ __('Nearby places') }}</p>

                @if ($apartment->places->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($apartment->places as $place)
                            <{{ $place->url ? 'a' : 'div' }}
                                {!! $place->url ? 'href="' . $place->url . '" target="_blank" rel="noopener noreferrer"' : '' !!}
                                class="flex border border-border rounded-2xl bg-white overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-purple">

                                <div class="w-20 h-20 m-4 shrink-0 rounded-xl bg-purpleGhost flex items-center justify-center text-3xl">
                                    {{ $place->icon }}
                                </div>

                                <div class="p-4 pl-0 flex flex-col justify-center">
                                    <p class="text-sm font-bold text-navy mb-1">{{ $place->name }}</p>
                                    <p class="text-xs text-muted">{{ $place->distance_text }}</p>
                                    @if ($place->description)
                                        <p class="text-xs text-muted mt-2">{{ $place->description }}</p>
                                    @endif
                                </div>
                            </{{ $place->url ? 'a' : 'div' }}>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-muted">{{ __('No places are available for this apartment yet.') }}</p>
                @endif
            </div>

            <div class="mt-6">
                <a href="{{ route('apartments.show', $apartment->slug) }}" class="bg-teal px-5 py-2 rounded-xl w-fit font-bold text-sm teal-shadow duration-300 transition-all hover:-translate-y-1 hover:bg-tealD">
                    {{ __('Explore apartment') }}
                </a>
            </div>
        </section>
    @endforeach
</x-app-layout>

