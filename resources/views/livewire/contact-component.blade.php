<div
    class="flex min-h-[80vh] w-full flex-col pt-2 sm:pt-6"
    x-data
    x-init="
        $nextTick(() => {
            if (window.location.hash) {
                const el = document.querySelector(window.location.hash);
                if (el) {
                    const y =
                        el.getBoundingClientRect().top + window.scrollY - 80;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            }
        })
    "
>
    <section class="flex flex-col px-8 py-10 pb-12 md:px-14" id="contact-info">
        <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase md:mb-4">{{ __('Contact') }}</p>
        <h6 class="text-navy mb-8 font-serif text-3xl md:mb-12 md:text-4xl">
            {{ __('Get in touch.') }}
        </h6>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
            <div
                class="border-border hover:border-purple card-shadow flex flex-col rounded-2xl border bg-white p-6 py-8 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg"
            >
                <div
                    class="bg-purplePale text-purple mb-4 flex h-12 w-12 items-center justify-center rounded-xl text-xl"
                >
                    📞
                </div>
                <p class="text-navy my-2 text-lg font-bold">{{ __('Contact details') }}</p>
                <a
                    href="mailto:{{ $settings->email }}"
                    class="text-muted hover:text-purple mb-1 w-fit transition"
                    >{{ $settings->email }}</a
                >
                <a
                    href="tel:{{ str_replace(' ', '', $settings->phone) }}"
                    class="text-muted hover:text-purple mb-4 w-fit font-medium transition"
                    >{{ $settings->phone }}</a
                >
                <div class="border-border mt-auto w-full border-t pt-4">
                    <span class="text-muted text-sm font-medium"
                        >{{ __('ID (IČO):') }} {{ $settings->vat }}</span
                    >
                </div>
            </div>

            <div
                class="border-border hover:border-purple card-shadow flex flex-col rounded-2xl border bg-white p-6 py-8 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg"
            >
                <div
                    class="bg-purplePale text-purple mb-4 flex h-12 w-12 items-center justify-center rounded-xl text-xl"
                >
                    📍
                </div>
                <p class="text-navy my-2 text-lg font-bold">{{ __('Address') }}</p>
                <div class="text-muted mt-auto pt-2 whitespace-pre-line">
                    {{ $settings->address }}
                </div>
            </div>

            <div
                class="border-border hover:border-purple card-shadow flex flex-col rounded-2xl border bg-white p-6 py-8 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg"
            >
                <div
                    class="bg-purplePale text-purple mb-4 flex h-12 w-12 items-center justify-center rounded-xl text-xl"
                >
                    🌍
                </div>
                <p class="text-navy my-2 text-lg font-bold">{{ __('Social Media') }}</p>
                <div class="mt-2 flex w-full flex-col gap-3">
                    @foreach ($settings->socials ?? [] as $social)
                        <a
                            href="{{ $social['url'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="bg-gray border-border hover:bg-purplePale hover:border-purplePale text-navy flex w-fit items-center gap-3 rounded-xl border px-3 py-2 pr-5 transition-all"
                        >
                            <span
                                class="text-purple w-6 text-center font-bold"
                                >{{ $social['platform'] }}</span
                            >
                            {{ $social['name'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section
        class="bg-gray border-border flex flex-col border-t px-8 py-12 md:px-14 md:py-16"
        id="faq"
    >
        <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase md:mb-4">{{ __('FAQ') }}</p>
        <h6 class="text-navy mb-2 font-serif text-3xl md:text-4xl">
            {{ __('Frequently asked questions.') }}
        </h6>
        <p class="text-muted mb-8 max-w-2xl md:mb-10">
            {{ __('Did not find what you were looking for?') }}
            <a
                href="mailto:{{ $settings->email }}"
                class="group text-purple relative ml-1 font-bold hover:underline"
            >
                {{ __('Write to us') }}
                <span
                    class="bg-navy pointer-events-none absolute bottom-full left-1/2 z-50 mb-2 -translate-x-1/2 rounded-lg px-3 py-1.5 text-xs font-medium whitespace-nowrap text-white opacity-0 shadow-lg transition-all duration-200 group-hover:opacity-100"
                >
                    {{ $settings->email }}
                    <span
                        class="border-t-navy absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent"
                    ></span>
                </span>
            </a>
        </p>

        <div
            class="border-border card-shadow w-full max-w-4xl overflow-hidden rounded-2xl border bg-white shadow-sm"
        >
            <flux:accordion transition exclusive>
                @foreach ($faqs as $faq)
                    <flux:accordion.item
                        wire:key="faq-{{ $faq->id }}"
                        class="border-border border-b last:border-0"
                    >
                        <flux:accordion.heading
                            class="text-navy hover:bg-purpleGhost px-6 py-5 font-bold transition-colors"
                        >
                            {{ $faq->question }}
                        </flux:accordion.heading>

                        <flux:accordion.content
                            class="text-muted px-6 py-6 leading-relaxed"
                        >
                            {!! nl2br(e($faq->answer)) !!}
                        </flux:accordion.content>
                    </flux:accordion.item>
                @endforeach
            </flux:accordion>
        </div>
    </section>
</div>
