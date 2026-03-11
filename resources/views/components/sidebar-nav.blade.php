<div x-data="{ open: false }" @keydown.escape.window="open = false">
    
    <button @click="open = true" class="group fixed top-8 right-8 z-[50] p-2 text-white cursor-pointer focus:outline-none">
        <div class="relative w-10 h-10">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="absolute inset-0 w-10 h-10 transition-opacity duration-300 opacity-100 group-hover:opacity-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
            
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="absolute inset-0 w-10 h-10 transition-opacity duration-300 opacity-0 group-hover:opacity-100">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M12 17.25h8.25" />
            </svg>
        </div>
    </button>

    <template x-teleport="body">
        <div x-show="open" style="display: none;">
            
            <div x-show="open" 
                 x-transition.opacity.duration.400ms
                 @click="open = false"
                 class="fixed inset-0 z-[60] bg-black/70 backdrop-blur-md cursor-pointer">
            </div>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-500 transform"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-400 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="fixed inset-y-0 right-0 z-[70] w-full sm:w-[40%] lg:w-[30%] bg-primary shadow-2xl border-l border-white/10 flex flex-col">
                 
                 <button @click="open = false" class="absolute top-8 right-8 text-white/50 hover:text-secondary transition-colors duration-300 focus:outline-none cursor-pointer p-2 z-[80]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                 </button>

                 <div class="flex-grow flex flex-col justify-center items-center">
                     <nav class="flex flex-col gap-10 text-center text-white">
                         <a href="{{ route('home') }}" class="text-3xl font-medium tracking-[0.1em] hover:text-secondary transition-colors duration-300">{{ __('Home') }}</a>
                         <a href="{{ route('about') }}" class="text-3xl font-medium tracking-[0.1em] hover:text-secondary transition-colors duration-300">{{ __('Location') }}</a>
                         <a href="{{ route('pricing') }}" class="text-3xl font-medium tracking-[0.1em] hover:text-secondary transition-colors duration-300">{{ __('Pricing') }}</a>
                         <a href="{{ route('reservation') }}" class="text-3xl font-medium tracking-[0.1em] hover:text-secondary transition-colors duration-300">{{ __('Reservation') }}</a>
                     </nav>
                 </div>

                 <div class="w-full pb-12 pt-6">
                     <div class="flex justify-center items-center gap-6 text-white">
                         @php $current = app()->getLocale(); @endphp
                         <a href="{{ route('locale.switch', 'cs') }}" class="text-xl font-medium tracking-[0.1em] transition-colors duration-300 hover:text-secondary {{ $current === 'cs' ? 'text-secondary' : '' }}">Česky</a>
                         <a href="{{ route('locale.switch', 'en') }}" class="text-xl font-medium tracking-[0.1em] transition-colors duration-300 hover:text-secondary {{ $current === 'en' ? 'text-secondary' : '' }}">English</a>
                         <a href="{{ route('locale.switch', 'de') }}" class="text-xl font-medium tracking-[0.1em] transition-colors duration-300 hover:text-secondary {{ $current === 'de' ? 'text-secondary' : '' }}">Deutsch</a>
                     </div>
                 </div>
            </div>
        </div>
    </template>
</div>