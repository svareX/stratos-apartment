<footer class="bg-navy text-white">
    <div class="max-w-7xl mx-auto px-8 md:px-12 pt-14 pb-8">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 gap-y-6 md:gap-8 border-b border-white/10 pb-6 md:pb-10 mb-3 md:mb-6">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-full bg-purple flex items-center justify-center text-white font-bold">S</div>
                    <div class="font-bold md:text-lg">{{ __('Apartment Stratos') }}</div>
                </div>
                <p class="text-[14px] text-[rgba(255,255,255,0.45)] max-w-md">
                    {!! __('Jeseníky nebo Laa.<br>Obojí je lepší než pondělí.') !!}
                </p>
            </div>

            <div class="text-xxs">
                <h4 class="font-bold uppercase text-[rgba(255,255,255,0.3)] tracking-[8%] mb-2 md:mb-4">{{ __('Apartments') }}</h4>
                <ul class="space-y-1">
                    <li><a href="#" class="text-white/70 hover:text-white">{{ __('Ramzová / Jeseníky') }}</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white">{{ __('Laa an der Thaya') }}</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white">{{ __('Gallery') }}</a></li>
                </ul>
            </div>

            <div class="text-xxs">
                <h4 class="font-bold uppercase text-[rgba(255,255,255,0.3)] tracking-[8%] mb-2 md:mb-4">{{ __('Stay') }}</h4>
                <ul class="space-y-1">
                    <li><a href="#" class="text-white/70 hover:text-white">{{ __('Special offers') }}</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white">{{ __('Pricing') }}</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white">{{ __('Activities and tips') }}</a></li>
                </ul>
            </div>

            <div class="text-xxs">
                <h4 class="font-bold uppercase text-[rgba(255,255,255,0.3)] tracking-[8%] mb-2 md:mb-4">{{ __('Information') }}</h4>
                <ul class="space-y-1">
                    <li><a href="#" class="text-white/70 hover:text-white">{{ __('Contact') }}</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white">{{ __('FAQs') }}</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white">{{ __('Terms and Conditions') }}</a></li>
                </ul>
            </div>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-2 md:gap-4 text-sm text-[rgba(255,255,255,0.3)] text-xxs ">
            <div>© {{ date('Y') }} {{ __('Apartment Stratos') }} — apartmanstratos.cz</div>
            <div class="flex gap-6">
                <a href="#" class="hover:text-white">{{ __('Privacy Policy') }}</a>
                <a href="#" class="hover:text-white">{{ __('Cookies') }}</a>
                <div class="flex items-center gap-1">
                    @php $current = app()->getLocale(); @endphp
                    <a href="{{ route('locale.switch', 'cs') }}" class="{{ $current === 'cs' ? 'opacity-100' : 'opacity-40 hover:opacity-100' }}">
                        <span class="inline-block mr-2" aria-hidden>🇨🇿</span>
                    </a>
                    <a href="{{ route('locale.switch', 'de') }}" class="{{ $current === 'de' ? 'opacity-100' : 'opacity-40 hover:opacity-100' }}">
                        <span class="inline-block mr-2" aria-hidden>🇩🇪</span>
                    </a>
                    <a href="{{ route('locale.switch', 'en') }}" class="{{ $current === 'en' ? 'opacity-100' : 'opacity-40 hover:opacity-100' }}">
                        <span class="inline-block mr-2" aria-hidden>🇬🇧</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>