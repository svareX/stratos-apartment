<x-app-layout>
    <section
        class="bg-purpleGhost/50 flex flex-col px-8 py-12 md:px-14 md:pt-14 md:pb-12"
    >
        <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase">{{ __('Pricing') }}</p>
        <h1 class="text-navy mb-3 font-serif text-4xl md:text-5xl">
            {{ __('Transparent pricing for every apartment.') }}
        </h1>
        <p class="text-muted max-w-3xl text-sm md:text-base">
            {{ __('Prices are loaded directly from apartment settings. Short stays include cleaning, longer stays are charged without cleaning fee.') }}
        </p>
    </section>

    @foreach ($apartments as $apartment)
        @php
            $basePrice = (float) ($apartment->base_price ?? 0);
            $cleaningFee = (float) ($apartment->cleaning_fee ?? 0);
            $thresholdDays = max(1, (int) ($apartment->days_for_cleaning_fee ?? 1));

            $shortStayAvgPrice = $basePrice + ($cleaningFee / $thresholdDays);
            $shortStayTotal = ($basePrice * $thresholdDays) + $cleaningFee;
            $longStayDays = $thresholdDays + 2;
            $longStayTotal = $basePrice * $longStayDays;
        @endphp
        <section
            class="border-border/70 flex flex-col border-t px-8 py-10 md:px-14 md:py-12"
        >
            <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase">{{ __($apartment->address) }}</p>
            <h2 class="text-navy mb-5 font-serif text-3xl md:text-4xl">
                {{ $apartment->name }}
            </h2>

            <div
                class="border-border max-w-5xl overflow-hidden rounded-2xl border bg-white transition-all duration-300 hover:shadow-md"
            >
                <div
                    class="border-border/80 grid grid-cols-1 items-center gap-3 border-b px-5 py-4 md:grid-cols-[280px_1fr] md:px-6"
                >
                    <p class="text-muted text-xs font-bold uppercase">{{ __('Base price / night') }}</p>
                    <p class="text-navy text-lg font-bold">{{ number_format($basePrice, 0, ',', ' ') }} {{ __('CZK') }}</p>
                </div>

                <div
                    class="border-border/80 grid grid-cols-1 items-center gap-3 border-b px-5 py-4 md:grid-cols-[280px_1fr] md:px-6"
                >
                    <p class="text-muted text-xs font-bold uppercase">{{ __('Cleaning fee') }}</p>
                    <p class="text-navy text-lg font-bold">{{ number_format($cleaningFee, 0, ',', ' ') }} {{ __('CZK') }}</p>
                </div>

                <div
                    class="border-border/80 grid grid-cols-1 items-center gap-3 border-b px-5 py-4 md:grid-cols-[280px_1fr] md:px-6"
                >
                    <p class="text-muted text-xs font-bold uppercase">{{ __('Short stay rule') }}</p>
                    <p class="text-navy text-sm">{{ __('Up to :days nights includes cleaning fee.', ['days' => $thresholdDays]) }}</p>
                </div>

                <div
                    class="border-border/80 grid grid-cols-1 items-center gap-3 border-b px-5 py-4 md:grid-cols-[280px_1fr] md:px-6"
                >
                    <p class="text-muted text-xs font-bold uppercase">{{ __('Long stay rule') }}</p>
                    <p class="text-navy text-sm">{{ __('From :days nights, cleaning fee is not charged.', ['days' => $thresholdDays + 1]) }}</p>
                </div>

                <div
                    class="border-border/80 grid grid-cols-1 items-center gap-3 border-b px-5 py-4 md:grid-cols-[280px_1fr] md:px-6"
                >
                    <p class="text-muted text-xs font-bold uppercase">{{ __('Effective short-stay price / night') }}</p>
                    <p class="text-navy text-sm">
                        {{ number_format($shortStayAvgPrice, 0, ',', ' ') }} {{ __('CZK') }}
                        <span class="text-muted"
                            >({{ __(':days nights total :total CZK incl. cleaning', ['days' => $thresholdDays, 'total' => number_format($shortStayTotal, 0, ',', ' ')]) }})</span
                        >
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 items-center gap-3 px-5 py-4 md:grid-cols-[280px_1fr] md:px-6"
                >
                    <p class="text-muted text-xs font-bold uppercase">{{ __('Long-stay example') }}</p>
                    <p class="text-navy text-sm">
                        {{ __(':days nights total :total CZK (no cleaning fee)', ['days' => $longStayDays, 'total' => number_format($longStayTotal, 0, ',', ' ')]) }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a
                    href="{{ route('apartments.show', $apartment->slug) }}"
                    class="bg-teal teal-shadow hover:bg-tealD w-fit rounded-xl px-5 py-2 text-sm font-bold transition-all duration-300 hover:-translate-y-1"
                >
                    {{ __('Explore apartment') }}
                </a>
                <a
                    href="{{ route('reservation', ['locale' => app()->getLocale(), 'apartment' => $apartment->slug]) }}"
                    class="border-border text-navy hover:border-purple hover:bg-purpleGhost hover:text-purple rounded-xl border px-5 py-2 text-sm font-bold transition-all duration-300 hover:-translate-y-1"
                >
                    {{ __('Book') }}
                </a>
            </div>
        </section>
    @endforeach

    <section class="px-8 pt-24 pb-12 md:px-14 md:pt-32 md:pb-16">
        <livewire:reservation-widget />
    </section>
</x-app-layout>
