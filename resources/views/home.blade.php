<x-app-layout>
    
    <!-- Hero Section -->
    <section class="relative h-[94vh] min-h-130 overflow-hidden">
        <div x-data="{ idx: 0, slides: {{ Js::from($heroSlides) }} }"
            x-init="setInterval(() => idx = (idx + 1) % slides.length, 5500)" class="h-full relative">

            <div class="apt-slides flex h-full transition-transform duration-700"
                :style="`transform:translateX(-${idx * 100}%);`">
                <template x-for="(slide, i) in slides" :key="i">
                    <div class="apt-slide min-w-full h-full relative">
                        <div class="sc-r1 absolute inset-0 bg-cover bg-center" :style="`background-image: url('${slide.image_url}')`">
                        </div>
                        <div
                            class="apt-slide-overlay absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent">
                        </div>

                        <div
                            class="apt-slide-content absolute bottom-28 sm:bottom-20 md:bottom-16 left-0 sm:left-10 md:left-12 w-full p-8 md:p-12 z-10 text-white">
                            <div class="max-w-7xl mx-auto">
                                <template x-if="slide.is_new">
                                    <div class="slide-label">{{ __('New') }}</div>
                                </template>
                                
                                <h1 class="font-serif text-5xl md:text-6xl leading-tight mb-3">
                                    <span x-text="slide.title"></span>
                                    <template x-if="slide.highlighted_title">
                                        <em class="text-teal" x-text="slide.highlighted_title"></em>
                                    </template>
                                </h1>
                                
                                <p class="max-w-xl text-white/80 mb-6" x-text="slide.description"></p>
                                
                                <div class="flex gap-3">
                                    <a href="{{ route('reservation') }}"
                                        class="flex flex-col justify-center md:hidden btn-teal px-4 py-2 rounded-xl font-semibold duration-200 transition-all hover:-translate-y-1 teal-shadow">{{
                                        __('Book') }}</a>
                                    <a href="{{ route('reservation') }}"
                                        class="hidden md:flex flex-col justify-center btn-teal px-6 py-3 rounded-xl font-bold duration-200 transition-all hover:-translate-y-1 teal-shadow">{{
                                        __('Book') }} →</a>
                                    <a href="#"
                                        class="flex flex-col justify-center md:hidden btn-gho inline-flex items-center px-3 sm:px-4 py-2 rounded-xl border border-white/30 text-white duration-200 transition-all hover:-translate-y-1">{{
                                        __('Find stay') }}</a>
                                    <a href="#apartments"
                                        class="hidden md:flex flex-col justify-center btn-gho inline-flex items-center px-5 py-3 rounded-xl border border-white/30 text-white duration-200 transition-all hover:-translate-y-1">{{
                                        __('Find ideal stay') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="pointer-events-none absolute inset-0 z-30 flex items-center justify-between px-4 md:px-6">
                <button
                    class="pointer-events-auto hover:cursor-pointer bg-white/10 hover:bg-white/20 text-white w-11 h-11 rounded-full flex items-center justify-center transition-all duration-300"
                    @click="idx = (idx - 1 + slides.length) % slides.length">
                    ‹
                </button>
                <button
                    class="pointer-events-auto hover:cursor-pointer bg-white/10 hover:bg-white/20 text-white w-11 h-11 rounded-full flex items-center justify-center transition-all duration-300"
                        @click="idx = (idx + 1) % slides.length">
                    ›
                </button>
            </div>
        </div>
    </section>

    <!-- Reservation Widget -->
    <livewire:reservation-widget id="reservation" />

    <!-- Marquee -->
    <section class="bg-purplePale border-t border-b border-border overflow-hidden py-3 mb-10 rounded-md" id="marquee">
        <style>
            @keyframes marquee {
                from {
                    transform: translateX(0);
                }

                to {
                    transform: translateX(-50%);
                }
            }
        </style>
        <div class="flex whitespace-nowrap items-center" style="animation: marquee 26s linear infinite;">
            <span class="text-sm font-medium text-purple px-7">{{ __('Wellness & sauna') }}</span>
            <span class="text-sm font-medium text-teal px-7">{{ __('Free parking') }}</span>
            <span class="text-sm font-medium text-muted px-7">{{ __('Only 3 weekends left in Feb') }}</span>
            <span class="text-sm font-medium text-purple px-7">{{ __('Pet friendly') }}</span>
            <span class="text-sm font-medium text-teal px-7">{{ __('Direct booking saves fees') }}</span>
            <span class="text-sm font-medium text-purple px-7">{{ __('Wellness & sauna') }}</span>
            <span class="text-sm font-medium text-teal px-7">{{ __('Free parking') }}</span>
            <span class="text-sm font-medium text-muted px-7">{{ __('Only 3 weekends left in Feb') }}</span>
            <span class="text-sm font-medium text-purple px-7">{{ __('Pet friendly') }}</span>
            <span class="text-sm font-medium text-teal px-7">{{ __('Direct booking saves fees') }}</span>
        </div>
    </section>

    <!-- Apartment Selection Section -->
    <section class="flex flex-col gap-2 px-8 md:px-14" id="apartments">
        <h5 class="text-teal font-bold text-xs tracking-[8%] uppercase">{{ __('Select your stay') }}</h5>
        <h4 class="font-serif text-3xl text-navy">{{ __('Two destinations, one apartment for you') }}</h4>
        <p class="text-sm sm:text-smm text-muted">{{ __('Every place has its own soul - choose yours.') }}</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 mt-2 md:mt-5">
            @foreach ($apartments as $apartment)
            <div
                class="w-full h-full rounded-2xl bg-white border-[1.5px] border-border shadow-md card-shadow transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <div x-data="{ idx: 0, count: {{ $apartment->photosMain->count() }} }"
                    class="relative flex flex-col justify-end w-full h-60 rounded-t-2xl overflow-hidden">
            
                    <div class="apt-slides flex h-full transition-transform duration-700"
                        :style="`transform:translateX(-${idx * 100}%);`">
                        @foreach ($apartment->photosMain as $photo)
                        <div class="apt-slide min-w-full h-full relative">
                            <img src="{{ Storage::url($photo->path) }}" alt="{{ __('Apartment view') }}"
                                class="w-full h-full object-cover" />
            
                            @if($photo->tag)
                            <div
                                class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">
                                {{ $photo->tag }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
            
                    <div class="absolute bottom-3 right-4 flex gap-1 z-20">
                        @foreach ($apartment->photosMain as $index => $photo)
                        <div :class="[
                                                    'transition-all duration-300 cursor-pointer',
                                                    idx === {{ $index }}
                                                        ? 'bg-teal w-6 h-2 rounded-full'
                                                        : 'bg-teal/60 w-2 h-2 rounded-full opacity-60'
                                                ]" @click="idx = {{ $index }}"></div>
                        @endforeach
                    </div>
            
                    <div class="c-arrow prev absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/10 duration-300 transition-all hover:bg-white/20 text-white w-3.5 h-3.5 rounded-full flex items-center justify-center cursor-pointer text-base"
                        @click="idx = count > 0 ? (idx - 1 + count) % count : 0">‹</div>
                    <div class="c-arrow next absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/10 duration-300 transition-all hover:bg-white/20 text-white w-3.5 h-3.5 rounded-full flex items-center justify-center cursor-pointer text-base"
                        @click="idx = count > 0 ? (idx + 1) % count : 0">›</div>
                </div>
            
                <div class="flex flex-col py-6 px-5 gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-purple flex items-center justify-center text-white font-bold">
                            S</div>
                        <div class="flex flex-col">
                            <div class="flex gap-2">
                                <div class="font-bold text-xs uppercase text-teal">{{ __($apartment->address) }}</div>
                            </div>
                            <div class="text-lg text-navy font-serif">{{ __($apartment->name) }}</div>
                        </div>
                    </div>
                    <p class="px-1 text-sm text-muted">{{ __($apartment->description) }}</p>
                    <div class="flex px-2 gap-2 mt-1 mb-2">
                        @foreach ($apartment->tags as $tag)
                        <span
                            class="flex flex-col justify-center py-1 px-3 rounded-xl text-xs text-purple bg-purplePale border border-border">
                            {{ __($tag['value']) }}
                        </span>
                        @endforeach
                    </div>
                    <a href="{{ route('apartments.show', $apartment->slug) }}"
                        class="btn-teal px-4 py-1.5 ml-2 w-fit rounded-xl font-bold duration-200 transition-all hover:translate-x-1 teal-shadow text-sm">
                        {{ __('Explore apartment') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="flex flex-col gap-4 p-8 md:px-14 md:py-12 md:pb-14 rounded-t-lg">
        <h5 class="text-2xl font-serif text-navy">{{ __('Photo gallery') }}</h5>
        
        <div x-data="{ 
                lightbox: false, 
                lightboxIdx: 0, 
                images: {{ $galleryImages->count() > 0 ? Js::from($galleryImages->map(fn($p) => Storage::url($p->path))->values()) : Js::from($apartmentImages) }},
                tags: {{ $galleryImages->count() > 0 ? Js::from($galleryImages->map(fn($p) => $p->tag ? __($p->tag) : '')->values()) : Js::from(array_fill(0, count($apartmentImages), '')) }}
            }" class="w-full">
            
            <div class="flex md:hidden overflow-x-auto gap-4 snap-x snap-mandatory pb-2 touch-pan-x [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                <template x-for="(img, idx) in images" :key="idx">
                    <div class="w-[85%] shrink-0 snap-center rounded-3xl overflow-hidden relative h-64 border border-border/50 shadow-sm">
                        <img :src="img" alt="{{ __('Apartment view') }}" class="w-full h-full object-cover" />
                        <div class="absolute bottom-4 right-4 text-xs font-bold tracking-widest text-white/90 bg-black/40 backdrop-blur-md px-3 py-1.5 rounded-full">
                            <span x-text="idx + 1"></span> / <span x-text="images.length"></span>
                        </div>
                    </div>
                </template>
            </div>

            @php
                $count = $galleryImages->count();
            @endphp

            <div class="hidden md:grid gap-3 h-80 w-full 
                @if($count === 1) grid-cols-1 grid-rows-1
                @elseif($count === 2) grid-cols-2 grid-rows-1
                @elseif($count === 3) grid-cols-3 grid-rows-1
                @elseif($count === 4) grid-cols-4 grid-rows-2
                @else grid-cols-4 grid-rows-2 @endif">
                
                @forelse ($galleryImages->take(5) as $index => $photo)
                    @if ($count === 1)
                        <div class="flex flex-col justify-end col-span-1 row-span-1 rounded-3xl cursor-pointer group overflow-hidden relative"
                            @click="lightbox = true; lightboxIdx = 0">
                            <img src="{{ Storage::url($photo->path) }}" alt="{{ $photo->tag ?? __('Apartment view') }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                            @if($photo->tag)
                                <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">
                                    {{ __($photo->tag) }}
                                </div>
                            @endif
                        </div>

                    @elseif ($count === 2)
                        <div class="flex flex-col justify-end col-span-1 row-span-1 {{ $index === 0 ? 'rounded-l-3xl' : 'rounded-r-3xl' }} cursor-pointer group overflow-hidden relative"
                            @click="lightbox = true; lightboxIdx = {{ $index }}">
                            <img src="{{ Storage::url($photo->path) }}" alt="{{ $photo->tag ?? __('Apartment view') }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                            @if($photo->tag)
                                <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">
                                    {{ __($photo->tag) }}
                                </div>
                            @endif
                        </div>

                    @elseif ($count === 3)
                        <div class="flex flex-col justify-end col-span-1 row-span-1 {{ $index === 0 ? 'rounded-l-3xl' : ($index === 2 ? 'rounded-r-3xl' : '') }} cursor-pointer group overflow-hidden relative"
                            @click="lightbox = true; lightboxIdx = {{ $index }}">
                            <img src="{{ Storage::url($photo->path) }}" alt="{{ $photo->tag ?? __('Apartment view') }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                            @if($photo->tag)
                                <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">
                                    {{ __($photo->tag) }}
                                </div>
                            @endif
                        </div>

                    @elseif ($count === 4)
                        <div class="flex flex-col justify-end {{ $index === 0 ? 'col-span-2 row-span-2 rounded-l-3xl' : 'col-span-1 row-span-1' }} {{ $index === 2 ? 'rounded-tr-3xl' : '' }} {{ $index === 3 ? 'rounded-br-3xl' : '' }} cursor-pointer group overflow-hidden relative"
                            @click="lightbox = true; lightboxIdx = {{ $index }}">
                            <img src="{{ Storage::url($photo->path) }}" alt="{{ $photo->tag ?? __('Apartment view') }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                            @if($photo->tag)
                                <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">
                                    {{ __($photo->tag) }}
                                </div>
                            @endif
                        </div>

                    @else
                        @if ($index === 0)
                            <div class="flex flex-col justify-end col-span-2 row-span-2 rounded-l-3xl cursor-pointer group overflow-hidden relative"
                                @click="lightbox = true; lightboxIdx = 0">
                                <img src="{{ Storage::url($photo->path) }}" alt="{{ $photo->tag ?? __('Apartment view') }}"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                @if($photo->tag)
                                    <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">
                                        {{ __($photo->tag) }}
                                    </div>
                                @endif
                            </div>
                        @elseif ($index === 1 || $index === 2)
                            <div class="flex flex-col justify-end col-span-1 row-span-1 {{ $index === 2 ? 'rounded-tr-3xl' : '' }} cursor-pointer group overflow-hidden relative"
                                @click="lightbox = true; lightboxIdx = {{ $index }}">
                                <img src="{{ Storage::url($photo->path) }}" alt="{{ $photo->tag ?? __('Apartment view') }}"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                @if($photo->tag)
                                    <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">
                                        {{ __($photo->tag) }}
                                    </div>
                                @endif
                            </div>
                        @elseif ($index === 3)
                            <div class="flex flex-col justify-end col-span-1 row-span-1 cursor-pointer group overflow-hidden relative"
                                @click="lightbox = true; lightboxIdx = {{ $index }}">
                                <img src="{{ Storage::url($photo->path) }}" alt="{{ $photo->tag ?? __('Apartment view') }}"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                @if($photo->tag)
                                    <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">
                                        {{ __($photo->tag) }}
                                    </div>
                                @endif
                            </div>
                        @elseif ($index === 4)
                            <div class="flex flex-col justify-center col-span-1 row-span-1 rounded-br-3xl cursor-pointer group overflow-hidden relative"
                                @click="lightbox = true; lightboxIdx = {{ $index }}">
                                <img src="{{ Storage::url($photo->path) }}" alt="{{ __('More photos') }}"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105 opacity-80" />
                                    @if ($galleryImages->count() - 5 > 0)
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white font-semibold text-lg">
                                            +{{ $galleryImages->count() - 5 }} {{ __('photos') }} →
                                        </div>
                                    @endif
                            </div>
                        @endif
                    @endif
                @empty
                    <div class="flex flex-col justify-end col-span-2 row-span-2 rounded-l-3xl cursor-pointer group overflow-hidden relative"
                        @click="lightbox = true; lightboxIdx = 0">
                        <img :src="images[0]" alt="{{ __('Apartment view') }}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                        <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">
                            {{ __('Apartment view') }}</div>
                    </div>
                    <div class="flex flex-col justify-end col-span-1 row-span-1 cursor-pointer group overflow-hidden relative"
                        @click="lightbox = true; lightboxIdx = 1">
                        <img :src="images[1]" alt="{{ __('Apartment view') }}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                    </div>
                    <div class="flex flex-col justify-end col-span-1 row-span-1 rounded-tr-3xl cursor-pointer group overflow-hidden relative"
                        @click="lightbox = true; lightboxIdx = 2">
                        <img :src="images[2]" alt="{{ __('Apartment view') }}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                    </div>
                    <div class="flex flex-col justify-end col-span-1 row-span-1 cursor-pointer group overflow-hidden relative"
                        @click="lightbox = true; lightboxIdx = 3">
                        <img :src="images[3]" alt="{{ __('Apartment view') }}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                    </div>
                    <div class="flex flex-col justify-center col-span-1 row-span-1 rounded-br-3xl cursor-pointer group overflow-hidden relative"
                        @click="lightbox = true; lightboxIdx = 4">
                        <img :src="images[4]" alt="{{ __('More photos') }}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105 opacity-80" />
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white font-semibold text-lg">
                            +{{ count($apartmentImages) - 5 }} {{ __('photos') }} →</div>
                    </div>
                @endforelse
            </div>

            <div x-show="lightbox" style="display: none;" 
                class="fixed inset-0 z-[100] hidden md:flex flex-col items-center justify-center bg-black/95 backdrop-blur-xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @keydown.window.escape="lightbox = false"
                @keydown.window.arrow-right="if(lightbox) lightboxIdx = (lightboxIdx + 1) % images.length"
                @keydown.window.arrow-left="if(lightbox) lightboxIdx = (lightboxIdx - 1 + images.length) % images.length"
                x-init="$watch('lightbox', val => { if(window.innerWidth >= 768) document.body.style.overflow = val ? 'hidden' : ''; })">
                
                <div class="absolute top-0 left-0 right-0 flex justify-between items-center p-6 z-50">
                    <div class="text-white/80 font-medium text-sm tracking-widest uppercase">
                        <span x-text="lightboxIdx + 1"></span> / <span x-text="images.length"></span>
                    </div>
                    
                    <span class="text-xl text-white font-bold tracking-wide" x-show="tags[lightboxIdx]" x-text="tags[lightboxIdx]"></span>
                    
                    <button @click="lightbox = false" class="p-3 bg-white/10 hover:bg-white/20 text-white rounded-full backdrop-blur-md transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-teal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <button @click="lightboxIdx = (lightboxIdx - 1 + images.length) % images.length" class="absolute left-8 top-1/2 -translate-y-1/2 p-4 bg-white/10 hover:bg-white/20 text-white rounded-full backdrop-blur-md transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-teal z-50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                
                <button @click="lightboxIdx = (lightboxIdx + 1) % images.length" class="absolute right-8 top-1/2 -translate-y-1/2 p-4 bg-white/10 hover:bg-white/20 text-white rounded-full backdrop-blur-md transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-teal z-50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"></path></svg>
                </button>

                <div class="relative w-full flex-1 flex items-center justify-center p-16 mb-24 mt-16" @click.self="lightbox = false">
                    <img :src="images[lightboxIdx]" 
                        class="max-h-[80vh] max-w-full rounded-lg shadow-2xl object-contain ring-1 ring-white/10" />
                </div>

                <div class="absolute bottom-0 left-0 w-full bg-linear-to-t from-black/90 via-black/60 to-transparent pt-12 pb-6 px-4 z-40">
                    <div class="flex items-center justify-center gap-3 overflow-x-auto w-full max-w-6xl mx-auto pt-1 pb-2 touch-pan-x [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                        <template x-for="(img, idx) in images" :key="idx">
                            <button @click="lightboxIdx = idx; $el.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' })" 
                                    class="relative shrink-0 h-20 w-32 rounded-lg overflow-hidden transition-all duration-300 focus:outline-none"
                                    :class="lightboxIdx === idx ? 'ring-2 ring-teal scale-105 z-10 opacity-100' : 'ring-1 ring-white/20 opacity-40 hover:opacity-100 hover:scale-105'">
                                <img :src="img" class="w-full h-full object-cover" />
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="flex flex-col gap-5 pt-8 md:pt-10 pb-8 md:pb-14 bg-gray w-full border-y border-border" id="features">
        <p class="mx-auto text-xs text-teal uppercase font-bold tracking-[8%] mb-0 md:mb-4 align-bottom">{{ __('Ideal
            for') }}</p>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 px-8 md:px-14">

            <div
                class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">👫</span>
                <span class="text-xs text-navy font-semibold">{{ __('Couples') }}</span>
            </div>

            <div
                class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg md:border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">👩‍🦰🐕</span>
                <span class="text-xs text-navy font-semibold">{{ __('Dog owners') }}</span>
            </div>

            <div
                class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">👨‍👩‍👧</span>
                <span class="text-xs text-navy font-semibold">{{ __('Families with children') }}</span>
            </div>

            <div
                class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg lg:border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">💻</span>
                <span class="text-xs text-navy font-semibold">{{ __('Remote work') }}</span>
            </div>

            <div
                class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">🏃</span>
                <span class="text-xs text-navy font-semibold">{{ __('Hiking and trails') }}</span>
            </div>

            <div
                class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg md:border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">⛷</span>
                <span class="text-xs text-navy font-semibold">{{ __('Skiing') }}</span>
            </div>

            <div
                class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">🧘</span>
                <span class="text-xs text-navy font-semibold">{{ __('Wellness stay') }}</span>
            </div>

            <div
                class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">🏢</span>
                <span class="text-xs text-navy font-semibold">{{ __('Company retreat') }}</span>
            </div>

        </div>
    </section>

    <!-- More Features Section -->
    <section class="flex flex-col px-8 md:px-14 py-10 pb-12" id="features2">
        <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-2 md:mb-4">{{ __('Why Stratos?') }}</p>
        <h6 class="text-3xl md:text-4xl text-navy font-serif">{{ __('Yes, we have a bed too. But that is the least interesting part.') }}</h6>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-6 sm:mt-9 md:mt-12 bg-white">
            <div
                class="flex flex-col p-6 py-8 rounded-2xl bg-cream border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <span class="text-25xl">🍳</span>
                <p class="my-2 text-navy font-bold">{{ __('Own kitchen') }}</p>
                <p class="text-text">{{ __('Breakfast when you want. Reception won\'t wake you up, because there isn\'t one.') }}</p>
            </div>

            <div
                class="flex flex-col p-6 py-8 rounded-2xl bg-cream border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <span class="text-25xl">🐾</span>
                <p class="my-2 text-navy font-bold">{{ __('Dog friendly') }}</p>
                <p class="text-text">{{ __('Your dog is as welcome as you are. No surcharges, no compromises.') }}</p>
            </div>

            <div
                class="flex flex-col p-6 py-8 rounded-2xl bg-cream border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <span class="text-25xl">🚗</span>
                <p class="my-2 text-navy font-bold">{{ __('Free parking') }}</p>
                <p class="text-text">{{ __('You park your car. And your worries.') }}</p>
            </div>

            <div
                class="flex flex-col p-6 py-8 rounded-2xl bg-cream border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <span class="text-25xl">📶</span>
                <p class="my-2 text-navy font-bold">{{ __('Fast WiFi + Netflix') }}</p>
                <p class="text-text">{{ __('We have Netflix. But outside is better.') }}</p>
            </div>

            <div
                class="flex flex-col p-6 py-8 rounded-2xl bg-cream border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <span class="text-25xl">💰</span>
                <p class="my-2 text-navy font-bold">{{ __('Better price than a hotel') }}</p>
                <p class="text-text">{{ __('Whole apartment just for you, for the price of a hotel room for two.') }}
                </p>
            </div>

            <div
                class="flex flex-col p-6 py-8 rounded-2xl bg-cream border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <span class="text-25xl">🏠</span>
                <p class="my-2 text-navy font-bold">{{ __('Personal approach') }}</p>
                <p class="text-text">{{ __('We are not a chain. You get tips from the host, not a laminated flyer.') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Review Section -->
    <section class="bg-review w-full" id="reviews">
        <div class="flex flex-col px-8 md:px-14 py-12 md:pt-14 md:pb-16">
            <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-1 md:mb-3">{{ __('Guest reviews') }}</p>
            <h6 class="text-4xl md:text-5xl text-white font-serif mb-2">{{ __('What guests say.') }}</h6>
            <p class="flex gap-2 text-[rgba(255,255,255,0.5)] text-sm md:text-base">
                <span>
                    {{ __('Over 9.8 on Booking.com') }}
                </span>
                <span>
                    ·
                </span>
                <span>
                    {{ __('Over 150 reviews') }}
                </span>
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-10 text-[rgba(255,255,255,0.72)]">
                <div
                    class="flex flex-col gap-y-4 p-6 border-[1px] border-[rgba(255,255,255,0.1)] bg-[rgba(255,255,255,0.06);] rounded-2xl">
                    <p class="text-xxs">⭐⭐⭐⭐⭐</p>
                    <p class="italic text-[15px]">„{{ __('Perfect ski base. We were on the slope in 3 minutes. Warm apartment and wine in the evening.') }}”</p>
                    <div class="flex gap-3">
                        <div
                            class="w-9 h-9 my-auto rounded-full bg-teal flex items-center justify-center text-white font-bold">
                            K</div>
                        <div class="flex flex-col">
                            <p class="text-[rgba(255,255,255,0.85)]">Kateřina M.</p>
                            <div class="flex gap-1 text-xs text-[rgba(255,255,255,0.35)]">
                                <span>
                                    Brno
                                </span>
                                <span>
                                    ·
                                </span>
                                <span>
                                    Booking.com
                                </span>
                                <span>
                                    · ⭐ 9.8
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="flex flex-col gap-y-4 p-6 border-[1px] border-[rgba(255,255,255,0.1)] bg-[rgba(255,255,255,0.06);] rounded-2xl">
                    <p class="text-xxs">⭐⭐⭐⭐⭐</p>
                    <p class="italic text-[15px]">„{{ __('Perfect ski base. We were on the slope in 3 minutes. Warm apartment and wine in the evening.') }}”</p>
                    <div class="flex gap-3">
                        <div
                            class="w-9 h-9 my-auto rounded-full bg-teal flex items-center justify-center text-white font-bold">
                            K</div>
                        <div class="flex flex-col">
                            <p class="text-[rgba(255,255,255,0.85)]">Kateřina M.</p>
                            <div class="flex gap-1 text-xs text-[rgba(255,255,255,0.35)]">
                                <span>
                                    Brno
                                </span>
                                <span>
                                    ·
                                </span>
                                <span>
                                    Booking.com
                                </span>
                                <span>
                                    · ⭐ 9.8
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="flex flex-col gap-y-4 p-6 border-[1px] border-[rgba(255,255,255,0.1)] bg-[rgba(255,255,255,0.06);] rounded-2xl">
                    <p class="text-xxs">⭐⭐⭐⭐⭐</p>
                    <p class="italic text-[15px]">„{{ __('Perfect ski base. We were on the slope in 3 minutes. Warm apartment and wine in the evening.') }}”</p>
                    <div class="flex gap-3">
                        <div
                            class="w-9 h-9 my-auto rounded-full bg-teal flex items-center justify-center text-white font-bold">
                            K</div>
                        <div class="flex flex-col">
                            <p class="text-[rgba(255,255,255,0.85)]">Kateřina M.</p>
                            <div class="flex gap-1 text-xs text-[rgba(255,255,255,0.35)]">
                                <span>
                                    Brno
                                </span>
                                <span>
                                    ·
                                </span>
                                <span>
                                    Booking.com
                                </span>
                                <span>
                                    · ⭐ 9.8
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Instagram Section -->
    <section class="flex flex-col px-8 py-10 md:px-14 md:pt-10 md:pb-12 bg-purpleGhost" id="instagram">
        <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-1 md:mb-2">{{ __('Follow us') }}</p>
        <div class="flex flex-col md:flex-row gap-y-2 justify-between w-full">
            <h6 class="text-3xl md:text-4xl text-navy font-serif hover:text-purple transition-colors duration-300">
                <a href="#" target="_blank" rel="noopener noreferrer">
                    @stratosapartments
                </a>
            </h6>
            <a href="#" target="_blank" rel="noopener noreferrer"
                class="text-sm text-purple font-semibold hover:text-purpleMid transition-colors duration-300 mt-auto">
                {{ __('Open Instagram') }} →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-6 gap-3 gap-y-5 mt-8">
            @for ($i = 0; $i < 6; $i++) <div
                class="h-40 sm:h-32 md:h-40 lg:h-60 bg-violet-300 rounded-xl hover:scale-105 transition-transform duration-300">
                <img src="{{ $apartmentImages[$i] }}" alt="" class="w-full h-full object-cover rounded-xl">
        </div>
        @endfor
        </div>
    </section>

    <!-- CTA Section -->
    <section class="px-8 md:px-14 py-12 md:py-16 bg-purplePale rounded-t-lg" id="cta">
        <div class="flex flex-col md:flex-row gap-y-4 mx-auto md:items-center justify-between">
            <div>
                <h2 class="font-serif text-3xl md:text-4xl text-navy font-bold">{{ __('Book before') }}<br>{{ __('your neighbor does.') }}</h2>
                <p class="text-muted mt-2">{{ __('Directly with us – no commission,') }}<br> {{ __('with a personal touch.') }}</p>
            </div>
            <div class="cta-btns flex gap-4">
                <a href="{{ route('reservation') }}"
                    class="btn-teal px-5 sm:px-7 lg:px-10 pt-2 pb-1 sm:py-2 md:py-3 rounded-xl text-sm sm:text-base font-normal sm:font-bold duration-200 transition-all hover:-translate-y-1 teal-shadow">{{
                    __('Book') }}</a>
                <a href="#apartments"
                    class="btn-outline-purple px-3 sm:px-4 md:px-6 py-1 sm:py-2 md:py-3 rounded-xl font-normal sm:font-semibold duration-200 transition-all hover:-translate-y-1">{{
                    __('Find stay') }}</a>
            </div>
        </div>
    </section>

</x-app-layout>
