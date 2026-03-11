<x-app-layout>
    <div x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 150)" class="flex flex-col justify-start gap-lg h-screen">
        <h2 x-cloak :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-700 ease-out font-bold text-custom-xl">Lokalita</h2>
        
        <h3 x-cloak :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" style="transition-delay: 200ms;" class="transition-all duration-700 ease-out font-light text-custom-lg tracking-low w-[860px]">Česko-rakouské pohraniční městečko skrývá v okolí mnoho krásy</h3>

        <div class="grid grid-cols-3 justify-start w-[700px] gap-[40px] px-[10px]">
            @for ($i = 0; $i < 6; $i++)
                <div x-cloak :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'" 
                     style="transition-delay: {{ 400 + ($i * 150) }}ms;"
                     class="transition-all duration-700 ease-out flex flex-col justify-center text-center w-[200px] h-[200px] bg-red-400 gap-[12px] p-[12px]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="mx-auto size-[60px]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                    </svg>
                    
                    <span class="text-[20px] leading-[32px] tracking-[-6%]">
                        Nejaky prvek z oblasti
                    </span>
                </div>
            @endfor
        </div>
    </div>

    <div class="flex flex-col justify-start gap-xl">
        <div x-data="{ shown: false }" x-intersect.once.margin.-100px="shown = true" class="flex flex-col gap-lg">
            <h3 x-cloak :class="shown ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-8'" class="transition-all duration-700 ease-out font-medium text-custom-xl">Termální prameny</h3>
            <div class="grid grid-cols-2 sm:grid-cols-7 gap-lg">
                <div x-cloak :class="shown ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-8'" style="transition-delay: 300ms;" class="transition-all duration-700 ease-out col-span-3">
                    <h6 class="font-light text-custom-lg tracking-low">
                        V městečku se nachází <span class="font-medium italic">termální prameny</span> a kolem nich <span class="font-medium italic">moderní aquapark</span>.
                    </h6>
                </div>
                <img x-cloak :class="shown ? 'opacity-100 scale-100' : 'opacity-0 scale-95'" style="transition-delay: 500ms;" class="transition-all duration-1000 ease-out col-span-4 h-[400px]" src="https://picsum.photos/1000/400" alt="Termální prameny">
            </div>
        </div>

        <div x-data="{ shown: false }" x-intersect.once.margin.-100px="shown = true" class="flex flex-col gap-lg">
            <h3 x-cloak :class="shown ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-8'" class="transition-all duration-700 ease-out font-medium text-custom-xl">Pivovar Hubertus</h3>
            <div class="grid grid-cols-2 sm:grid-cols-7 gap-lg">
                <img x-cloak :class="shown ? 'opacity-100 scale-100' : 'opacity-0 scale-95'" style="transition-delay: 500ms;" class="transition-all duration-1000 ease-out col-span-4 h-[400px]" src="https://picsum.photos/1000/400" alt="Pivovar Hubertus">
                <div x-cloak :class="shown ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-8'" style="transition-delay: 300ms;" class="transition-all duration-700 ease-out col-span-3">
                    <h6 class="font-light text-custom-lg tracking-low">
                        Kromě toho zde také můžete najít <span class="font-medium italic">pivovar</span>, ve kterém se vaří rakouské <span class="font-medium italic">pivo Hubertus</span>.
                    </h6>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>