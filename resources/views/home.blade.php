<x-app-layout>
    <!-- Hero Section -->
    <section class="relative h-[94vh] min-h-130 overflow-hidden">
        <div
            x-data="{ idx: 0, slides: {{ Js::from($heroSlides) }} }"
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
                            class="apt-slide-overlay absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent"
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
                                        href="{{ route('reservation', ['locale' => app()->getLocale()]) }}"
                                        class="btn-teal teal-shadow flex flex-col justify-center rounded-xl px-4 py-2 font-semibold transition-all duration-200 hover:-translate-y-1 md:hidden"
                                        >{{
                                        __('Book') }}</a
                                    >
                                    <a
                                        href="{{ route('reservation', ['locale' => app()->getLocale()]) }}"
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
                    class="pointer-events-auto flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition-all duration-300 hover:cursor-pointer hover:bg-white/20"
                    @click="idx = (idx - 1 + slides.length) % slides.length"
                >
                    ‹
                </button>
                <button
                    class="pointer-events-auto flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition-all duration-300 hover:cursor-pointer hover:bg-white/20"
                    @click="idx = (idx + 1) % slides.length"
                >
                    ›
                </button>
            </div>
        </div>
    </section>

    <!-- Reservation Widget -->
    <livewire:reservation-widget id="reservation" />

    <!-- Marquee -->
    <section
        class="bg-purplePale border-border mb-10 overflow-hidden rounded-md border-t border-b py-3"
        id="marquee"
    >
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
        <div
            class="flex items-center whitespace-nowrap"
            style="animation: marquee 26s linear infinite"
        >
            <span
                class="text-purple px-7 text-sm font-medium"
                >{{ __('Wellness & sauna') }}</span
            >
            <span
                class="text-teal px-7 text-sm font-medium"
                >{{ __('Free parking') }}</span
            >
            <span
                class="text-muted px-7 text-sm font-medium"
                >{{ __('Only 3 weekends left in Feb') }}</span
            >
            <span
                class="text-purple px-7 text-sm font-medium"
                >{{ __('Pet friendly') }}</span
            >
            <span
                class="text-teal px-7 text-sm font-medium"
                >{{ __('Direct booking saves fees') }}</span
            >
            <span
                class="text-purple px-7 text-sm font-medium"
                >{{ __('Wellness & sauna') }}</span
            >
            <span
                class="text-teal px-7 text-sm font-medium"
                >{{ __('Free parking') }}</span
            >
            <span
                class="text-muted px-7 text-sm font-medium"
                >{{ __('Only 3 weekends left in Feb') }}</span
            >
            <span
                class="text-purple px-7 text-sm font-medium"
                >{{ __('Pet friendly') }}</span
            >
            <span
                class="text-teal px-7 text-sm font-medium"
                >{{ __('Direct booking saves fees') }}</span
            >
        </div>
    </section>

    <!-- Apartment Selection Section -->
    <section class="flex flex-col gap-2 px-8 md:px-14" id="apartments">
        <h5 class="text-teal text-xs font-bold tracking-[8%] uppercase">
            {{ __('Select your stay') }}
        </h5>
        <h4 class="text-navy font-serif text-3xl">
            {{ __('Two destinations, one apartment for you') }}
        </h4>
        <p class="sm:text-smm text-muted text-sm">{{ __('Every place has its own soul - choose yours.') }}</p>
        <div
            class="mt-2 grid grid-cols-1 gap-6 md:mt-5 md:grid-cols-2 md:gap-10"
        >
            @foreach ($apartments as $apartment)
                <div
                    class="border-border card-shadow h-full w-full rounded-2xl border-[1.5px] bg-white shadow-md transition-all duration-200 hover:-translate-y-1 hover:shadow-lg"
                >
                    <div
                        x-data="{ idx: 0, count: {{ $apartment->photosMain->count() }} }"
                        class="relative flex h-60 w-full flex-col justify-end overflow-hidden rounded-t-2xl"
                    >
                        <div
                            class="apt-slides flex h-full transition-transform duration-700"
                            :style="`transform:translateX(-${idx * 100}%);`"
                        >
                            @foreach ($apartment->photosMain as $photo)
                                <div
                                    class="apt-slide relative h-full min-w-full"
                                >
                                    <x-responsive-image
                                        :path="$photo->path"
                                        :alt="$photo->tag ?: $apartment->name ?: __('Apartment view')"
                                        class="h-full w-full object-cover"
                                    />

                                    @if ($photo->tag)
                                        <div
                                            class="absolute bottom-0 left-0 mb-3 ml-4 w-fit rounded-xl border-[1px] border-[rgba(255,255,255,0.15)] bg-[rgba(0,0,0,0.3)] px-3 text-sm font-bold tracking-[8%] text-[rgba(255,255,255,0.6)] uppercase backdrop-blur"
                                        >
                                            {{ $photo->tag }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="absolute right-4 bottom-3 z-20 flex gap-1">
                            @foreach ($apartment->photosMain as $index => $photo)
                                <div
                                    :class="[
                                                    'transition-all duration-300 cursor-pointer',
                                                    idx === {{ $index }}
                                                        ? 'bg-teal w-6 h-2 rounded-full'
                                                        : 'bg-teal/60 w-2 h-2 rounded-full opacity-60'
                                                ]"
                                    @click="idx = {{ $index }}"
                                ></div>
                            @endforeach
                        </div>

                        <div
                            class="c-arrow prev absolute top-1/2 left-4 z-20 flex h-3.5 w-3.5 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full bg-white/10 text-base text-white transition-all duration-300 hover:bg-white/20"
                            @click="
                                idx = count > 0 ? (idx - 1 + count) % count : 0
                            "
                        >
                            ‹
                        </div>
                        <div
                            class="c-arrow next absolute top-1/2 right-4 z-20 flex h-3.5 w-3.5 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full bg-white/10 text-base text-white transition-all duration-300 hover:bg-white/20"
                            @click="idx = count > 0 ? (idx + 1) % count : 0"
                        >
                            ›
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 px-5 py-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="bg-purple flex h-9 w-9 items-center justify-center rounded-full font-bold text-white"
                            >
                                S
                            </div>
                            <div class="flex flex-col">
                                <div class="flex gap-2">
                                    <div
                                        class="text-teal text-xs font-bold uppercase"
                                    >
                                        {{ __($apartment->address) }}
                                    </div>
                                </div>
                                <div class="text-navy font-serif text-lg">
                                    {{ $apartment->name }}
                                </div>
                            </div>
                        </div>
                        <p class="text-muted px-1 text-sm">{{ $apartment->description }}</p>
                        <div class="mt-1 mb-2 flex gap-2 px-2">
                            @foreach ($apartment->tags as $tag)
                                <span
                                    class="text-purple bg-purplePale border-border flex flex-col justify-center rounded-xl border px-3 py-1 text-xs"
                                >
                                    {{ $tag['value'] }}
                                </span>
                            @endforeach
                        </div>
                        <a
                            href="{{ route('apartments.show', $apartment->slug) }}"
                            class="btn-teal teal-shadow ml-2 w-fit rounded-xl px-4 py-1.5 text-sm font-bold transition-all duration-200 hover:translate-x-1"
                        >
                            {{ __('Explore apartment') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Gallery Section -->
    <section
        class="flex flex-col gap-4 rounded-t-lg p-8 md:px-14 md:py-12 md:pb-14"
        id="gallery"
    >
        <h5 class="text-navy font-serif text-2xl">{{ __('Photo gallery') }}</h5>

        <div
            x-data="{
                lightbox: false,
                lightboxIdx: 0,
                images: {{ $galleryImages->count() > 0 ? Js::from($galleryImages->map(fn($p) => url('/img') . '?path=' . urlencode($p->path) . '&w=1200')->values()) : Js::from($apartmentImages) }},
                apartmentName: {{ Js::from($apartment->name ?? '') }},
                tags: {{ $galleryImages->count() > 0 ? Js::from($galleryImages->map(fn($p) => $p->tag ?? '')->values()) : Js::from(array_fill(0, count($apartmentImages), '')) }}
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
                $count = $galleryImages->count();
            @endphp

            <div
                class="hidden md:grid gap-3 h-80 w-full
                @if($count === 1) grid-cols-1 grid-rows-1
                @elseif($count === 2) grid-cols-2 grid-rows-1
                @elseif($count === 3) grid-cols-3 grid-rows-1
                @elseif($count === 4) grid-cols-4 grid-rows-2
                @else grid-cols-4 grid-rows-2 @endif"
            >
                @forelse ($galleryImages->take(5) as $index => $photo)
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
                            @if ($photo->tag)
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
                            @if ($photo->tag)
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
                            @if ($photo->tag)
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
                                @if ($photo->tag)
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
                                @if ($galleryImages->count() - 5 > 0)
                                    <div
                                        class="absolute inset-0 flex items-center justify-center bg-black/40 text-lg font-semibold text-white"
                                    >
                                        +{{ $galleryImages->count() - 5 }} {{ __('photos') }} →
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
                            +{{ count($apartmentImages) - 5 }} {{ __('photos') }} →
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
                            (lightboxIdx - 1 + images.length) % images.length;
                "
                x-init="
                    $watch('lightbox', (val) => {
                        if (window.innerWidth >= 768)
                            document.body.style.overflow = val ? 'hidden' : '';
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
                            (lightboxIdx - 1 + images.length) % images.length
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

    <!-- Features Section -->
    <section
        class="bg-gray border-border flex w-full flex-col gap-5 border-y pt-8 pb-8 md:pt-10 md:pb-14"
        id="features"
    >
        <p class="text-teal mx-auto mb-0 align-bottom text-xs font-bold tracking-[8%] uppercase md:mb-4">{{ __('Ideal
            for') }}</p>
        <div
            class="grid grid-cols-2 px-8 md:grid-cols-4 md:px-14 lg:grid-cols-8"
        >
            <div
                class="border-border hover:bg-purplePale flex flex-col justify-center rounded-lg border-r-[1px] px-3 pt-4 pb-4 text-center transition-colors duration-300"
            >
                <span class="text-[28px]">👫</span>
                <span
                    class="text-navy text-xs font-semibold"
                    >{{ __('Couples') }}</span
                >
            </div>

            <div
                class="border-border hover:bg-purplePale flex flex-col justify-center rounded-lg px-3 pt-4 pb-4 text-center transition-colors duration-300 md:border-r-[1px]"
            >
                <span class="text-[28px]">👩‍🦰🐕</span>
                <span
                    class="text-navy text-xs font-semibold"
                    >{{ __('Dog owners') }}</span
                >
            </div>

            <div
                class="border-border hover:bg-purplePale flex flex-col justify-center rounded-lg border-r-[1px] px-3 pt-4 pb-4 text-center transition-colors duration-300"
            >
                <span class="text-[28px]">👨‍👩‍👧</span>
                <span
                    class="text-navy text-xs font-semibold"
                    >{{ __('Families with children') }}</span
                >
            </div>

            <div
                class="border-border hover:bg-purplePale flex flex-col justify-center rounded-lg px-3 pt-4 pb-4 text-center transition-colors duration-300 lg:border-r-[1px]"
            >
                <span class="text-[28px]">💻</span>
                <span
                    class="text-navy text-xs font-semibold"
                    >{{ __('Remote work') }}</span
                >
            </div>

            <div
                class="border-border hover:bg-purplePale flex flex-col justify-center rounded-lg border-r-[1px] px-3 pt-4 pb-4 text-center transition-colors duration-300"
            >
                <span class="text-[28px]">🏃</span>
                <span
                    class="text-navy text-xs font-semibold"
                    >{{ __('Hiking and trails') }}</span
                >
            </div>

            <div
                class="border-border hover:bg-purplePale flex flex-col justify-center rounded-lg px-3 pt-4 pb-4 text-center transition-colors duration-300 md:border-r-[1px]"
            >
                <span class="text-[28px]">⛷</span>
                <span
                    class="text-navy text-xs font-semibold"
                    >{{ __('Skiing') }}</span
                >
            </div>

            <div
                class="border-border hover:bg-purplePale flex flex-col justify-center rounded-lg border-r-[1px] px-3 pt-4 pb-4 text-center transition-colors duration-300"
            >
                <span class="text-[28px]">🧘</span>
                <span
                    class="text-navy text-xs font-semibold"
                    >{{ __('Wellness stay') }}</span
                >
            </div>

            <div
                class="hover:bg-purplePale flex flex-col justify-center rounded-lg px-3 pt-4 pb-4 text-center transition-colors duration-300"
            >
                <span class="text-[28px]">🏢</span>
                <span
                    class="text-navy text-xs font-semibold"
                    >{{ __('Company retreat') }}</span
                >
            </div>
        </div>
    </section>

    <!-- More Features Section -->
    <section class="flex flex-col px-8 py-10 pb-12 md:px-14" id="features2">
        <p class="text-teal mb-2 text-xs font-bold tracking-[8%] uppercase md:mb-4">{{ __('Why Stratos?') }}</p>
        <h6 class="text-navy font-serif text-3xl md:text-4xl">
            {{ __('Yes, we have a bed too. But that is the least interesting part.') }}
        </h6>
        <div
            class="mt-6 grid grid-cols-1 gap-6 bg-white sm:mt-9 sm:grid-cols-2 md:mt-12 md:grid-cols-3"
        >
            <div
                class="bg-cream border-border hover:border-purple flex flex-col rounded-2xl border-[1px] p-6 py-8 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg"
            >
                <span class="text-25xl">🍳</span>
                <p class="text-navy my-2 font-bold">{{ __('Own kitchen') }}</p>
                <p class="text-text">{{ __('Breakfast when you want. Reception won\'t wake you up, because there isn\'t one.') }}</p>
            </div>

            <div
                class="bg-cream border-border hover:border-purple flex flex-col rounded-2xl border-[1px] p-6 py-8 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg"
            >
                <span class="text-25xl">🐾</span>
                <p class="text-navy my-2 font-bold">{{ __('Dog friendly') }}</p>
                <p class="text-text">{{ __('Your dog is as welcome as you are. No surcharges, no compromises.') }}</p>
            </div>

            <div
                class="bg-cream border-border hover:border-purple flex flex-col rounded-2xl border-[1px] p-6 py-8 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg"
            >
                <span class="text-25xl">🚗</span>
                <p class="text-navy my-2 font-bold">{{ __('Free parking') }}</p>
                <p class="text-text">{{ __('You park your car. And your worries.') }}</p>
            </div>

            <div
                class="bg-cream border-border hover:border-purple flex flex-col rounded-2xl border-[1px] p-6 py-8 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg"
            >
                <span class="text-25xl">📶</span>
                <p class="text-navy my-2 font-bold">{{ __('Fast WiFi + Netflix') }}</p>
                <p class="text-text">{{ __('We have Netflix. But outside is better.') }}</p>
            </div>

            <div
                class="bg-cream border-border hover:border-purple flex flex-col rounded-2xl border-[1px] p-6 py-8 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg"
            >
                <span class="text-25xl">💰</span>
                <p class="text-navy my-2 font-bold">{{ __('Better price than a hotel') }}</p>
                <p class="text-text">
                    {{ __('Whole apartment just for you, for the price of a hotel room for two.') }}
                </p>
            </div>

            <div
                class="bg-cream border-border hover:border-purple flex flex-col rounded-2xl border-[1px] p-6 py-8 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg"
            >
                <span class="text-25xl">🏠</span>
                <p class="text-navy my-2 font-bold">{{ __('Personal approach') }}</p>
                <p class="text-text">
                    {{ __('We are not a chain. You get tips from the host, not a laminated flyer.') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Review Section -->
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
                            <p class="text-[rgba(255,255,255,0.85)]">Kateřina M.</p>
                            <div
                                class="flex gap-1 text-xs text-[rgba(255,255,255,0.35)]"
                            >
                                <span> Brno </span>
                                <span> · </span>
                                <span> Booking.com </span>
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
                            <p class="text-[rgba(255,255,255,0.85)]">Kateřina M.</p>
                            <div
                                class="flex gap-1 text-xs text-[rgba(255,255,255,0.35)]"
                            >
                                <span> Brno </span>
                                <span> · </span>
                                <span> Booking.com </span>
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
                            <p class="text-[rgba(255,255,255,0.85)]">Kateřina M.</p>
                            <div
                                class="flex gap-1 text-xs text-[rgba(255,255,255,0.35)]"
                            >
                                <span> Brno </span>
                                <span> · </span>
                                <span> Booking.com </span>
                                <span> · ⭐ 9.8 </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Instagram Section -->
    <section
        class="bg-purpleGhost flex flex-col px-8 py-10 md:px-14 md:pt-10 md:pb-12"
        id="instagram"
    >
        <p class="text-teal mb-1 text-xs font-bold tracking-[8%] uppercase md:mb-2">{{ __('Follow us') }}</p>
        <div class="flex w-full flex-col justify-between gap-y-2 md:flex-row">
            <h6
                class="text-navy hover:text-purple font-serif text-3xl transition-colors duration-300 md:text-4xl"
            >
                <a
                    href="https://instagram.com/apartmanstratos"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    @apartmanstratos
                </a>
            </h6>
            <a
                href="https://instagram.com/apartmanstratos"
                target="_blank"
                rel="noopener noreferrer"
                class="text-purple hover:text-purpleMid mt-auto text-sm font-semibold transition-colors duration-300"
            >
                {{ __('Open Instagram') }} →
            </a>
        </div>

        <div
            class="mt-8 grid grid-cols-1 gap-3 gap-y-5 sm:grid-cols-3 md:grid-cols-6"
        >
            @if ($instagramPosts->isNotEmpty())
                @foreach ($instagramPosts as $post)
                    <a
                        href="{{ $post->url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="block"
                    >
                        <div
                            class="h-40 rounded-xl bg-violet-300 transition-transform duration-300 hover:scale-105 sm:h-32 md:h-40 lg:h-60"
                        >
                            <x-responsive-image
                                :path="preg_replace('/^(\/?storage\/)+/', '', $post->image_url)"
                                :alt="$post->caption ?? __('Instagram photo')"
                                class="h-full w-full rounded-xl object-cover"
                            />
                        </div>
                    </a>
                @endforeach
            @else
                @for ($i = 0; $i < 6; $i++)
                    <div
                        class="h-40 rounded-xl bg-violet-300 transition-transform duration-300 hover:scale-105 sm:h-32 md:h-40 lg:h-60"
                    >
                        <x-responsive-image
                            :path="$apartmentImages[$i]"
                            :alt="__('Apartment image') . ' ' . ($i + 1)"
                            class="h-full w-full rounded-xl object-cover"
                        />
                    </div>
                @endfor
            @endif
        </div>
    </section>

    <!-- CTA Section -->
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
                    href="{{ route('reservation', ['locale' => app()->getLocale()]) }}"
                    class="btn-teal teal-shadow rounded-xl px-5 pt-2 pb-1 text-sm font-normal transition-all duration-200 hover:-translate-y-1 sm:px-7 sm:py-2 sm:text-base sm:font-bold md:py-3 lg:px-10"
                    >{{
                    __('Book') }}</a
                >
                <a
                    href="#apartments"
                    class="btn-outline-purple rounded-xl px-3 py-1 font-normal transition-all duration-200 hover:-translate-y-1 sm:px-4 sm:py-2 sm:font-semibold md:px-6 md:py-3"
                    >{{
                    __('Find stay') }}</a
                >
            </div>
        </div>
    </section>
</x-app-layout>
