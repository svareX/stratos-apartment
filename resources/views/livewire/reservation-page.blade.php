<div class="max-w-5xl mx-auto px-6 py-12">
    <div class="text-center mb-10">
        <h2 class="font-bold text-custom-xl">{{ __('Reservation') }}</h2>
        <p class="text-muted mt-2">{{ __('Book your stay — choose dates and enter your details') }}</p>
    </div>

    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden">
        {{-- Pass any query params down to the actual form so it can prefill dates --}}
        @livewire('reservation-form', [
            'apartment_id' => request('apartment_id'),
            'start_date' => request('start_date'),
            'end_date' => request('end_date'),
            'adults' => request('adults'),
            'children' => request('children'),
            'pets' => request('pets'),
        ])
    </div>
</div>
