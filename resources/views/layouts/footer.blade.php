<footer class="bg-navy text-white">
    <div class="mx-auto max-w-7xl px-8 pt-14 pb-8 md:px-12">
        <div
            class="mb-3 grid grid-cols-2 gap-4 gap-y-6 border-b border-white/10 pb-6 md:mb-6 md:grid-cols-5 md:gap-8 md:pb-10"
        >
            <div class="col-span-1 md:col-span-2">
                <div class="mb-4 flex items-center gap-3">
                    <img
                        src="{{ asset('images/logo/icon.png') }}"
                        alt="{{ __('Apartment Stratos') }}"
                        class="h-9 w-9 rounded-full object-cover"
                    />
                    <div class="font-bold md:text-lg">
                        {{ __('Apartment Stratos') }}
                    </div>
                </div>
                <p class="max-w-md text-[14px] text-[rgba(255,255,255,0.45)]">
                    {!! __('Jeseníky nebo Laa.<br>Obojí je lepší než pondělí.') !!}
                </p>
                <div class="mt-2 flex items-center gap-4">
                    <a
                        href="https://www.facebook.com/apartmanstratos"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-[rgba(255,255,255,0.45)] transition-colors duration-300 hover:text-white"
                    >
                        <svg fill="currentColor" viewBox="0 0 24 24" class="h-6 w-6"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" /></svg>
                    </a>
                    <a
                        href="https://www.instagram.com/apartmanstratos/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-[rgba(255,255,255,0.45)] transition-colors duration-300 hover:text-white"
                    >
                        <svg fill="currentColor" viewBox="0 0 24 24" class="h-6 w-6"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" /></svg>
                    </a>
                </div>
            </div>

            <div class="text-xxs">
                <h4
                    class="mb-2 font-bold tracking-[8%] text-[rgba(255,255,255,0.3)] uppercase md:mb-4"
                >
                    {{ __('Apartments') }}
                </h4>
                <ul class="space-y-1">
                    @php
                        $apartments = \App\Models\Apartment::where('active', true)->get();
                    @endphp
                    @foreach ($apartments as $apartment)
                        <li>
                            <a
                                href="{{ route('apartments.show', $apartment->slug) }}"
                                class="text-white/70 hover:text-white"
                                >{{ $apartment->name }}</a
                            >
                        </li>
                    @endforeach
                    <li>
                        <a
                            href="{{ route('home').'#gallery' }}"
                            class="text-white/70 hover:text-white"
                            >{{ __('Gallery') }}</a
                        >
                    </li>
                </ul>
            </div>

            <div class="text-xxs">
                <h4
                    class="mb-2 font-bold tracking-[8%] text-[rgba(255,255,255,0.3)] uppercase md:mb-4"
                >
                    {{ __('Stay') }}
                </h4>
                <ul class="space-y-1">
                    <li>
                        <a
                            href="{{ route('packages') }}"
                            class="text-white/70 hover:text-white"
                            >{{ __('Special offers') }}</a
                        >
                    </li>
                    <li>
                        <a
                            href="{{ route('pricing') }}"
                            class="text-white/70 hover:text-white"
                            >{{ __('Pricing') }}</a
                        >
                    </li>
                    <li>
                        <a
                            href="{{ route('activities') }}"
                            class="text-white/70 hover:text-white"
                            >{{ __('Activities and tips') }}</a
                        >
                    </li>
                </ul>
            </div>

            <div class="text-xxs">
                <h4
                    class="mb-2 font-bold tracking-[8%] text-[rgba(255,255,255,0.3)] uppercase md:mb-4"
                >
                    {{ __('Information') }}
                </h4>
                <ul class="space-y-1">
                    <li>
                        <a
                            href="{{ route('contact') }}"
                            class="text-white/70 hover:text-white"
                            >{{ __('Contact') }}</a
                        >
                    </li>
                    <li>
                        <a
                            href="{{ route('contact').'#faq' }}"
                            class="text-white/70 hover:text-white"
                            >{{ __('FAQs') }}</a
                        >
                    </li>
                    <li>
                        <a
                            href="{{ route('terms') }}"
                            class="text-white/70 hover:text-white"
                            >{{ __('Terms and Conditions') }}</a
                        >
                    </li>
                </ul>
            </div>
        </div>

        <div
            class="text-xxs flex flex-col items-center justify-between gap-2 text-sm text-[rgba(255,255,255,0.3)] md:flex-row md:gap-4"
        >
            <div>
                © {{ date('Y') }} {{ __('Apartment Stratos') }} —
                apartmanstratos.cz
            </div>
            <div class="flex gap-6">
                <a
                    href="{{ route('terms'). '#privacy' }}"
                    class="hover:text-white"
                    >{{ __('Privacy Policy') }}</a
                >
                <a
                    href="{{ route('cookies') }}"
                    class="hover:text-white"
                    >{{ __('Cookies') }}</a
                >
                <div class="flex items-center gap-1">
                    @php $current = app()->getLocale(); @endphp
                    <a
                        href="{{ route('locale.switch', 'cs') }}"
                        class="{{ $current === 'cs' ? 'opacity-100' : 'opacity-40 hover:opacity-100' }}"
                    >
                        <span class="mr-2 inline-block" aria-hidden>🇨🇿</span>
                    </a>
                    <a
                        href="{{ route('locale.switch', 'de') }}"
                        class="{{ $current === 'de' ? 'opacity-100' : 'opacity-40 hover:opacity-100' }}"
                    >
                        <span class="mr-2 inline-block" aria-hidden>🇩🇪</span>
                    </a>
                    <a
                        href="{{ route('locale.switch', 'en') }}"
                        class="{{ $current === 'en' ? 'opacity-100' : 'opacity-40 hover:opacity-100' }}"
                    >
                        <span class="mr-2 inline-block" aria-hidden>🇬🇧</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
