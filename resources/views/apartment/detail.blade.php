<x-app-layout>
    <section class="relative h-[94vh] min-h-130 overflow-hidden">
        <div
            x-data="{ idx: 0, slides: {{ Js::from($slides) }} }"
            x-init="setInterval(() => (idx = (idx + 1) % slides.length), 5500)"
            class="relative h-full"
        >
            <div
                class="apt-slides flex h-full transition-transform duration-700"
                :style="`transform:translateX(-${idx * 100}%);`"
            >
                <template x-for="(slide, i) in slides" :key="i">
                    <div class="apt-slide relative h-full min-w-full">
                        <div
                            class="sc-r1 absolute inset-0 bg-cover bg-center"
                            :style="`background-image: url('${slide.image_url}')`"
                        ></div>
                        <div
                            class="apt-slide-overlay absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"
                        ></div>

                        <div
                            class="apt-slide-content absolute bottom-28 left-0 z-10 w-full p-8 text-white sm:bottom-20 sm:left-10 md:bottom-16 md:left-12 md:p-12"
                        >
                            <div class="mx-auto max-w-7xl">
                                <template x-if="slide.is_new">
                                    <div class="slide-label">
                                        {{ __('New') }}
                                    </div>
                                </template>

                                <h1
                                    class="mb-3 font-serif text-5xl leading-tight md:text-6xl"
                                >
                                    <span x-text="slide.title"></span>
                                    <template x-if="slide.highlighted_title">
                                        <em
                                            class="text-teal"
                                            x-text="slide.highlighted_title"
                                        ></em>
                                    </template>
                                </h1>

                                <p class="mb-6 max-w-xl text-white/80" x-text="
                                        slide.description
                                    "></p>

                                <div class="flex gap-3">
                                    <a
                                        href="{{ route('reservation', ['locale' => app()->getLocale(), 'apartment' => $apartment->slug]) }}"
                                        class="btn-teal teal-shadow flex flex-col justify-center rounded-xl px-4 py-2 font-semibold transition-all duration-200 hover:-translate-y-1 md:hidden"
                                        >{{
                                        __('Book') }}</a
                                    >
                                    <a
                                        href="{{ route('reservation', ['locale' => app()->getLocale(), 'apartment' => $apartment->slug]) }}"
                                        class="btn-teal teal-shadow hidden flex-col justify-center rounded-xl px-6 py-3 font-bold transition-all duration-200 hover:-translate-y-1 md:flex"
                                        >{{
                                        __('Book') }} →</a
                                    >
                                    <a
                                        href="#apartments"
                                        class="btn-gho flex flex-col items-center justify-center rounded-xl border border-white/30 px-3 py-2 text-white transition-all duration-200 hover:-translate-y-1 sm:px-4 md:hidden"
                                        >{{
                                        __('Find ideal stay') }}</a
                                    >
                                    <a
                                        href="#apartments"
                                        class="btn-gho hidden flex-col items-center justify-center rounded-xl border border-white/30 px-5 py-3 text-white transition-all duration-200 hover:-translate-y-1 md:flex"
                                        >{{
                                        __('Find ideal stay') }}</a
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div
                class="pointer-events-none absolute inset-0 z-30 flex items-center justify-between px-4 md:px-6"
            >
                <button
                    class="pointer-events-auto flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition-all duration-300 hover:bg-white/20"
                    @click="idx = (idx - 1 + slides.length) % slides.length"
                >
                    ‹
                </button>
                <button
                    class="pointer-events-auto flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition-all duration-300 hover:bg-white/20"
                    @click="idx = (idx + 1) % slides.length"
                >
                    ›
                </button>
            </div>
        </div>
    </section>

    <livewire:reservation-widget />

    @if ($galleryPhotos->count() > 0)
        <section
            class="flex flex-col gap-4 rounded-t-lg p-8 md:px-14 md:py-12 md:pb-14"
            id="gallery"
        >
            <h5 class="text-navy font-serif text-2xl">
                {{ __('Photo gallery') }}
            </h5>

            <div
                x-data="{ 
                    lightbox: false, 
                    lightboxIdx: 0, 
                    images: {{ Js::from($apartmentImages) }},
                    apartmentName: {{ Js::from($apartment->name ?? '') }},
                    tags: {{ $galleryPhotos->count() > 0 ? Js::from($galleryPhotos->map(fn($p) => $p->tag ?? '')->values()) : Js::from(array_fill(0, count($apartmentImages), '')) }}
                }"
                class="w-full"
            >
                <div
                    class="[&::-webkit-scrollbar]:hidden flex touch-pan-x snap-x snap-mandatory gap-4 overflow-x-auto pb-2 [-ms-overflow-style:none] [scrollbar-width:none] md:hidden"
                >
                    <template x-for="(img, idx) in images" :key="idx">
                        <div
                            class="border-border/50 relative h-64 w-[85%] shrink-0 snap-center overflow-hidden rounded-3xl border shadow-sm"
                        >
                            <img
                                :src="img"
                                :alt="tags[idx] || apartmentName || '{{ __('Apartment view') }}'"
                                class="h-full w-full object-cover"
                            />
                            <div
                                class="absolute right-4 bottom-4 rounded-full bg-black/40 px-3 py-1.5 text-xs font-bold tracking-widest text-white/90 backdrop-blur-md"
                            >
                                <span x-text="idx + 1"></span> /
                                <span x-text="images.length"></span>
                            </div>
                        </div>
                    </template>
                </div>

                @php
                    $count = $galleryPhotos->count();
                @endphp

                <div
                    class="hidden md:grid gap-3 h-80 w-full 
                    @if($count === 1) grid-cols-1 grid-rows-1
                    @elseif($count === 2) grid-cols-2 grid-rows-1
                    @elseif($count === 3) grid-cols-3 grid-rows-1
                    @elseif($count === 4) grid-cols-4 grid-rows-2
                    @else grid-cols-4 grid-rows-2 @endif"
                >
                    @forelse ($galleryPhotos->take(5) as $index => $photo)
                        @if ($count === 1)
                            <div
                                class="group relative col-span-1 row-span-1 flex cursor-pointer flex-col justify-end overflow-hidden rounded-3xl"
                                @click="
                                    lightbox = true;
                                    lightboxIdx = 0;
                                "
                            >
                                <x-responsive-image
                                    :path="$photo->path"
                                    :alt="$photo->tag ?: $apartment->name ?: __('Apartment view')"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                                @if ($photo->tag ?? false)
                                    <div
                                        class="absolute bottom-0 left-0 mb-3 ml-4 w-fit rounded-xl border-[1px] border-[rgba(255,255,255,0.15)] bg-[rgba(0,0,0,0.3)] px-3 text-sm font-bold tracking-[8%] text-[rgba(255,255,255,0.6)] uppercase backdrop-blur"
                                    >
                                        {{ $photo->tag }}
                                    </div>
                                @endif
                            </div>

                        @elseif ($count === 2)
                            <div
                                class="flex flex-col justify-end col-span-1 row-span-1 {{ $index === 0 ? 'rounded-l-3xl' : 'rounded-r-3xl' }} cursor-pointer group overflow-hidden relative"
                                @click="lightbox = true; lightboxIdx = {{ $index }}"
                            >
                                <x-responsive-image
                                    :path="$photo->path"
                                    :alt="$photo->tag ?: $apartment->name ?: __('Apartment view')"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                                @if ($photo->tag ?? false)
                                    <div
                                        class="absolute bottom-0 left-0 mb-3 ml-4 w-fit rounded-xl border-[1px] border-[rgba(255,255,255,0.15)] bg-[rgba(0,0,0,0.3)] px-3 text-sm font-bold tracking-[8%] text-[rgba(255,255,255,0.6)] uppercase backdrop-blur"
                                    >
                                        {{ $photo->tag }}
                                    </div>
                                @endif
                            </div>

                        @elseif ($count === 3)
                            <div
                                class="flex flex-col justify-end col-span-1 row-span-1 {{ $index === 0 ? 'rounded-l-3xl' : ($index === 2 ? 'rounded-r-3xl' : '') }} cursor-pointer group overflow-hidden relative"
                                @click="lightbox = true; lightboxIdx = {{ $index }}"
                            >
                                <x-responsive-image
                                    :path="$photo->path"
                                    :alt="$photo->tag ?: $apartment->name ?: __('Apartment view')"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                                @if ($photo->tag ?? false)
                                    <div
                                        class="absolute bottom-0 left-0 mb-3 ml-4 w-fit rounded-xl border-[1px] border-[rgba(255,255,255,0.15)] bg-[rgba(0,0,0,0.3)] px-3 text-sm font-bold tracking-[8%] text-[rgba(255,255,255,0.6)] uppercase backdrop-blur"
                                    >
                                        {{ $photo->tag }}
                                    </div>
                                @endif
                            </div>

                        @elseif ($count === 4)
                            <div
                                class="flex flex-col justify-end {{ $index === 0 ? 'col-span-2 row-span-2 rounded-l-3xl' : 'col-span-1 row-span-1' }} {{ $index === 2 ? 'rounded-tr-3xl' : '' }} {{ $index === 3 ? 'rounded-br-3xl' : '' }} cursor-pointer group overflow-hidden relative"
                                @click="lightbox = true; lightboxIdx = {{ $index }}"
                            >
                                <x-responsive-image
                                    :path="$photo->path"
                                    :alt="$photo->tag ?: $apartment->name ?: __('Apartment view')"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                                @if ($photo->tag)
                                    <div
                                        class="absolute bottom-0 left-0 mb-3 ml-4 w-fit rounded-xl border-[1px] border-[rgba(255,255,255,0.15)] bg-[rgba(0,0,0,0.3)] px-3 text-sm font-bold tracking-[8%] text-[rgba(255,255,255,0.6)] uppercase backdrop-blur"
                                    >
                                        {{ $photo->tag }}
                                    </div>
                                @endif
                            </div>

                        @else
                            @if ($index === 0)
                                <div
                                    class="group relative col-span-2 row-span-2 flex cursor-pointer flex-col justify-end overflow-hidden rounded-l-3xl"
                                    @click="
                                        lightbox = true;
                                        lightboxIdx = 0;
                                    "
                                >
                                    <x-responsive-image
                                        :path="$photo->path"
                                        :alt="$photo->tag ?: $apartment->name ?: __('Apartment view')"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    />
                                    @if ($photo->tag)
                                        <div
                                            class="absolute bottom-0 left-0 mb-3 ml-4 w-fit rounded-xl border-[1px] border-[rgba(255,255,255,0.15)] bg-[rgba(0,0,0,0.3)] px-3 text-sm font-bold tracking-[8%] text-[rgba(255,255,255,0.6)] uppercase backdrop-blur"
                                        >
                                            {{ $photo->tag }}
                                        </div>
                                    @endif
                                </div>
                            @elseif ($index === 1 || $index === 2)
                                <div
                                    class="flex flex-col justify-end col-span-1 row-span-1 {{ $index === 2 ? 'rounded-tr-3xl' : '' }} cursor-pointer group overflow-hidden relative"
                                    @click="lightbox = true; lightboxIdx = {{ $index }}"
                                >
                                    <x-responsive-image
                                        :path="$photo->path"
                                        :alt="$photo->tag ?: $apartment->name ?: __('Apartment view')"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    />
                                    @if ($photo->tag)
                                        <div
                                            class="absolute bottom-0 left-0 mb-3 ml-4 w-fit rounded-xl border-[1px] border-[rgba(255,255,255,0.15)] bg-[rgba(0,0,0,0.3)] px-3 text-sm font-bold tracking-[8%] text-[rgba(255,255,255,0.6)] uppercase backdrop-blur"
                                        >
                                            {{ $photo->tag }}
                                        </div>
                                    @endif
                                </div>
                            @elseif ($index === 3)
                                <div
                                    class="group relative col-span-1 row-span-1 flex cursor-pointer flex-col justify-end overflow-hidden"
                                    @click="lightbox = true; lightboxIdx = {{ $index }}"
                                >
                                    <x-responsive-image
                                        :path="$photo->path"
                                        :alt="$photo->tag ?: $apartment->name ?: __('Apartment view')"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    />
                                    @if ($photo->tag ?? false)
                                        <div
                                            class="absolute bottom-0 left-0 mb-3 ml-4 w-fit rounded-xl border-[1px] border-[rgba(255,255,255,0.15)] bg-[rgba(0,0,0,0.3)] px-3 text-sm font-bold tracking-[8%] text-[rgba(255,255,255,0.6)] uppercase backdrop-blur"
                                        >
                                            {{ $photo->tag }}
                                        </div>
                                    @endif
                                </div>
                            @elseif ($index === 4)
                                <div
                                    class="group relative col-span-1 row-span-1 flex cursor-pointer flex-col justify-center overflow-hidden rounded-br-3xl"
                                    @click="lightbox = true; lightboxIdx = {{ $index }}"
                                >
                                    <x-responsive-image
                                        :path="$photo->path"
                                        :alt="__('More photos')"
                                        class="h-full w-full object-cover opacity-80 transition-transform duration-300 group-hover:scale-105"
                                    />
                                    @if ($galleryPhotos->count() - 5 > 0 ?? false)
                                        <div
                                            class="absolute inset-0 flex items-center justify-center bg-black/40 text-lg font-semibold text-white"
                                        >
                                            +{{ $galleryPhotos->count() - 5 }} {{ __('photos') }} →
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif
                    @empty
                        <div
                            class="group relative col-span-2 row-span-2 flex cursor-pointer flex-col justify-end overflow-hidden rounded-l-3xl"
                            @click="
                                lightbox = true;
                                lightboxIdx = 0;
                            "
                        >
                            <img
                                :src="images[0]"
                                :alt="tags[0] || apartmentName || '{{ __('Apartment view') }}'"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute bottom-0 left-0 mb-3 ml-4 w-fit rounded-xl border-[1px] border-[rgba(255,255,255,0.15)] bg-[rgba(0,0,0,0.3)] px-3 text-sm font-bold tracking-[8%] text-[rgba(255,255,255,0.6)] uppercase backdrop-blur"
                            >
                                {{ __('Apartment view') }}
                            </div>
                        </div>
                        <div
                            class="group relative col-span-1 row-span-1 flex cursor-pointer flex-col justify-end overflow-hidden"
                            @click="
                                lightbox = true;
                                lightboxIdx = 1;
                            "
                        >
                            <img
                                :src="images[1]"
                                :alt="tags[1] || apartmentName || '{{ __('Apartment view') }}'"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            />
                        </div>
                        <div
                            class="group relative col-span-1 row-span-1 flex cursor-pointer flex-col justify-end overflow-hidden rounded-tr-3xl"
                            @click="
                                lightbox = true;
                                lightboxIdx = 2;
                            "
                        >
                            <img
                                :src="images[2]"
                                :alt="tags[2] || apartmentName || '{{ __('Apartment view') }}'"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            />
                        </div>
                        <div
                            class="group relative col-span-1 row-span-1 flex cursor-pointer flex-col justify-end overflow-hidden"
                            @click="
                                lightbox = true;
                                lightboxIdx = 3;
                            "
                        >
                            <img
                                :src="images[3]"
                                :alt="tags[3] || apartmentName || '{{ __('Apartment view') }}'"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            />
                        </div>
                        <div
                            class="group relative col-span-1 row-span-1 flex cursor-pointer flex-col justify-center overflow-hidden rounded-br-3xl"
                            @click="
                                lightbox = true;
                                lightboxIdx = 4;
                            "
                        >
                            <img
                                :src="images[4]"
                                :alt="tags[4] || '{{ __('More photos') }}'"
                                class="h-full w-full object-cover opacity-80 transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 flex items-center justify-center bg-black/40 text-lg font-semibold text-white"
                            >
                                +{{ count($apartmentImages) }} {{ __('photos') }} →
                            </div>
                        </div>
                    @endforelse
                </div>

                <div
                    x-show="lightbox"
                    style="display: none"
                    class="fixed inset-0 z-[100] hidden flex-col items-center justify-center bg-black/95 backdrop-blur-xl md:flex"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @keydown.window.escape="lightbox = false"
                    @keydown.window.arrow-right="
                        if (lightbox)
                            lightboxIdx = (lightboxIdx + 1) % images.length;
                    "
                    @keydown.window.arrow-left="
                        if (lightbox)
                            lightboxIdx =
                                (lightboxIdx - 1 + images.length) %
                                images.length;
                    "
                    x-init="
                        $watch('lightbox', (val) => {
                            if (window.innerWidth >= 768)
                                document.body.style.overflow = val
                                    ? 'hidden'
                                    : '';
                        })
                    "
                >
                    <div
                        class="absolute top-0 right-0 left-0 z-50 flex items-center justify-between p-6"
                    >
                        <div
                            class="text-sm font-medium tracking-widest text-white/80 uppercase"
                        >
                            <span x-text="lightboxIdx + 1"></span> /
                            <span x-text="images.length"></span>
                        </div>

                        <span
                            class="text-xl font-bold tracking-wide text-white"
                            x-show="tags[lightboxIdx]"
                            x-text="tags[lightboxIdx]"
                        ></span>

                        <button
                            @click="lightbox = false"
                            class="focus:ring-teal rounded-full bg-white/10 p-3 text-white backdrop-blur-md transition-all duration-300 hover:bg-white/20 focus:ring-2 focus:outline-none"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <button
                        @click="
                            lightboxIdx =
                                (lightboxIdx - 1 + images.length) %
                                images.length
                        "
                        class="focus:ring-teal absolute top-1/2 left-8 z-50 -translate-y-1/2 rounded-full bg-white/10 p-4 text-white backdrop-blur-md transition-all duration-300 hover:bg-white/20 focus:ring-2 focus:outline-none"
                    >
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path></svg>
                    </button>

                    <button
                        @click="lightboxIdx = (lightboxIdx + 1) % images.length"
                        class="focus:ring-teal absolute top-1/2 right-8 z-50 -translate-y-1/2 rounded-full bg-white/10 p-4 text-white backdrop-blur-md transition-all duration-300 hover:bg-white/20 focus:ring-2 focus:outline-none"
                    >
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"></path></svg>
                    </button>

                    <div
                        class="relative mt-16 mb-24 flex w-full flex-1 items-center justify-center p-16"
                        @click.self="lightbox = false"
                    >
                        <img
                            :src="images[lightboxIdx]"
                            :alt="tags[lightboxIdx] || apartmentName || '{{ __('Apartment view') }}'"
                            class="max-h-[80vh] max-w-full rounded-lg object-contain shadow-2xl ring-1 ring-white/10"
                        />
                    </div>

                    <div
                        class="absolute bottom-0 left-0 z-40 w-full bg-linear-to-t from-black/90 via-black/60 to-transparent px-4 pt-12 pb-6"
                    >
                        <div
                            class="[&::-webkit-scrollbar]:hidden mx-auto flex w-full max-w-6xl touch-pan-x items-center justify-center gap-3 overflow-x-auto pt-1 pb-2 [-ms-overflow-style:none] [scrollbar-width:none]"
                        >
                            <template x-for="(img, idx) in images" :key="idx">
                                <button
                                    @click="
                                        lightboxIdx = idx;
                                        $el.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'nearest',
                                            inline: 'center',
                                        });
                                    "
                                    class="relative h-20 w-32 shrink-0 overflow-hidden rounded-lg transition-all duration-300 focus:outline-none"
                                    :class="lightboxIdx === idx
                                        ? 'ring-2 ring-teal scale-105 z-10 opacity-100'
                                        : 'ring-1 ring-white/20 opacity-40 hover:opacity-100 hover:scale-105'"
                                >
                                    <img
                                        :src="img"
                                        :alt="tags[idx] || apartmentName || '{{ __('Apartment view') }}'"
                                        class="h-full w-full object-cover"
                                    />
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($apartment?->type != null && $apartment->type === \App\Enums\ApartmentType::Vineyard)
        <section class="bg-spa w-full" id="spa">
            <div class="flex flex-col px-8 py-12 md:px-14 md:pt-14 md:pb-16">
                <p class="text-teal mb-1 text-xs font-bold tracking-[8%] uppercase md:mb-3">{{ __('Therme Laa') }}</p>
                <h6 class="font-serif text-4xl text-white">
                    {{ __('No excuses.') }}
                </h6>
                <h6 class="mb-3 font-serif text-4xl text-white">
                    {{ __('Thermals are 5 minutes away.') }}
                </h6>
                <p class="flex flex-col gap-1 text-sm text-[rgba(255,255,255,0.5)] md:text-base">
                    <span
                        class="text-purple bg-purplePale border-border flex flex-col justify-center rounded-xl border px-3 py-1 text-xs"
                    >
                        {{ $apartment->tags[0]['value'] ?? '' }}
                    </span>
                    {{ __('Swimming pools, saunas, relaxation zones and more!') }}
                </p>

                <div
                    class="mt-10 grid max-w-6xl grid-cols-1 gap-6 text-[rgba(255,255,255,0.72)]"
                >
                    <div
                        class="bg-[rgba(255,255,255,0.06);] flex flex-col gap-y-4 rounded-2xl border-[1px] border-[rgba(0,201,167,.2)] p-6 md:flex-row"
                    >
                        <span class="text-8xl"> 🛁 </span>
                        <div class="flex flex-col gap-2">
                            <div class="flex flex-col">
                                <p class="text-xl text-[rgba(255,255,255,0.85)]">
                                    {{ __('Spa & Stay package') }}
                                </p>
                                <span
                                    class="mb-3 text-sm text-[rgba(255,255,255,0.6)]"
                                >
                                    {{ __('2 nights + 2 admissions to Therme Laa + welcome set (vine, candles, towels). Ideal for couples\' getaway') }}
                                </span>
                                <a
                                    href="{{ route('reservation', ['locale' => app()->getLocale(), 'apartment' => $apartment->slug]) }}"
                                    class="btn-teal teal-shadow flex w-fit flex-col justify-center rounded-xl px-8 py-2 text-sm font-semibold transition-all duration-200 hover:-translate-y-1"
                                >
                                    {{ __('Book') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="flex flex-col px-8 py-10 pb-12 md:px-14" id="about">
            <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase md:mb-4">{{ __('About the apartment') }}</p>
            <h6 class="text-navy mb-2 font-serif text-3xl md:text-4xl">
                {{ __('Your wellness escape.') }}
            </h6>
            <span class="text-muted">
                {{ __('Apartment for 2 – 4 people in Laa an der Thaya. Therme Laa is 5 minutes away,') }}
                <br />
                {{ __('Vienna is an hour’s drive, and vineyards are visible from the window.') }}
            </span>
            <div class="mt-6 flex gap-4 bg-white">
                <div class="mt-1 mb-2 flex gap-2 px-2 text-center">
                    @foreach ($apartment->tags as $tag)
                        <span
                            class="text-purple bg-purplePale border-border flex flex-col justify-center rounded-2xl border-[1px] px-4 py-2 text-xs font-bold"
                        >
                            {{ $tag['value'] }}
                        </span>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <section class="flex flex-col px-8 py-10 pb-12 md:px-14" id="skiing">
            <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase md:mb-4">{{ __('About the apartment') }}</p>
            <h6 class="text-navy mb-2 font-serif text-3xl md:text-4xl">
                {{ __('Your base in the mountains.') }}
            </h6>
            <span class="text-muted">
                {{ __('Apartment for 2–6 people right in the Ramzová resort. The ski slope is right outside the door,') }}
                <br />
                {{ __('Priessnitz Spa is 13 km away; hiking trails start right at your doorstep.') }}
            </span>
            <div class="mt-6 flex gap-4 bg-white">
                <div class="mt-1 mb-2 flex gap-2 px-2 text-center">
                    @foreach ($apartment->tags as $tag)
                        <span
                            class="text-purple bg-purplePale border-border flex flex-col justify-center rounded-2xl border-[1px] px-4 py-2 text-xs font-bold"
                        >
                            {{ $tag['value'] }}
                        </span>
                    @endforeach
                </div>
            </div>
        </section>
        @if ($apartment->hikes && $apartment->hikes->isNotEmpty())
            <section class="bg-skiing w-full" id="hikes">
                <div
                    class="flex flex-col px-8 py-12 md:px-14 md:pt-14 md:pb-16"
                >
                    <div class="flex flex-row justify-between">
                        <p class="text-teal mb-1 text-xs font-bold tracking-[8%] uppercase md:mb-3">{{ __('Trails and skiing') }}</p>
                        <a
                            class="text-teal mb-1 text-xs font-bold tracking-[8%] uppercase hover:underline md:mb-3"
                            href={{ route('activities') }}
                            >{{ __('Learn more') }}</a
                        >
                    </div>
                    <h6 class="mb-3 font-serif text-4xl text-white">
                        {{ __('Selection of the best routes.') }}
                    </h6>
                    <p class="flex flex-col gap-1 text-sm text-[rgba(255,255,255,0.5)] md:text-base">
                        <span
                            class="text-purple bg-purplePale border-border flex flex-col justify-center rounded-xl border px-3 py-1 text-xs"
                        >
                            {{ $apartment->tags[0]['value'] ?? '' }}
                        </span>

                    <div
                        class="mt-10 grid grid-cols-1 gap-6 text-[rgba(255,255,255,0.72)] md:grid-cols-3"
                    >
                        @foreach ($apartment->hikes as $hike)
                            <div
                                class="flex flex-col rounded-xl border-[1px] border-[rgba(255,255,255,.1)] bg-[rgba(255,255,255,.06)] p-5"
                            >
                                <div
                                    class="flex gap-x-1 py-[2.5px] mb-3 px-3 w-fit rounded-2xl {{ $hike->difficulty->bgColor() }}"
                                >
                                    <span class="text-xxs">
                                        {{ $hike->difficulty->icon() }}
                                    </span>
                                    <span
                                        class="text-xxs font-bold uppercase {{ $hike->difficulty->textColor() }}"
                                    >
                                        {{ $hike->difficulty->label() }}
                                    </span>
                                </div>
                                <span class="mb-1 text-sm font-bold text-white">
                                    {{ $hike->name }}
                                </span>
                                <div
                                    class="text-xxs flex gap-x-3 font-semibold text-[rgba(255,255,255,.45)]"
                                >
                                    <span>
                                        {{ $hike->length }} {{ __('km') }}
                                    </span>
                                    @if ($hike->distance_tx)
                                        <span> {{ $hike->distance_tx }} </span>
                                    @endif
                                    @if ($hike->is_for_families)
                                        <span> {{ __('For families') }} </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @else
            <section class="bg-skiing w-full" id="about">
                <div
                    class="flex flex-col px-8 py-12 md:px-14 md:pt-14 md:pb-16"
                >
                    <div class="flex flex-row justify-between">
                        <p class="text-teal mb-1 text-xs font-bold tracking-[8%] uppercase md:mb-3">{{ __('Trails and skiing') }}</p>
                        <a
                            class="text-teal mb-1 text-xs font-bold tracking-[8%] uppercase hover:underline md:mb-3"
                            href={{ route('activities') }}
                            >{{ __('Learn more') }}</a
                        >
                    </div>
                    <h6 class="mb-3 font-serif text-4xl text-white">
                        {{ __('Selection of the best routes.') }}
                    </h6>
                    <p class="flex flex-col gap-1 text-sm text-[rgba(255,255,255,0.5)] md:text-base">
                        <span>
                            {{ __('Direct access to ski slopes, cross-country trails, and hiking trails. Filter by difficulty.') }}
                        </span>
                    </p>

                    <div
                        class="mt-10 grid grid-cols-1 gap-6 text-[rgba(255,255,255,0.72)] md:grid-cols-3"
                    >
                        <div
                            class="flex flex-col rounded-xl border-[1px] border-[rgba(255,255,255,.1)] bg-[rgba(255,255,255,.06)] p-5"
                        >
                            <div
                                class="mb-3 flex w-fit gap-x-1 rounded-2xl bg-[rgba(0,201,167,.15)] px-3 py-[2.5px]"
                            >
                                <span class="text-xxs"> 🟢 </span>
                                <span
                                    class="text-teal text-xxs font-bold uppercase"
                                >
                                    {{ __('Easy') }}
                                </span>
                            </div>
                            <span class="mb-1 text-sm font-bold text-white">
                                {{ __('Rejvíz Circuit') }}
                            </span>
                            <div
                                class="text-xxs flex gap-x-3 font-semibold text-[rgba(255,255,255,.45)]"
                            >
                                <span> {{ __('8 km') }} </span>
                                <span> {{ __('↑ 150 m') }} </span>
                                <span> {{ __('For families') }} </span>
                            </div>
                        </div>

                        <div
                            class="flex flex-col rounded-xl border-[1px] border-[rgba(255,255,255,.1)] bg-[rgba(255,255,255,.06)] p-5"
                        >
                            <div
                                class="mb-3 flex w-fit gap-x-1 rounded-2xl bg-[rgba(255,200,50,.12)] px-3 py-[2.5px]"
                            >
                                <span class="text-xxs"> 🟡 </span>
                                <span
                                    class="text-xxs font-bold text-[#ffd166] uppercase"
                                >
                                    {{ __('Medium') }}
                                </span>
                            </div>
                            <span class="mb-1 text-sm font-bold text-white">
                                {{ __('The Jeseníky Ridge') }}
                            </span>
                            <div
                                class="text-xxs flex gap-x-3 font-semibold text-[rgba(255,255,255,.45)]"
                            >
                                <span> {{ __('8 km') }} </span>
                                <span> {{ __('↑ 150 m') }} </span>
                                <span> {{ __('For families') }} </span>
                            </div>
                        </div>

                        <div
                            class="flex flex-col rounded-xl border-[1px] border-[rgba(255,255,255,.1)] bg-[rgba(255,255,255,.06)] p-5"
                        >
                            <div
                                class="mb-3 flex w-fit gap-x-1 rounded-2xl bg-[rgba(229,57,53,.12)] px-3 py-[2.5px]"
                            >
                                <span class="text-xxs"> 🔴 </span>
                                <span
                                    class="text-xxs font-bold text-[#ef9a9a] uppercase"
                                >
                                    {{ __('Hard') }}
                                </span>
                            </div>
                            <span class="mb-1 text-sm font-bold text-white">
                                {{ __('Praděd from Ramzová') }}
                            </span>
                            <div
                                class="text-xxs flex gap-x-3 font-semibold text-[rgba(255,255,255,.45)]"
                            >
                                <span> {{ __('8 km') }} </span>
                                <span> {{ __('↑ 150 m') }} </span>
                                <span> {{ __('For families') }} </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif

    <section
        class="flex scroll-mt-20 flex-col px-8 py-10 pb-12 md:px-14"
        id="nearby"
    >
        <div class="flex flex-row justify-between">
            <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase md:mb-4">{{ __('Nearby Area') }}</p>
            <a
                class="text-teal mb-1 text-xs font-bold tracking-[8%] uppercase hover:underline md:mb-3"
                href={{
                route('activities') }}
                >{{ __('Learn more') }}</a
            >
        </div>
        <h6 class="text-navy mb-2 font-serif text-3xl md:text-4xl">
            {{ __('Things worth seeing.') }}
        </h6>

        @if ($apartment->places && $apartment->places->count() > 0)
            <div
                class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-2"
                x-data="apartmentMap({{ Js::from($apartment->places) }})"
            >
                <div
                    class="[&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-border [&::-webkit-scrollbar-thumb]:rounded-full flex max-h-[500px] flex-col gap-4 overflow-y-auto pr-2"
                >
                    <script>
                        document.addEventListener("alpine:init", () => {
                            Alpine.data("apartmentMap", (places) => ({
                                map: null,
                                markers: {},
                                placesData: places,
                                colors: [
                                    "#ef4444",
                                    "#8bc34a",
                                    "#9c27b0",
                                    "#00bcd4",
                                    "#ff9800",
                                    "#2196f3",
                                ],
                                hoveredPlaceId: null,

                                async init() {
                                    if (
                                        !this.placesData ||
                                        this.placesData.length === 0
                                    ) {
                                        return;
                                    }

                                    this.renderMap();
                                },

                                async renderMap() {
                                    const placesArray = JSON.parse(
                                        JSON.stringify(this.placesData),
                                    );
                                    const { Map } =
                                        await google.maps.importLibrary("maps");

                                    const fallback =
                                        document.getElementById("map-fallback");
                                    if (fallback)
                                        fallback.style.display = "none";

                                    const firstLat = parseFloat(
                                        String(placesArray[0].latitude).replace(
                                            ",",
                                            ".",
                                        ),
                                    );
                                    const firstLng = parseFloat(
                                        String(
                                            placesArray[0].longitude,
                                        ).replace(",", "."),
                                    );

                                    this.map = new Map(
                                        document.getElementById("google-map"),
                                        {
                                            zoom: 13,
                                            center: {
                                                lat: firstLat,
                                                lng: firstLng,
                                            },
                                            disableDefaultUI: true,
                                            zoomControl: true,
                                            styles: [
                                                {
                                                    featureType: "poi",
                                                    elementType: "labels",
                                                    stylers: [
                                                        { visibility: "off" },
                                                    ],
                                                },
                                                {
                                                    featureType: "transit",
                                                    elementType: "labels",
                                                    stylers: [
                                                        { visibility: "off" },
                                                    ],
                                                },
                                            ],
                                        },
                                    );

                                    this.map.addListener("click", () => {
                                        Object.values(this.markers).forEach(
                                            (m) => m.setClicked(false),
                                        );
                                    });

                                    document
                                        .getElementById("google-map")
                                        .addEventListener(
                                            "marker-clicked",
                                            (e) => {
                                                const clickedId = e.detail.id;
                                                Object.values(
                                                    this.markers,
                                                ).forEach((m) =>
                                                    m.setClicked(false),
                                                );
                                                if (this.markers[clickedId]) {
                                                    this.markers[
                                                        clickedId
                                                    ].setClicked(true);
                                                }
                                            },
                                        );

                                    const component = this;

                                    class CustomHTMLMarker
                                        extends google.maps.OverlayView
                                    {
                                        constructor(position, place, color) {
                                            super();
                                            this.position = position;
                                            this.place = place;
                                            this.color = color;
                                            this.div = null;
                                            this.bubble = null;
                                            this.dot = null;
                                            this.isClicked = false;
                                            this.isHovered = false;
                                        }

                                        setHovered(show) {
                                            this.isHovered = show;
                                            this.updateState();
                                        }

                                        setClicked(show) {
                                            this.isClicked = show;
                                            this.updateState();
                                        }

                                        updateState() {
                                            if (
                                                !this.div ||
                                                !this.bubble ||
                                                !this.dot
                                            )
                                                return;

                                            this.div.style.zIndex =
                                                this.isClicked || this.isHovered
                                                    ? "20"
                                                    : "10";

                                            if (
                                                this.isClicked ||
                                                this.isHovered
                                            ) {
                                                this.bubble.style.opacity = "1";
                                                this.bubble.style.pointerEvents =
                                                    "auto";
                                                this.bubble.style.transform =
                                                    "translateX(-50%) translateY(-4px)";
                                                this.dot.style.transform =
                                                    "scale(1.3)";
                                            } else {
                                                this.bubble.style.opacity = "0";
                                                this.bubble.style.pointerEvents =
                                                    "none";
                                                this.bubble.style.transform =
                                                    "translateX(-50%) translateY(0)";
                                                this.dot.style.transform =
                                                    "scale(1)";
                                            }
                                        }

                                        onAdd() {
                                            this.div =
                                                document.createElement("div");
                                            this.div.style.position =
                                                "absolute";
                                            this.div.style.cursor = "pointer";
                                            this.div.style.transform =
                                                "translate(-50%, -50%)";
                                            this.div.style.zIndex = "10";

                                            this.dot =
                                                document.createElement("div");
                                            this.dot.style.cssText = `width: 16px; height: 16px; background-color: ${this.color}; border: 2.5px solid white; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.3); transition: transform 0.3s ease; position: relative; z-index: 2;`;

                                            this.bubble =
                                                document.createElement("div");
                                            this.bubble.style.cssText =
                                                "position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%) translateY(0); margin-bottom: 6px; opacity: 0; pointer-events: none; transition: opacity 0.8s ease, transform 0.3s ease; z-index: 3; display: flex; flex-direction: column; align-items: center;";

                                            this.bubble.innerHTML = `
                                                <div style="background: white; padding: 6px 14px; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-weight: bold; color: #1e1e38; display: flex; gap: 8px; align-items: center; border: 1px solid rgba(0,0,0,0.05); white-space: nowrap; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                                    <span style="font-size: 18px;">${this.place.icon || ""}</span>
                                                    <span style="font-size: 14px;">${this.place.name || ""}</span>
                                                </div>
                                                <div style="width: 0; height: 0; border-left: 6px solid transparent; border-right: 6px solid transparent; border-top: 8px solid white; margin-top: -1px; transform: scaleY(1.3); transform-origin: top;"></div>
                                            `;

                                            this.div.appendChild(this.bubble);
                                            this.div.appendChild(this.dot);

                                            this.div.addEventListener(
                                                "mouseenter",
                                                () => {
                                                    this.setHovered(true);
                                                    component.hoveredPlaceId =
                                                        this.place.id;
                                                },
                                            );

                                            this.div.addEventListener(
                                                "mouseleave",
                                                () => {
                                                    this.setHovered(false);
                                                    component.hoveredPlaceId =
                                                        null;
                                                },
                                            );

                                            this.div.addEventListener(
                                                "click",
                                                (e) => {
                                                    e.stopPropagation();
                                                    const currentClickedState =
                                                        this.isClicked;
                                                    this.div.dispatchEvent(
                                                        new CustomEvent(
                                                            "marker-clicked",
                                                            {
                                                                detail: {
                                                                    id: this
                                                                        .place
                                                                        .id,
                                                                },
                                                                bubbles: true,
                                                            },
                                                        ),
                                                    );

                                                    if (currentClickedState) {
                                                        this.setClicked(false);
                                                    }
                                                },
                                            );

                                            const panes = this.getPanes();
                                            panes.overlayMouseTarget.appendChild(
                                                this.div,
                                            );

                                            this.updateState();
                                        }
                                        draw() {
                                            const overlayProjection =
                                                this.getProjection();
                                            const pos =
                                                overlayProjection.fromLatLngToDivPixel(
                                                    new google.maps.LatLng(
                                                        this.position.lat,
                                                        this.position.lng,
                                                    ),
                                                );
                                            if (this.div) {
                                                this.div.style.left =
                                                    pos.x + "px";
                                                this.div.style.top =
                                                    pos.y + "px";
                                            }
                                        }
                                        onRemove() {
                                            if (this.div) {
                                                this.div.parentNode.removeChild(
                                                    this.div,
                                                );
                                                this.div = null;
                                            }
                                        }
                                    }

                                    const bounds =
                                        new google.maps.LatLngBounds();

                                    placesArray.forEach((place, index) => {
                                        const lat = parseFloat(
                                            String(place.latitude).replace(
                                                ",",
                                                ".",
                                            ),
                                        );
                                        const lng = parseFloat(
                                            String(place.longitude).replace(
                                                ",",
                                                ".",
                                            ),
                                        );

                                        if (isNaN(lat) || isNaN(lng)) return;

                                        const position = { lat: lat, lng: lng };
                                        bounds.extend(position);

                                        const colorIndex =
                                            index % this.colors.length;
                                        const color = this.colors[colorIndex];

                                        const marker = new CustomHTMLMarker(
                                            position,
                                            place,
                                            color,
                                        );
                                        marker.setMap(this.map);
                                        this.markers[place.id] = marker;
                                    });

                                    if (placesArray.length > 1) {
                                        this.map.fitBounds(bounds);
                                        const listener =
                                            google.maps.event.addListener(
                                                this.map,
                                                "idle",
                                                () => {
                                                    if (this.map.getZoom() > 15)
                                                        this.map.setZoom(15);
                                                    google.maps.event.removeListener(
                                                        listener,
                                                    );
                                                },
                                            );
                                    }
                                },

                                toggleMarkerHover(id, show) {
                                    if (this.markers[id]) {
                                        this.markers[id].setHovered(show);
                                    }
                                },

                                focusMarker(id, lat, lng) {
                                    if (this.map && lat && lng) {
                                        const cleanLat = parseFloat(
                                            String(lat).replace(",", "."),
                                        );
                                        const cleanLng = parseFloat(
                                            String(lng).replace(",", "."),
                                        );
                                        this.map.panTo({
                                            lat: cleanLat,
                                            lng: cleanLng,
                                        });
                                        this.map.setZoom(16);

                                        Object.values(this.markers).forEach(
                                            (m) => m.setClicked(false),
                                        );
                                        if (this.markers[id]) {
                                            this.markers[id].setClicked(true);
                                        }
                                    }
                                },
                            }));
                        });
                    </script>
                    <script>
                        ((g) => {
                            var h,
                                a,
                                k,
                                p = "The Google Maps JavaScript API",
                                c = "google",
                                l = "importLibrary",
                                q = "__ib__",
                                m = document,
                                b = window;
                            b = b[c] || (b[c] = {});
                            var d = b.maps || (b.maps = {}),
                                r = new Set(),
                                e = new URLSearchParams(),
                                u = () =>
                                    h ||
                                    (h = new Promise(async (f, n) => {
                                        await (a = m.createElement("script"));
                                        e.set("libraries", [...r] + "");
                                        for (k in g)
                                            e.set(
                                                k.replace(/[A-Z]/g, (t) => "_" + t[0].toLowerCase()),
                                                g[k],
                                            );
                                        e.set("callback", c + ".maps." + q);
                                        a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                                        d[q] = f;
                                        a.onerror = () => (h = n(Error(p + " could not load.")));
                                        a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                                        m.head.append(a);
                                    }));
                            d[l]
                                ? console.warn(p + " only loads once. Ignoring:", g)
                                : (d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)));
                        })({
                            key: "{{ config('services.google_maps.key') }}",
                            v: "weekly",
                        });
                    </script>

                    @foreach ($apartment->places as $place)
                        <a
                            href="{{ $place->url ?? '#' }}"
                            {{ $place->url ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                            @click.prevent="focusMarker({{ $place->id }}, {{ $place->latitude }}, {{ $place->longitude }})"
                            @mouseover="toggleMarkerHover({{ $place->id }}, true)"
                            @mouseout="toggleMarkerHover({{ $place->id }}, false)"
                            :class="hoveredPlaceId === {{ $place->id }} ? 'border-purple shadow-md' : 'border-border'"
                            class="hover:border-purple group flex cursor-pointer gap-3 rounded-xl border-[1px] bg-white px-6 py-4 transition-all duration-200 hover:shadow-md"
                        >
                            <span
                                :class="hoveredPlaceId === {{ $place->id }} ? 'scale-110' : ''"
                                class="my-auto text-3xl transition-transform duration-200 group-hover:scale-110"
                            >
                                {{ $place->icon }}
                            </span>
                            <div class="flex flex-col justify-center">
                                <p class="text-navy ml-1 text-sm font-bold">
                                    {{ $place->name }}
                                </p>
                                <span class="text-muted mt-0.5 text-xs">
                                    {{ $place->distance_text }}
                                </span>
                                @if ($place->description)
                                    <p class="text-muted mt-1.5 ml-1 line-clamp-2 text-xs">
                                        {{ $place->description }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div
                    class="bg-purplePale border-border relative h-[400px] w-full overflow-hidden rounded-2xl border shadow-inner lg:h-[500px]"
                    wire:ignore
                >
                    <div id="google-map" class="h-full w-full"></div>

                    <div
                        id="map-fallback"
                        class="text-navy bg-purplePale absolute inset-0 flex items-center justify-center p-6 text-center font-semibold"
                        style="display: none"
                    >
                        {{ __('Map will be displayed here once the Google Maps API key is configured.') }}
                    </div>
                </div>
            </div>
        @else
            <div class="text-muted mt-4 text-sm">
                {{ __('No places added yet.') }}
            </div>
        @endif
    </section>

    @if ($apartment->packages->isNotEmpty())
        <section
            class="bg-purpleGhost flex flex-col px-8 py-10 md:px-14 md:pt-10 md:pb-12"
            id="packages"
        >
            <div class="flex flex-row justify-between">
                <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase md:mb-4">{{ __('Vacation packages') }}</p>
                <a
                    class="text-teal mb-1 text-xs font-bold tracking-[8%] uppercase hover:underline md:mb-3"
                    href={{
                    route('packages') }}
                    >{{ __('Learn more') }}</a
                >
            </div>
            <h6 class="text-navy mb-2 font-serif text-3xl md:text-4xl">
                {{ __('Try a different approach.') }}
            </h6>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3">
                @foreach ($apartment->packages as $package)
                    <div
                        class="border-border flex flex-col rounded-2xl border bg-white p-6"
                    >
                        <span class="text-3xl"> {{ $package->icon }} </span>
                        <span class="text-navy text-smm mt-2 font-bold">
                            {{ $package->name }}
                        </span>
                        <div class="text-muted mt-1 text-sm">
                            @foreach ($package->translated_features as $feature)
                                <p>
                                    <span>✓</span>
                                    <span> {{ $feature }} </span>
                                </p>
                            @endforeach
                        </div>
                        <span class="text-tealD mt-3 text-xs font-bold">
                            {{ __('from') }} {{ number_format($package->price, 0, ',', ' ') }} {{ __('CZK per night') }}
                        </span>
                        <a
                            href="#"
                            class="text-teal mt-2 text-sm font-bold hover:underline"
                        >
                            {{ __('Book now') }}
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if ($apartment->places->isNotEmpty())
        <section class="flex flex-col px-8 py-10 pb-12 md:px-14" id="places">
            <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase md:mb-4">{{ __('Host\'s Tips') }}</p>
            <h6 class="text-navy mb-5 font-serif text-3xl md:text-4xl">
                {{ __('Hidden secrets that you\'ll love.') }}
            </h6>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3">
                @foreach ($apartment->places->take(3) as $place)
                    <{{ $place->url ? 'a' : 'div' }}
                        {!! $place->url ? 'href="' . $place->url . '" target="_blank" rel="noopener noreferrer"' : '' !!}
                        class="border-border card-shadow block h-full w-full rounded-2xl border-[1.5px] bg-white shadow-md transition-all duration-200 hover:-translate-y-1 hover:cursor-pointer hover:shadow-lg"
                    >
                        <div
                            class="relative flex h-32 w-full flex-col justify-end overflow-hidden rounded-t-2xl"
                        >
                            <div
                                class="apt-slides flex h-full transition-transform duration-700"
                            >
                                <div
                                    class="apt-slide relative h-full min-w-full"
                                >
                                    <x-responsive-image
                                        :path="$place->image"
                                        :alt="__($place->name)"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 px-5 pt-2 pb-5">
                            <p class="text-navy text-sm font-bold">
                                {{ __($place->name) }}
                            </p>
                            <span class="text-muted text-xs">
                                {{ __($place->description) }}
                            </span>
                        </div>
                    </{{ $place->url ? 'a' : 'div' }}>
                @endforeach
            </div>
        </section>
    @endif

    <section class="bg-review w-full" id="reviews">
        <div class="flex flex-col px-8 py-12 md:px-14 md:pt-14 md:pb-16">
            <p class="text-teal mb-1 text-xs font-bold tracking-[8%] uppercase md:mb-3">{{ __('Guest reviews') }}</p>
            <h6 class="mb-2 font-serif text-4xl text-white md:text-5xl">
                {{ __('What guests say.') }}
            </h6>
            <p class="flex gap-2 text-sm text-[rgba(255,255,255,0.5)] md:text-base">
                <span> {{ __('Over 9.8 on Booking.com') }} </span>
                <span> · </span>
                <span> {{ __('Over 150 reviews') }} </span>
            </p>

            <div
                class="mt-10 grid grid-cols-1 gap-6 text-[rgba(255,255,255,0.72)] sm:grid-cols-2 md:grid-cols-3"
            >
                <div
                    class="bg-[rgba(255,255,255,0.06);] flex flex-col gap-y-4 rounded-2xl border-[1px] border-[rgba(255,255,255,0.1)] p-6"
                >
                    <p class="text-xxs">⭐⭐⭐⭐⭐</p>
                    <p class="text-[15px] italic">„{{ __('Perfect ski base. We were on the slope in 3 minutes. Warm apartment and wine in the evening.') }}”</p>
                    <div class="flex gap-3">
                        <div
                            class="bg-teal my-auto flex h-9 w-9 items-center justify-center rounded-full font-bold text-white"
                        >
                            K
                        </div>
                        <div class="flex flex-col">
                            <p class="text-[rgba(255,255,255,0.85)]">{{ __('Kateřina M.') }}</p>
                            <div
                                class="flex gap-1 text-xs text-[rgba(255,255,255,0.35)]"
                            >
                                <span> {{ __('Brno') }} </span>
                                <span> · </span>
                                <span> {{ __('Booking.com') }} </span>
                                <span> · ⭐ 9.8 </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-[rgba(255,255,255,0.06);] flex flex-col gap-y-4 rounded-2xl border-[1px] border-[rgba(255,255,255,0.1)] p-6"
                >
                    <p class="text-xxs">⭐⭐⭐⭐⭐</p>
                    <p class="text-[15px] italic">„{{ __('Perfect ski base. We were on the slope in 3 minutes. Warm apartment and wine in the evening.') }}”</p>
                    <div class="flex gap-3">
                        <div
                            class="bg-teal my-auto flex h-9 w-9 items-center justify-center rounded-full font-bold text-white"
                        >
                            K
                        </div>
                        <div class="flex flex-col">
                            <p class="text-[rgba(255,255,255,0.85)]">{{ __('Kateřina M.') }}</p>
                            <div
                                class="flex gap-1 text-xs text-[rgba(255,255,255,0.35)]"
                            >
                                <span> {{ __('Brno') }} </span>
                                <span> · </span>
                                <span> {{ __('Booking.com') }} </span>
                                <span> · ⭐ 9.8 </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-[rgba(255,255,255,0.06);] flex flex-col gap-y-4 rounded-2xl border-[1px] border-[rgba(255,255,255,0.1)] p-6"
                >
                    <p class="text-xxs">⭐⭐⭐⭐⭐</p>
                    <p class="text-[15px] italic">„{{ __('Perfect ski base. We were on the slope in 3 minutes. Warm apartment and wine in the evening.') }}”</p>
                    <div class="flex gap-3">
                        <div
                            class="bg-teal my-auto flex h-9 w-9 items-center justify-center rounded-full font-bold text-white"
                        >
                            K
                        </div>
                        <div class="flex flex-col">
                            <p class="text-[rgba(255,255,255,0.85)]">{{ __('Kateřina M.') }}</p>
                            <div
                                class="flex gap-1 text-xs text-[rgba(255,255,255,0.35)]"
                            >
                                <span> {{ __('Brno') }} </span>
                                <span> · </span>
                                <span> {{ __('Booking.com') }} </span>
                                <span> · ⭐ 9.8 </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section
        class="bg-purplePale rounded-t-lg px-8 py-12 md:px-14 md:py-16"
        id="cta"
    >
        <div
            class="mx-auto flex flex-col justify-between gap-y-4 md:flex-row md:items-center"
        >
            <div>
                <h2 class="text-navy font-serif text-3xl font-bold md:text-4xl">
                    {{ __('Book before') }}<br />{{ __('your neighbor does.') }}
                </h2>
                <p class="text-muted mt-2">{{ __('Directly with us – no commission,') }}<br />
                {{ __('with a personal touch.') }}</p>
            </div>
            <div class="cta-btns flex gap-4">
                <a
                    href="{{ route('reservation', ['locale' => app()->getLocale(), 'apartment' => $apartment->slug]) }}"
                    class="btn-teal teal-shadow rounded-xl px-5 pt-2 pb-1 text-sm font-normal transition-all duration-200 hover:-translate-y-1 sm:px-7 sm:py-2 sm:text-base sm:font-bold md:py-3 lg:px-10"
                    >{{
                    __('Book') }}</a
                >
                <a
                    href="#"
                    class="btn-outline-purple rounded-xl px-3 py-1 font-normal transition-all duration-200 hover:-translate-y-1 sm:px-4 sm:py-2 sm:font-semibold md:px-6 md:py-3"
                    >{{
                    __('Find stay') }}</a
                >
            </div>
        </div>
    </section>
</x-app-layout>
