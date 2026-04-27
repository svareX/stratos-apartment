@component('layouts.app')
<div class="w-full min-h-[60vh] flex flex-col pt-2 sm:pt-6"
    x-data
    x-init="$nextTick(() => {
        if (window.location.hash) {
            const el = document.querySelector(window.location.hash);
            if (el) {
                const y = el.getBoundingClientRect().top + window.scrollY - 80;
                window.scrollTo({top: y, behavior: 'smooth'});
            }
        }
    })">

    <section class="flex flex-col px-8 md:px-14 py-10 md:py-16" id="unavailable">
        <div class="max-w-4xl">
            <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-2 md:mb-4">{{ __('Notice') }}</p>
            <h6 class="text-3xl md:text-4xl text-navy font-serif mb-4 md:mb-6">{{ $apartment?->name ?? __('Apartment temporarily unavailable') }}</h6>

            <p class="text-muted mb-8 text-base md:text-lg leading-relaxed">
                {{ __('This apartment is temporarily unavailable for booking. Please check back later or contact us for more information.') }}
            </p>

            <div class="flex items-center gap-4">
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="bg-teal text-white px-5 py-2 rounded-xl w-fit font-bold text-sm teal-shadow duration-300 transition-all hover:-translate-y-1 hover:bg-tealD">{{ __('Back to home') }}</a>
                <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="text-navy underline hover:no-underline">{{ __('Contact us') }}</a>
            </div>

            <div class="prose prose-navy max-w-none mt-10">
                <p class="text-muted">{{ __('If you believe this is an error or want to request reactivation, please reach out and we will help.') }}</p>
            </div>
        </div>
    </section>

</div>
@endcomponent
