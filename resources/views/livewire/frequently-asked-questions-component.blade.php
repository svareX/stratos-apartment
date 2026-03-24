<div class="max-w-4xl mx-auto px-8 py-8 sm:py-10 md:py-12 lg:py-14 min-h-[80vh]">
    <div class="text-center mt-5 mb-14">
        <h5 class="text-teal font-bold text-xs tracking-[8%] uppercase mb-3">{{ __('FAQ') }}</h5>
        <h2 class="font-serif text-4xl md:text-5xl text-navy">{{ __('Frequently asked questions') }}</h2>
    </div>

    <div class="bg-white rounded-2xl border border-border shadow-sm card-shadow overflow-hidden">
        <flux:accordion transition exclusive>
            @foreach($faqs as $faq)
                <flux:accordion.item wire:key="faq-{{ $faq->id }}" class="border-b border-border last:border-0">
                    <flux:accordion.heading class="text-navy font-bold py-5 px-6 hover:bg-purple-ghost transition-colors">
                        {{ $faq->question }}
                    </flux:accordion.heading>

                    <flux:accordion.content class="text-muted leading-relaxed py-6 px-6">
                        {!! nl2br(e($faq->answer)) !!}
                    </flux:accordion.content>
                </flux:accordion.item>
            @endforeach
        </flux:accordion>
    </div>

    <div class="mt-12 text-center">
        <p class="text-muted text-sm italic">{{ __('Did not find what you were looking for?') }} 
            <a href="mailto:info@apartmanstratos.cz" class="text-purple font-bold hover:underline ml-1">{{ __('Write to us') }}</a>
        </p>
    </div>
</div>