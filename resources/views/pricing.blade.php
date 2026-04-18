<x-app-layout>
    <section class="flex flex-col px-8 md:px-14 py-12 md:pt-14 md:pb-12 bg-purpleGhost/50">
        <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-2">{{ __('Pricing') }}</p>
        <h1 class="text-4xl md:text-5xl text-navy font-serif mb-3">{{ __('Transparent pricing for every apartment.') }}</h1>
        <p class="text-sm md:text-base text-muted max-w-3xl">
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

        <section class="flex flex-col px-8 md:px-14 py-10 md:py-12 border-t border-border/70">
            <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-2">{{ __($apartment->address) }}</p>
            <h2 class="text-3xl md:text-4xl text-navy font-serif mb-5">{{ __($apartment->name) }}</h2>

            <div class="max-w-5xl border border-border rounded-2xl bg-white overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] items-center gap-3 px-5 md:px-6 py-4 border-b border-border/80">
                    <p class="text-xs uppercase font-bold text-muted">{{ __('Base price / night') }}</p>
                    <p class="text-lg font-bold text-navy">{{ number_format($basePrice, 0, ',', ' ') }} {{ __('CZK') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] items-center gap-3 px-5 md:px-6 py-4 border-b border-border/80">
                    <p class="text-xs uppercase font-bold text-muted">{{ __('Cleaning fee') }}</p>
                    <p class="text-lg font-bold text-navy">{{ number_format($cleaningFee, 0, ',', ' ') }} {{ __('CZK') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] items-center gap-3 px-5 md:px-6 py-4 border-b border-border/80">
                    <p class="text-xs uppercase font-bold text-muted">{{ __('Short stay rule') }}</p>
                    <p class="text-sm text-navy">{{ __('Up to :days nights includes cleaning fee.', ['days' => $thresholdDays]) }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] items-center gap-3 px-5 md:px-6 py-4 border-b border-border/80">
                    <p class="text-xs uppercase font-bold text-muted">{{ __('Long stay rule') }}</p>
                    <p class="text-sm text-navy">{{ __('From :days nights, cleaning fee is not charged.', ['days' => $thresholdDays + 1]) }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] items-center gap-3 px-5 md:px-6 py-4 border-b border-border/80">
                    <p class="text-xs uppercase font-bold text-muted">{{ __('Effective short-stay price / night') }}</p>
                    <p class="text-sm text-navy">
                        {{ number_format($shortStayAvgPrice, 0, ',', ' ') }} {{ __('CZK') }}
                        <span class="text-muted">({{ __(':days nights total :total CZK incl. cleaning', ['days' => $thresholdDays, 'total' => number_format($shortStayTotal, 0, ',', ' ')]) }})</span>
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] items-center gap-3 px-5 md:px-6 py-4">
                    <p class="text-xs uppercase font-bold text-muted">{{ __('Long-stay example') }}</p>
                    <p class="text-sm text-navy">
                        {{ __(':days nights total :total CZK (no cleaning fee)', ['days' => $longStayDays, 'total' => number_format($longStayTotal, 0, ',', ' ')]) }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('apartments.show', $apartment->slug) }}" class="bg-teal px-5 py-2 rounded-xl w-fit font-bold text-sm teal-shadow duration-300 transition-all hover:-translate-y-1 hover:bg-tealD">
                    {{ __('Explore apartment') }}
                </a>
                <a href="{{ route('reservation', ['apartment' => $apartment->slug]) }}" class="px-5 py-2 rounded-xl border border-border text-navy font-bold text-sm duration-300 transition-all hover:-translate-y-1 hover:border-purple hover:bg-purpleGhost hover:text-purple">
                    {{ __('Book') }}
                </a>
            </div>
        </section>
    @endforeach

    <section class="px-8 md:px-14 pb-12 md:pb-16 pt-24 md:pt-32">
        <livewire:reservation-widget />
    </section>
</x-app-layout>

