<div>
    @if ($success)
        <div
            class="border-border translate-y-0 transform rounded-2xl border bg-white p-10 text-center opacity-100 shadow-lg transition-all duration-700 md:p-14"
        >
            <div
                class="bg-tealL text-tealD mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full"
            >
                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2
                class="text-navy mb-4 font-serif text-3xl font-bold md:text-4xl"
            >
                {{ __('Reservation was successful') }}
            </h2>
            <p class="text-muted mx-auto mb-10 max-w-lg text-lg">{{ __('Thank you for your reservation. We look forward to your visit, we will contact you soon.') }}</p>
            <a
                href="/"
                wire:navigate
                class="btn-pri inline-flex justify-center px-8 py-3 text-base"
                >{{ __('Back to homepage') }}</a
            >
        </div>
    @else
        <div
            class="border-border translate-y-0 transform rounded-2xl border bg-white p-10 text-center opacity-100 shadow-lg transition-all duration-700 md:p-14"
        >
            <div
                class="bg-red/10 text-red mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full"
            >
                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h2
                class="text-navy mb-4 font-serif text-3xl font-bold md:text-4xl"
            >
                {{ __('Session expired') }}
            </h2>
            <p class="text-muted mx-auto mb-10 max-w-lg text-lg">{{ __('We could not verify your reservation. Please try again or contact us.') }}</p>
            <a
                href="{{ route('reservation', ['locale' => app()->getLocale()]) }}"
                wire:navigate
                class="btn-pri inline-flex justify-center px-8 py-3 text-base"
                >{{ __('New reservation') }}</a
            >
        </div>
    @endif
</div>
