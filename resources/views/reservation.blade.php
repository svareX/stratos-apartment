<x-app-layout>
    <div x-data="{ shown: false }" x-intersect.once="shown = true" class="flex flex-col justify-start gap-lg">
        <div x-cloak x-show="shown" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
            <livewire:reservation-page lazy />
        </div>
    </div>
</x-app-layout>