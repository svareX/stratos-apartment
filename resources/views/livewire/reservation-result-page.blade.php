<div>
    @if($success)
        <div class="bg-white rounded-2xl border border-border shadow-lg p-10 md:p-14 text-center transform transition-all duration-700 translate-y-0 opacity-100">
            <div class="w-20 h-20 bg-tealL text-tealD rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-navy mb-4">{{ __('Reservation was successful') }}</h2>
            <p class="text-muted text-lg mb-10 max-w-lg mx-auto">{{ __('Thank you for your reservation. We look forward to your visit, we will contact you soon.') }}</p>
            <a href="/" wire:navigate class="btn-pri inline-flex justify-center px-8 py-3 text-base">{{ __('Back to homepage') }}</a>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-border shadow-lg p-10 md:p-14 text-center transform transition-all duration-700 translate-y-0 opacity-100">
            <div class="w-20 h-20 bg-red/10 text-red rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-navy mb-4">{{ __('Session expired') }}</h2>
            <p class="text-muted text-lg mb-10 max-w-lg mx-auto">{{ __('We could not verify your reservation. Please try again or contact us.') }}</p>
            <a href="{{ route('reservation') }}" wire:navigate class="btn-pri inline-flex justify-center px-8 py-3 text-base">{{ __('New reservation') }}</a>
        </div>
    @endif
</div>