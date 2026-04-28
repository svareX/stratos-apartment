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
    <section class="flex flex-col px-8 py-10 md:px-14 md:py-16" id="terms">
        <div class="max-w-4xl">
            <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase md:mb-4">{{ __('Legal') }}</p>
            <h6 class="text-navy mb-4 font-serif text-3xl md:mb-6 md:text-4xl">
                {{ __('Terms and Conditions.') }}
            </h6>
            <p class="text-muted mb-10 text-base md:mb-12 md:text-lg">
                {{ __('These Terms and Conditions govern the relationship between the operator of Apartment Stratos and the guests. By making a reservation, you agree to the following rules.') }}
            </p>

            <div class="prose prose-navy max-w-none space-y-10">
                <div>
                    <h3 class="text-navy mb-3 text-xl font-bold">
                        {{ __('1. Reservations and Payment') }}
                    </h3>
                    <ul
                        class="text-muted list-disc space-y-2 pl-5 leading-relaxed"
                    >
                        <li>
                            {{ __('Reservations made through our website are direct and commission-free.') }}
                        </li>
                        <li>
                            {{ __('After submitting the reservation form, you will receive a confirmation email with payment instructions.') }}
                        </li>
                        <li>
                            {{ __('A standard cleaning fee is applied to stays shorter than the specified number of days for each apartment.') }}
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-navy mb-3 text-xl font-bold">
                        {{ __('2. Check-in and Check-out') }}
                    </h3>
                    <p class="text-muted leading-relaxed">
                        {{ __('Since we do not have a physical reception, check-in is contactless. You will receive instructions and an access code before your arrival. Standard check-in is from 15:00, and check-out is until 10:00, unless agreed otherwise.') }}
                    </p>
                </div>

                <div>
                    <h3 class="text-navy mb-3 text-xl font-bold">
                        {{ __('3. Dog Policy') }}
                    </h3>
                    <p class="text-muted leading-relaxed">
                        {{ __('We are proudly dog-friendly! Dogs stay for free. However, owners are fully responsible for their pets, any damages caused, and must ensure the dog does not disturb other guests or neighbors.') }}
                    </p>
                </div>

                <div>
                    <h3 class="text-navy mb-3 text-xl font-bold">
                        {{ __('4. Cancellation Policy') }}
                    </h3>
                    <p class="text-muted leading-relaxed">
                        {{ __('Cancellations made more than 14 days before arrival are fully refunded. Cancellations made within 14 days of arrival may be subject to a cancellation fee equivalent to the first night\'s stay.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section
        class="bg-gray border-border flex flex-col border-t px-8 py-12 md:px-14 md:py-20"
        id="privacy"
    >
        <div class="max-w-4xl">
            <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase md:mb-4">{{ __('Data Protection') }}</p>
            <h6 class="text-navy mb-4 font-serif text-3xl md:mb-6 md:text-4xl">
                {{ __('Privacy Policy.') }}
            </h6>
            <p class="text-muted mb-10 text-base md:mb-12 md:text-lg">
                {{ __('We value your trust. Here is a clear overview of how we handle and protect your personal information.') }}
            </p>

            <div class="prose prose-navy max-w-none space-y-10">
                <div>
                    <h3 class="text-navy mb-3 text-xl font-bold">
                        {{ __('1. Data Collection') }}
                    </h3>
                    <p class="text-muted leading-relaxed">
                        {{ __('We collect personal data necessary for processing your reservation, including your name, email address, phone number, and billing address. We only collect what is strictly necessary to provide you with accommodation services.') }}
                    </p>
                </div>

                <div>
                    <h3 class="text-navy mb-3 text-xl font-bold">
                        {{ __('2. Use of Data') }}
                    </h3>
                    <p class="text-muted leading-relaxed">
                        {{ __('Your data is used exclusively to fulfill your booking, provide customer support before and during your stay, and comply with legal obligations (such as the local tourist tax and foreign police reporting requirements).') }}
                    </p>
                </div>

                <div>
                    <h3 class="text-navy mb-3 text-xl font-bold">
                        {{ __('3. Data Sharing & Third Parties') }}
                    </h3>
                    <p class="text-muted leading-relaxed">
                        {{ __('We do not sell, rent, or share your personal information with third parties for marketing purposes. Your data may be shared with local authorities only when legally required by the laws of the respective country (Czech Republic or Austria).') }}
                    </p>
                </div>

                <div>
                    <h3 class="text-navy mb-3 text-xl font-bold">
                        {{ __('4. Your Rights') }}
                    </h3>
                    <p class="text-muted leading-relaxed">
                        {{ __('You have the right to request access to the personal data we hold about you, ask for corrections, or request its deletion, provided that the deletion does not conflict with our legal data retention obligations.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>
