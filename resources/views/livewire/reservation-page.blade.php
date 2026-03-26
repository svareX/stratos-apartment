<div class="max-w-5xl mx-auto p-6">
    <div class="text-center mb-8">
        <h2 class="font-bold text-navy text-4xl">{{ __('Reservation') }}</h2>
        <p class="text-muted mt-2">{{ __('Book your stay — choose dates and enter your details') }}</p>
    </div>

    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden">
        @livewire('reservation-form')
    </div>
</div>
