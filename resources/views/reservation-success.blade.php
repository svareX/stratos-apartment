<x-app-layout>
    <div
        x-data="{ shown: false }"
        x-intersect.once="shown = true"
        class="gap-lg flex flex-col justify-start"
    >
        <h2
            x-cloak
            x-show="shown"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="text-custom-xl font-bold"
        >
            {{ __('Reservation was successful') }}
        </h2>
        <h4
            x-cloak
            x-show="shown"
            x-transition:enter="transition ease-out duration-700 delay-300"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="text-custom-lg font-normal"
        >
            {{ __('Thank you for your reservation. We look forward to your visit, we will contact you soon.') }}
        </h4>
    </div>
</x-app-layout>
