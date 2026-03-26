<div class="w-full min-h-[80vh] flex flex-col pt-2 sm:pt-6">
    
    <section class="flex flex-col px-8 md:px-14 py-10 pb-12" id="contact-info">
        <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-2 md:mb-4">{{ __('Contact') }}</p>
        <h6 class="text-3xl md:text-4xl text-navy font-serif mb-8 md:mb-12">{{ __('Get in touch.') }}</h6>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            
            <div class="flex flex-col p-6 py-8 rounded-2xl bg-white border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1 card-shadow">
                <div class="w-12 h-12 bg-purplePale text-purple rounded-xl flex items-center justify-center text-xl mb-4">
                    📞
                </div>
                <p class="my-2 text-navy font-bold text-lg">{{ __('Contact details') }}</p>
                <a href="mailto:info@apartmanstratos.cz" class="text-muted hover:text-purple transition mb-1 w-fit">info@apartmanstratos.cz</a>
                <a href="tel:+420732558978" class="text-muted hover:text-purple transition font-medium mb-4 w-fit">+420 732 558 978</a>
                <div class="mt-auto pt-4 border-t border-border w-full">
                    <span class="text-sm text-muted font-medium">{{ __('ID (IČO):') }} 21681902</span>
                </div>
            </div>

            <div class="flex flex-col p-6 py-8 rounded-2xl bg-white border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1 card-shadow">
                <div class="w-12 h-12 bg-purplePale text-purple rounded-xl flex items-center justify-center text-xl mb-4">
                    📍
                </div>
                <p class="my-2 text-navy font-bold text-lg">{{ __('Address') }}</p>
                <p class="text-muted mb-1">Ramzová 345</p>
                <p class="text-muted mb-1">Ostružná, 788 25</p>
                <p class="text-muted mt-auto pt-4">{{ __('Czech Republic') }}</p>
            </div>

            <div class="flex flex-col p-6 py-8 rounded-2xl bg-white border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1 card-shadow">
                <div class="w-12 h-12 bg-purplePale text-purple rounded-xl flex items-center justify-center text-xl mb-4">
                    🌍
                </div>
                <p class="my-2 text-navy font-bold text-lg">{{ __('Social Media') }}</p>
                <div class="flex flex-col gap-3 w-full mt-2">
                    <a href="https://www.facebook.com/apartmanstratos" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 py-2 px-3 rounded-xl bg-gray border border-border hover:bg-purplePale hover:border-purplePale text-navy transition-all w-fit pr-5">
                        <span class="w-6 text-center text-purple font-bold">FB</span> Facebook
                    </a>
                    <a href="https://www.instagram.com/apartmanstratos/" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 py-2 px-3 rounded-xl bg-gray border border-border hover:bg-purplePale hover:border-purplePale text-navy transition-all w-fit pr-5">
                        <span class="w-6 text-center text-purple font-bold">IG</span> Instagram
                    </a>
                </div>
            </div>

        </div>
    </section>

    <section class="flex flex-col px-8 md:px-14 py-12 md:py-16 bg-gray border-t border-border" id="faq">
        <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-2 md:mb-4">{{ __('FAQ') }}</p>
        <h6 class="text-3xl md:text-4xl text-navy font-serif mb-2">{{ __('Frequently asked questions.') }}</h6>
        <p class="text-muted mb-8 md:mb-10 max-w-2xl">
            {{ __('Did not find what you were looking for?') }} 
            <a href="mailto:info@apartmanstratos.cz" class="relative group text-purple font-bold hover:underline ml-1">
                {{ __('Write to us') }}
                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 bg-navy text-white text-xs font-medium rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-all duration-200 whitespace-nowrap z-50 shadow-lg">
                    info@apartmanstratos.cz
                    <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-navy"></span>
                </span>
            </a>
        </p>

        <div class="bg-white rounded-2xl border border-border shadow-sm card-shadow overflow-hidden w-full max-w-4xl">
            <flux:accordion transition exclusive>
                @foreach($faqs as $faq)
                    <flux:accordion.item wire:key="faq-{{ $faq->id }}" class="border-b border-border last:border-0">
                        <flux:accordion.heading class="text-navy font-bold py-5 px-6 hover:bg-purpleGhost transition-colors">
                            {{ $faq->question }}
                        </flux:accordion.heading>

                        <flux:accordion.content class="text-muted leading-relaxed py-6 px-6">
                            {!! nl2br(e($faq->answer)) !!}
                        </flux:accordion.content>
                    </flux:accordion.item>
                @endforeach
            </flux:accordion>
        </div>
    </section>
</div>