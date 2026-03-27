<div class="w-full min-h-[80vh] flex flex-col pt-2 sm:pt-6">
    <section class="flex flex-col px-8 md:px-14 py-10 md:py-16" id="cookies">
        <div class="max-w-4xl">
            <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-2 md:mb-4">{{ __('Privacy') }}</p>
            <h6 class="text-3xl md:text-4xl text-navy font-serif mb-4 md:mb-6">{{ __('Cookie Policy.') }}</h6>
            <p class="text-muted mb-10 md:mb-12 text-base md:text-lg">
                {{ __('At Apartment Stratos, we respect your privacy. This policy explains how and why we use cookies on our website.') }}
            </p>

            <div class="prose prose-navy max-w-none space-y-10">
                
                <div>
                    <h3 class="text-xl font-bold text-navy mb-3">{{ __('What are cookies?') }}</h3>
                    <p class="text-muted leading-relaxed">
                        {{ __('Cookies are small text files that are placed on your computer or mobile device when you visit a website. They are widely used to make websites work more efficiently and provide a better user experience.') }}
                    </p>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-navy mb-3">{{ __('How we use cookies') }}</h3>
                    <ul class="list-disc pl-5 text-muted leading-relaxed space-y-3">
                        <li>
                            <strong class="text-navy">{{ __('Essential Cookies:') }}</strong> 
                            {{ __('These are required for the basic operation of our booking system and website. They handle things like keeping you logged in during the reservation process and remembering your chosen language (CZ, EN, DE).') }}
                        </li>
                        <li>
                            <strong class="text-navy">{{ __('Functional Cookies:') }}</strong> 
                            {{ __('These allow us to remember the choices you make (like the dates you selected in the reservation widget) to provide a smoother booking experience without having to re-enter data.') }}
                        </li>
                        <li>
                            <strong class="text-navy">{{ __('Analytics Cookies:') }}</strong> 
                            {{ __('If implemented, these help us understand how visitors interact with our website, so we can improve our content, layout, and performance.') }}
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-navy mb-3">{{ __('Managing your preferences') }}</h3>
                    <p class="text-muted leading-relaxed">
                        {{ __('You can control and/or delete cookies as you wish through your browser settings. You can delete all cookies that are already on your computer and you can set most browsers to prevent them from being placed. However, if you do this, you may have to manually adjust some preferences every time you visit a site and some services (like our booking form) may not work correctly.') }}
                    </p>
                </div>

            </div>
        </div>
    </section>
</div>