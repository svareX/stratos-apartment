@component ('layouts.app')
    <div
        class="flex min-h-[60vh] w-full flex-col pt-2 sm:pt-6"
        x-data
        x-init="
            $nextTick(() => {
                if (window.location.hash) {
                    const el = document.querySelector(window.location.hash);
                    if (el) {
                        const y =
                            el.getBoundingClientRect().top +
                            window.scrollY -
                            80;
                        window.scrollTo({ top: y, behavior: 'smooth' });
                    }
                }
            })
        "
    >
        <section
            class="flex flex-col px-8 py-10 md:px-14 md:py-16"
            id="unavailable"
        >
            <div class="max-w-4xl">
                <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase md:mb-4">{{ __('Notice') }}</p>
                <h6
                    class="text-navy mb-4 font-serif text-3xl md:mb-6 md:text-4xl"
                >
                    {{ $apartment?->name ?? __('Apartment temporarily unavailable') }}
                </h6>

                <p class="text-muted mb-8 text-base leading-relaxed md:text-lg">
                    {{ __('This apartment is temporarily unavailable for booking. Please check back later or contact us for more information.') }}
                </p>

                <div class="flex items-center gap-4">
                    <a
                        href="{{ route('home', ['locale' => app()->getLocale()]) }}"
                        class="bg-teal teal-shadow hover:bg-tealD w-fit rounded-xl px-5 py-2 text-sm font-bold text-white transition-all duration-300 hover:-translate-y-1"
                        >{{ __('Back to home') }}</a
                    >
                    <a
                        href="{{ route('contact', ['locale' => app()->getLocale()]) }}"
                        class="text-navy underline hover:no-underline"
                        >{{ __('Contact us') }}</a
                    >
                </div>

                <div class="prose prose-navy mt-10 max-w-none">
                    <p class="text-muted">{{ __('If you believe this is an error or want to request reactivation, please reach out and we will help.') }}</p>
                </div>
            </div>
        </section>
    </div>
@endcomponent
