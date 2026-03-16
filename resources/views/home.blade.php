<x-app-layout>
    @php
        $heroImages = [
            asset('images/hero-ramzova-1.jpg'),
            asset('images/hero-ramzova-2.jpg'),
            asset('images/hero-ramzova-3.jpg'),
        ];

        $apartmentImages = [
            'https://picsum.photos/1200/900?random=1',
            'https://picsum.photos/1200/900?random=2',
            'https://picsum.photos/1200/900?random=3',
            'https://picsum.photos/1200/900?random=4',
            'https://picsum.photos/1200/900?random=5',
            'https://picsum.photos/1200/900?random=6',
        ];
    @endphp

    <!-- Navbar -->
    <div class="flex items-center justify-between sticky top-0 z-50 bg-white border-b border-border px-14 h-16 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-purple flex items-center justify-center text-white">S</div>
            <div class="font-bold text-sm text-text">Apartmán Stratos</div>
        </div>
        <div class="flex justify-between gap-4">
            <div class="flex justify-center gap-1 bg-purple rounded-lg pl-2 pr-3">
                <div class="w-9 h-9 rounded-full bg-purple flex items-center justify-center text-white">M</div>
                <div class="font-bold text-white text-sm my-auto">Ramzová / Jeseníky</div>
            </div>
            <div class="flex justify-center gap-1 bg-purple rounded-lg pl-2 pr-3">
                <div class="w-9 h-9 rounded-full bg-purple flex items-center justify-center text-white">*</div>
                <div class="font-bold text-white text-sm my-auto">Laa an der Thaya</div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @php $current = app()->getLocale(); @endphp
            <a href="{{ route('locale.switch', 'cs') }}"
                class="p-1 px-3 rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-purple {{ $current === 'cs' ? 'bg-[var(--purplePale)] border-[1px] border-border' : 'hover:bg-[var(--purplePale)]' }}">
                <span class="inline-block mr-2" aria-hidden>🇨🇿</span>CZ
            </a>
            <a href="{{ route('locale.switch', 'de') }}"
                class="p-1 px-3 rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-purple {{ $current === 'de' ? 'bg-[var(--purplePale)] border-[1px] border-border' : 'hover:bg-[var(--purplePale)]' }}">
                <span class="inline-block mr-2" aria-hidden>🇩🇪</span>DE
            </a>
            <a href="{{ route('locale.switch', 'en') }}"
                class="p-1 px-3 rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-purple {{ $current === 'en' ? 'bg-[var(--purplePale)] border-[1px] border-border' : 'hover:bg-[var(--purplePale)]' }}">
                <span class="inline-block mr-2" aria-hidden>🇬🇧</span>EN
            </a>
            <a href="#" class="bg-teal text-white teal-shadow px-5 py-2 rounded-lg font-bold text-sm duration-200 transition-all hover:bg-tealD">
                Rezervovat
            </a>
        </div>
    </div>

    <!-- Hero -->
    <section class="relative h-[90vh] min-h-[520px] overflow-hidden">
        <div x-data="{ idx: 0, slides: {{ Js::from($heroImages) }} }" x-init="setInterval(() => idx = (idx + 1) % slides.length, 5500)" class="h-full">
            <div class="apt-slides flex h-full transition-transform duration-700" :style="`transform:translateX(-${idx * 100}%);`">
                <template x-for="(s, i) in slides" :key="i">
                    <div class="apt-slide min-w-full h-full relative">
                        <div class="sc-r1 absolute inset-0 bg-cover bg-center" :style="`background-image: url('${s}')`"></div>
                        <div class="apt-slide-overlay absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="apt-slide-content absolute bottom-0 left-0 right-0 p-12 z-10 text-white">
                            <div class="slide-label">Novinka</div>
                            <h1 class="font-serif text-5xl md:text-6xl leading-tight mb-3">Luxusní apartmán <em class="text-[var(--teal)]">u až</em></h1>
                            <p class="max-w-xl text-white/80 mb-6">Komfortní ubytování s výhledem, sauna a soukromé parkování.</p>
                            <div class="flex gap-3">
                                <a href="#" class="btn-teal px-6 py-3 rounded-xl font-bold duration-200 transition-all hover:-translate-y-1 teal-shadow">Rezervovat →</a>
                                <a href="#" class="btn-gho inline-flex items-center px-5 py-3 rounded-xl border border-white/30 text-white duration-200 transition-all hover:-translate-y-1">Najít ideální pobyt</a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="c-arrow prev absolute left-6 top-1/2 -translate-y-1/2 z-20 bg-white/10 duration-300 transition-all hover:bg-white/20 text-white w-11 h-11 rounded-full flex items-center justify-center cursor-pointer" @click="idx = (idx - 1 + slides.length) % slides.length">‹</div>
            <div class="c-arrow next absolute right-6 top-1/2 -translate-y-1/2 z-20 bg-white/10 duration-300 transition-all hover:bg-white/20 text-white w-11 h-11 rounded-full flex items-center justify-center cursor-pointer" @click="idx = (idx + 1) % slides.length">›</div>
        </div>
    </section>

    <!-- Reservation -->
    <section class="relative bottom-28 px-14 py-10">
        <div class="max-w-[94vw] min-h-[30vh] mx-auto grid grid-cols-5 gap-6 gap-y-1 bg-white border border-border rounded-2xl pt-4 p-6 shadow-lg">
            <div class="col-span-5 mt-auto mb-2">
                <span class="text-purple text-xs font-bold uppercase tracking-[8%] inline-block">Zkontroloval dostupnost</span>
            </div>
            <div class="col-span-1">
                <div class="w-full h-full bg-blue-600"></div>
            </div>
            <div class="col-span-4">
                <div class="grid grid-cols-4 gap-2 gap-y-4 text-black">
                    <div class="col-span-1">
                        Lokalita
                    </div>
                    <div class="col-span-1">
                        Příjezd
                    </div>
                    <div class="col-span-1">
                        Odjezd
                    </div>
                    <div class="col-span-1">
                        Hosté
                    </div>
                    <div class="col-span-4">
                        <a href="#" class="w-full inline-flex justify-center px-4 py-2 rounded-xl bg-teal text-white font-bold">
                            Zkontroloval dostupnost
                        </a>
                    </div>
                    <div class="col-span-4 flex justify-start gap-4">
                        <span class="text-teal font-bold text-xs border-l-1 border-0 border-l-[3px] border-teal px-2 h-8 pt-2">Pouze 3 volné víkendy v únoru - termíny se rychle plní!</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Marquee -->
    <section class="relative bottom-20 bg-purplePale border-t border-b border-border overflow-hidden py-3 mt-6 rounded-md">
        <style>
            @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        </style>
        <div class="flex whitespace-nowrap items-center" style="animation: marquee 26s linear infinite;">
            <span class="text-sm font-medium text-purple px-7">Wellness & sauna</span>
            <span class="text-sm font-medium text-teal px-7">Free parking</span>
            <span class="text-sm font-medium text-muted px-7">Only 3 weekends left in Feb</span>
            <span class="text-sm font-medium text-purple px-7">Pet friendly</span>
            <span class="text-sm font-medium text-teal px-7">Direct booking saves fees</span>
            <!-- duplicate for seamless loop -->
            <span class="text-sm font-medium text-purple px-7">Wellness & sauna</span>
            <span class="text-sm font-medium text-teal px-7">Free parking</span>
            <span class="text-sm font-medium text-muted px-7">Only 3 weekends left in Feb</span>
            <span class="text-sm font-medium text-purple px-7">Pet friendly</span>
            <span class="text-sm font-medium text-teal px-7">Direct booking saves fees</span>
        </div>
    </section>

    <!-- Apartment selection -->
    <section class="flex flex-col gap-2 px-14">
        <h5 class="text-teal font-bold text-xs tracking-[8%] uppercase">Vyberte svůj pobyt</h5>
        <h4 class="font-serif text-3xl text-navy">Dvě destinace, <br> jeden apartmán pro vás</h4>
        <p class="text-smm text-muted">Každé místo ma svou vlastní duši - vyberte tu svou.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-5">
            <div class="w-full h-full rounded-2xl bg-white border-[1.5px] border-border shadow-md card-shadow transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <div x-data="{ idx: 0, slides: {{ Js::from(array_slice($apartmentImages, 3, 5)) }} }" class="relative flex flex-col justify-end w-full h-60 rounded-t-2xl overflow-hidden rounded-2xl">
                    <div class="apt-slides flex h-full transition-transform duration-700" :style="`transform:translateX(-${idx * 100}%);`">
                        <template x-for="(s, i) in slides" :key="i">
                            <div class="apt-slide min-w-full h-full relative">
                                <img :src="s" alt="Výhled z apartmánu" class="w-full h-full object-cover" />
                                <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">Výhled z apartmánu</div>
                            </div>
                        </template>
                    </div>
                    <div class="absolute bottom-3 right-4 flex gap-1 z-20">
                        <template x-for="i in slides.length" :key="i">
                            <div
                                :class="[
                                    'transition-all duration-300 cursor-pointer',
                                    idx === i-1
                                        ? 'bg-teal w-6 h-2 rounded-full'
                                        : 'bg-teal/60 w-2 h-2 rounded-full opacity-60'
                                ]"
                                @click="idx = i-1"
                            ></div>
                        </template>
                    </div>
                    <div class="c-arrow prev absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/10 duration-300 transition-all hover:bg-white/20 text-white w-3.5 h-3.5 rounded-full flex items-center justify-center cursor-pointer text-base" @click="idx = (idx - 1 + slides.length) % slides.length">‹</div>
                    <div class="c-arrow next absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/10 duration-300 transition-all hover:bg-white/20 text-white w-3.5 h-3.5 rounded-full flex items-center justify-center cursor-pointer text-base" @click="idx = (idx + 1) % slides.length">›</div>
                </div>

                <div class="flex flex-col py-6 px-5 gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#4B2EA2] flex items-center justify-center text-white font-bold">S</div>
                        <div class="flex flex-col">
                            <div class="flex gap-2">
                                <div class="font-bold text-xs uppercase text-teal">Ramzová</div>
                                <div class="font-bold text-xs uppercase text-teal">Jeseníky</div>
                            </div>
                            <div class="text-lg font-serif">Apartmán Ramzová</div>
                        </div>
                    </div>
                    <p class="px-1 text-sm text-muted">Vaše základna v srdci Jeseníků. Sjezdovka za dveřmi, trailové stezky za rohem, Priessnitzovy lázně 13 km.</p>
                    <div class="flex px-2 gap-2 mt-1 mb-2">
                        <span class="py-1 px-3 rounded-xl text-xs text-purple bg-purplePale border-[1px] border-border">
                            Lyže za dveřmi
                        </span>
                        <span class="py-1 px-3 rounded-xl text-xs text-purple bg-purplePale border-[1px] border-border">
                            Traily
                        </span>
                        <span class="py-1 px-3 rounded-xl text-xs text-purple bg-purplePale border-[1px] border-border">
                            Kolo
                        </span>
                        <span class="py-1 px-3 rounded-xl text-xs text-purple bg-purplePale border-[1px] border-border">
                            Psi vítani
                        </span>
                    </div>
                    <a href="#" class="btn-teal px-4 py-1.5 ml-2 w-fit rounded-xl font-bold duration-200 transition-all hover:translate-x-1 teal-shadow text-sm">
                        Prozkoumat apartmán
                    </a>
                </div>
            </div>
            <div class="w-full h-full rounded-2xl bg-white border-[1.5px] border-border shadow-md card-shadow transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <div x-data="{ idx: 0, slides: {{ Js::from(array_slice($apartmentImages, 0, 3)) }} }" class="relative flex flex-col justify-end w-full h-60 rounded-t-2xl overflow-hidden rounded-2xl">
                    <div class="apt-slides flex h-full transition-transform duration-700" :style="`transform:translateX(-${idx * 100}%);`">
                        <template x-for="(s, i) in slides" :key="i">
                            <div class="apt-slide min-w-full h-full relative">
                                <img :src="s" alt="Výhled z apartmánu" class="w-full h-full object-cover" />
                                <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">Therme Laa - 5 minut</div>
                            </div>
                        </template>
                    </div>
                    <div class="absolute bottom-3 right-4 flex gap-1 z-20">
                        <template x-for="i in slides.length" :key="i">
                            <div
                                :class="[
                                    'transition-all duration-300 cursor-pointer',
                                    idx === i-1
                                        ? 'bg-teal w-6 h-2 rounded-full'
                                        : 'bg-teal/60 w-2 h-2 rounded-full opacity-60'
                                ]"
                                @click="idx = i-1"
                            ></div>
                        </template>
                    </div>
                    <div class="c-arrow prev absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/10 duration-300 transition-all hover:bg-white/20 text-white w-3.5 h-3.5 rounded-full flex items-center justify-center cursor-pointer text-base" @click="idx = (idx - 1 + slides.length) % slides.length">‹</div>
                    <div class="c-arrow next absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/10 duration-300 transition-all hover:bg-white/20 text-white w-3.5 h-3.5 rounded-full flex items-center justify-center cursor-pointer text-base" @click="idx = (idx + 1) % slides.length">›</div>
                </div>

                <div class="flex flex-col py-6 px-5 gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#4B2EA2] flex items-center justify-center text-white font-bold">S</div>
                        <div class="flex flex-col">
                            <div class="flex gap-2">
                                <div class="font-bold text-xs uppercase text-teal">Laa ab der Thaya</div>
                                <div class="font-bold text-xs uppercase text-teal">Dolní rakousy</div>
                            </div>
                            <div class="text-lg font-serif">Apartmán Laa</div>
                        </div>
                    </div>
                    <p class="px-1 text-sm text-muted">Wellness únik pár kroků od Therme Laa. Vídeň hodinu autem, vinohrady Weinviertelu za oknem.</p>
                    <div class="flex px-2 gap-2 mt-1 mb-2">
                        <span class="py-1 px-3 rounded-xl text-xs text-purple bg-purplePale border-[1px] border-border">
                            Lyže za dveřmi
                        </span>
                        <span class="py-1 px-3 rounded-xl text-xs text-purple bg-purplePale border-[1px] border-border">
                            Traily
                        </span>
                        <span class="py-1 px-3 rounded-xl text-xs text-purple bg-purplePale border-[1px] border-border">
                            Kolo
                        </span>
                        <span class="py-1 px-3 rounded-xl text-xs text-purple bg-purplePale border-[1px] border-border">
                            Psi vítani
                        </span>
                    </div>
                    <a href="#" class="btn-teal px-4 py-1.5 ml-2 w-fit rounded-xl font-bold duration-200 transition-all hover:translate-x-1 teal-shadow text-sm">
                        Prozkoumat apartmán
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Photogallery -->
    <section class="flex flex-col gap-4 px-14 py-16 rounded-t-lg">
        <h5 class="text-2xl font-serif text-navy">Fotogalerie - oba apartmány</h5>
        <div x-data="{ lightbox: false, lightboxIdx: 0, images: {{ Js::from($apartmentImages) }} }" class="grid grid-cols-4 grid-rows-2 gap-3 h-80 w-full">
            <div class="flex flex-col justify-end col-span-2 row-span-2 rounded-l-3xl cursor-pointer group overflow-hidden relative" @click="lightbox = true; lightboxIdx = 0">
                <img :src="images[0]" alt="Ramzová - výhled" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">Ramzová - výhled</div>
            </div>
            <div class="flex flex-col justify-end col-span-1 row-span-1 cursor-pointer group overflow-hidden relative" @click="lightbox = true; lightboxIdx = 1">
                <img :src="images[1]" alt="Laa - therme" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">Laa - therme</div>
            </div>
            <div class="flex flex-col justify-end col-span-1 row-span-1 rounded-tr-3xl cursor-pointer group overflow-hidden relative" @click="lightbox = true; lightboxIdx = 2">
                <img :src="images[2]" alt="Kuchyň" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">Kuchyň</div>
            </div>
            <div class="flex flex-col justify-end col-span-1 row-span-1 cursor-pointer group overflow-hidden relative" @click="lightbox = true; lightboxIdx = 3">
                <img :src="images[3]" alt="Lyžování" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                <div class="absolute bottom-0 left-0 ml-4 mb-3 text-sm tracking-[8%] uppercase font-bold w-fit px-3 rounded-xl text-[rgba(255,255,255,0.6)] bg-[rgba(0,0,0,0.3)] border-[1px] border-[rgba(255,255,255,0.15)] backdrop-blur">Lyžování</div>
            </div>
            <div class="flex flex-col justify-center col-span-1 row-span-1 rounded-br-3xl cursor-pointer group overflow-hidden relative" @click="lightbox = true; lightboxIdx = 4">
                <img :src="images[4]" alt="Další fotky" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105 opacity-80" />
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white font-semibold text-lg">+24 fotek →</div>
            </div>

            <!-- Lightbox Modal -->
            <template x-if="lightbox">
                <div 
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/80" 
                    @click.self="lightbox = false"
                    @keydown.window.escape="lightbox = false"
                    @keydown.window.arrow-right="lightboxIdx = (lightboxIdx + 1) % images.length"
                    @keydown.window.arrow-left="lightboxIdx = (lightboxIdx - 1 + images.length) % images.length"
                    tabindex="0"
                    x-init="$el.focus()"
                >
                    <button class="absolute top-6 right-8 text-white text-3xl font-bold" @click="lightbox = false">&times;</button>
                    <button class="absolute left-6 top-1/2 -translate-y-1/2 text-white text-3xl font-bold" @click="lightboxIdx = (lightboxIdx - 1 + images.length) % images.length">&#8592;</button>
                    <img :src="images[lightboxIdx]" class="max-h-[80vh] max-w-[90vw] rounded-xl shadow-2xl border-4 border-white" />
                    <button class="absolute right-6 top-1/2 -translate-y-1/2 text-white text-3xl font-bold" @click="lightboxIdx = (lightboxIdx + 1) % images.length">&#8594;</button>
                </div>
            </template>
        </div>
    </section>

    <!-- Features -->
    <section class="flex flex-col gap-5 pt-10 pb-14 bg-gray w-full border-y-[1px] border-border">
        <p class="mx-auto text-xs text-teal uppercase font-bold tracking-[8%] mb-4">Ideální pro</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-8 px-16">

            <div class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg sm:border-r-[1px]     border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">👫</span>
                <span class="text-xs text-navy font-semibold">Páry</span>
            </div>

            <div class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg md:border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">👩‍🦰🐕</span>
                <span class="text-xs text-navy font-semibold">Pejskaři</span>
            </div>

            <div class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg sm:border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">👨‍👩‍👧</span>
                <span class="text-xs text-navy font-semibold">Rodiny s dětmi</span>
            </div>

            <div class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg lg:border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">💻</span>
                <span class="text-xs text-navy font-semibold">Práce na dálku</span>
            </div>

            <div class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg sm:border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">🏃</span>
                <span class="text-xs text-navy font-semibold">Turistika a trail</span>
            </div>

            <div class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg md:border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">⛷</span>
                <span class="text-xs text-navy font-semibold">Lyžování</span>
            </div>

            <div class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg sm:border-r-[1px] border-border hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">🧘</span>
                <span class="text-xs text-navy font-semibold">Welness pobyt</span>
            </div>

            <div class="flex flex-col justify-center text-center px-3 pt-4 pb-4 rounded-lg hover:bg-purplePale transition-colors duration-300">
                <span class="text-[28px]">🏢</span>
                <span class="text-xs text-navy font-semibold">Firemní výjezd</span>
            </div>
            
        </div>
    </section>

    <!-- Features2 -->
    <section class="flex flex-col px-14 py-10">
        <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-4">Proč stratos?</p>
        <h6 class="text-4xl text-navy font-serif">Jo, postel taky máme. Ale to je ta nejméně zajímavá část.</h6>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-12 bg-white">
            <div class="flex flex-col p-6 py-8 rounded-2xl bg-cream border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <span class="text-25xl">🍳</span>
                <p class="my-2 text-navy font-bold">Vlastní kuchyň</p>
                <p class="text-text">Snídaně kdy chcete. Recepce vás budit nebude, protože žádná není.</p>
            </div>

            <div class="flex flex-col p-6 py-8 rounded-2xl bg-cream border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <span class="text-25xl">🐾</span>
                <p class="my-2 text-navy font-bold">Přátelé psům</p>
                <p class="text-text">Váš pes je vítán stejně jako vy. Bez příplatků, bez kompromisů.</p>
            </div>

            <div class="flex flex-col p-6 py-8 rounded-2xl bg-cream border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <span class="text-25xl">🚗</span>
                <p class="my-2 text-navy font-bold">Parkování zdarma</p>
                <p class="text-text">Auto zaparkujete. Starosti taky.</p>
            </div>

            <div class="flex flex-col p-6 py-8 rounded-2xl bg-cream border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <span class="text-25xl">📶</span>
                <p class="my-2 text-navy font-bold">Rychlé WiFi + Netflix</p>
                <p class="text-text">Netflix máme. Ale venku je to lepší.</p>
            </div>

            <div class="flex flex-col p-6 py-8 rounded-2xl bg-cream border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <span class="text-25xl">💰</span>
                <p class="my-2 text-navy font-bold">Lepší cena než hotel</p>
                <p class="text-text">Celý apartmán jen pro vás, za cenu hotelového pokoje pro dva.</p>
            </div>

            <div class="flex flex-col p-6 py-8 rounded-2xl bg-cream border-[1px] border-border hover:border-purple transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                <span class="text-25xl">🏠</span>
                <p class="my-2 text-navy font-bold">Osobní přístup</p>
                <p class="text-text">Nejsme řetězec. Dostanete tipy od hostitele, ne lamináčový leták.</p>
            </div>
        </div>
    </section>

    <!-- Reviews -->
    <section class="bg-review w-full">
        <div class="flex flex-col px-14 py-20">
            <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-3">Recenze hostů</p>
            <h6 class="text-5xl text-white font-serif mb-2">Co říkají hosté.</h6>
            <p class="flex gap-2 text-[rgba(255,255,255,0.5)]">
                <span>
                    Přes 9.8 na Booking.com
                </span>
                <span>
                    ·
                </span>
                <span>
                    Přes 150 recenzí
                </span>
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-10 text-[rgba(255,255,255,0.72)]">
                <div class="flex flex-col gap-y-4 p-6 border-[1px] border-[rgba(255,255,255,0.1)] bg-[rgba(255,255,255,0.06);] rounded-2xl">
                    <p class="text-xxs">⭐⭐⭐⭐⭐</p>
                    <p class="italic text-[15px]">„Perfektní základna na lyže. Ráno jsme byli na sjezdovce za 3 minuty. Večer vyhřátý apartmán a víno."</p>
                    <div class="flex gap-3">
                        <div class="w-9 h-9 my-auto rounded-full bg-teal flex items-center justify-center text-white font-bold">K</div>
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
                                    ⭐ 9.8
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-y-4 p-6 border-[1px] border-[rgba(255,255,255,0.1)] bg-[rgba(255,255,255,0.06);] rounded-2xl">
                    <p class="text-xxs">⭐⭐⭐⭐⭐</p>
                    <p class="italic text-[15px]">„Perfektní základna na lyže. Ráno jsme byli na sjezdovce za 3 minuty. Večer vyhřátý apartmán a víno."</p>
                    <div class="flex gap-3">
                        <div class="w-9 h-9 my-auto rounded-full bg-teal flex items-center justify-center text-white font-bold">K</div>
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
                                    ⭐ 9.8
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-y-4 p-6 border-[1px] border-[rgba(255,255,255,0.1)] bg-[rgba(255,255,255,0.06);] rounded-2xl">
                    <p class="text-xxs">⭐⭐⭐⭐⭐</p>
                    <p class="italic text-[15px]">„Perfektní základna na lyže. Ráno jsme byli na sjezdovce za 3 minuty. Večer vyhřátý apartmán a víno."</p>
                    <div class="flex gap-3">
                        <div class="w-9 h-9 my-auto rounded-full bg-teal flex items-center justify-center text-white font-bold">K</div>
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
                                    ⭐ 9.8
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Socials -->
    <section class="flex flex-col p-14 bg-purpleGhost">
        <p class="text-xs text-teal uppercase font-bold tracking-[8%] mb-2">Sledujte nás</p>
        <div class="flex justify-between w-full">
            <h6 class="text-4xl text-navy font-serif hover:text-purple transition-colors duration-300">
                <a href="#" target="_blank" rel="noopener noreferrer">
                    @stratosapartments
                </a>
            </h6>
            <a href="#" target="_blank" rel="noopener noreferrer" class="text-sm text-purple font-semibold hover:text-purpleMid transition-colors duration-300">
                Otevřít Instagram →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-6 gap-3 mt-8">
            @for ($i = 0; $i < 6; $i++)
                <div class="h-60 bg-violet-300 rounded-xl hover:scale-105 transition-transform duration-300">
                    <img src="{{ $apartmentImages[$i] }}" alt="" class="w-full h-full object-cover rounded-xl">
                </div>
            @endfor
        </div>
    </section>

    <!-- CTA -->
    <section class="px-14 py-16 bg-purplePale rounded-t-lg">
        <div class="mx-auto flex items-center justify-between">
            <div>
                <h2 class="font-serif text-4xl text-navy font-bold">Rezervujte dřív,<br>než to udělá  váš soused.</h2>
                <p class="text-muted mt-2">Přímo u nás – bez provize,<br> s osobním přístupem hostitele.</p>
            </div>
            <div class="cta-btns flex gap-4">
                <a href="#" class="btn-teal px-10 py-3 rounded-xl font-bold duration-200 transition-all hover:-translate-y-1 teal-shadow">Rezervovat →</a>
                <a href="#" class="btn-outline-purple px-6 py-3 rounded-xl font-semibold duration-200 transition-all hover:-translate-y-1">Najít ideální pobyt</a>
            </div>
        </div>
    </section>

</x-app-layout>
