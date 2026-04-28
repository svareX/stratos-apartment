<div class="mx-auto max-w-5xl p-6">
    <div class="mb-8 text-center">
        <h2 class="text-navy text-4xl font-bold">{{ __('Reservation') }}</h2>
        <p class="text-muted mt-2">{{ __('Book your stay — choose dates and enter your details') }}</p>
    </div>

    <div
        class="border-border overflow-hidden rounded-2xl border bg-white shadow-sm"
    >
        @livewire ('reservation-form')
    </div>
</div>
