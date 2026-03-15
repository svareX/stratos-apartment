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
        <div class="flex items-center gap-3">
            @php $current = app()->getLocale(); @endphp
            <a href="{{ route('locale.switch', 'cs') }}"
                class="p-1 px-3 border border-1 border-border rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-[var(--purple)] {{ $current === 'cs' ? 'bg-[var(--purplePale)]' : 'hover:bg-[var(--purplePale)]' }}">
                <span class="inline-block mr-2" aria-hidden>🇨🇿</span>CZ
            </a>
            <a href="{{ route('locale.switch', 'en') }}"
                class="p-1 px-3 border border-1 border-border rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-[var(--purple)] {{ $current === 'en' ? 'bg-[var(--purplePale)]' : 'hover:bg-[var(--purplePale)]' }}">
                <span class="inline-block mr-2" aria-hidden>🇩🇪</span>DE
            </a>
            <a href="{{ route('locale.switch', 'de') }}"
                class="p-1 px-3 border border-1 border-border rounded-lg text-sm font-bold tracking-[-10%] transition-colors duration-300 text-[var(--purple)] {{ $current === 'de' ? 'bg-[var(--purplePale)]' : 'hover:bg-[var(--purplePale)]' }}">
                <span class="inline-block mr-2" aria-hidden>🇺🇸</span>EN
            </a>
            <a href="{{ route('reservation') }}"
                class="ml-6 inline-flex items-center px-4 py-2 rounded-lg bg-[var(--teal)] text-white font-bold">Rezervovat</a>
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
                                <a href="#" class="btn-pri inline-flex items-center px-6 py-3 rounded-xl bg-[var(--teal)] text-white font-bold">Rezervovat</a>
                                <a href="#" class="btn-gho inline-flex items-center px-5 py-3 rounded-xl border border-white/30 text-white">Najít ideální pobyt</a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="c-arrow prev absolute left-6 top-1/2 -translate-y-1/2 z-20 bg-white/10 text-white w-11 h-11 rounded-full flex items-center justify-center cursor-pointer" @click="idx = (idx - 1 + slides.length) % slides.length">‹</div>
            <div class="c-arrow next absolute right-6 top-1/2 -translate-y-1/2 z-20 bg-white/10 text-white w-11 h-11 rounded-full flex items-center justify-center cursor-pointer" @click="idx = (idx + 1) % slides.length">›</div>
        </div>
    </section>

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

    <!-- Gallery -->
    {{-- <section class="px-14">
        <div class="gallery-inner grid grid-cols-[2fr_1fr_1fr] grid-rows-[200px_200px] gap-2 rounded-b-2xl overflow-hidden">
            <div class="gi col-span-1 row-span-2 bg-gray-200">
                <x-flux.skeleton />
                <img src="{{ $apartmentImages[0] }}" alt="" class="w-full h-full object-cover">
                <div class="gi-lbl">Interiér</div>
            </div>
            <div class="gi bg-gray-300">
                <img src="{{ $apartmentImages[1] }}" alt="" class="w-full h-full object-cover">
            </div>
            <div class="gi bg-gray-300">
                <img src="{{ $apartmentImages[2] }}" alt="" class="w-full h-full object-cover">
            </div>
            <div class="gi bg-gray-300">
                <img src="{{ $apartmentImages[3] }}" alt="" class="w-full h-full object-cover">
            </div>
            <div class="gi bg-[var(--purple)] text-white flex items-center justify-center">
                <div class="gi-more">View Gallery</div>
            </div>
        </div>
    </section> --}}

    <!-- Marquee -->
    <section class="relative bottom-32 bg-purplePale border-t border-b border-border overflow-hidden py-3 mt-6 rounded-md">
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
    <section class="flex flex-col relative bottom-16; gap-2 px-14">
        <h5 class="text-teal font-bold text-xs tracking-[8%] uppercase">Vyberte svůj pobyt</h5>
        <h4 class="font-serif text-3xl text-navy">Dvě destinace, <br> jeden apartmán pro vás</h4>
        <p class="text-smm text-muted">Každé místo ma svou vlastní duši - vyberte tu svou.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
            <div class="w-full h-full rounded-lg border">
                <!-- Card image -->
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
                    <a href="{{ route('reservation') }}" class="ml-2 inline-flex items-center px-4 py-2 rounded-xl bg-teal text-white font-bold text-sm w-fit">
                        Prozkoumat apartmán
                    </a>
                </div>
            </div>
            <div class="w-full h-full rounded-lg border">
                <!-- Card image -->
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
                    <a href="{{ route('reservation') }}" class="ml-2 inline-flex items-center px-4 py-2 rounded-xl bg-teal text-white font-bold text-sm w-fit">
                        Prozkoumat apartmán
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="px-14 py-16 bg-purplePale rounded-t-lg mt-8">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div>
                <h2 class="font-serif text-3xl text-navy">Rezervujte dřív,<br>než to udělá <br> váš soused.</h2>
                <p class="text-muted mt-2">Přímo u nás – bez provize,<br> s osobním přístupem hostitele.</p>
            </div>
            <div class="cta-btns flex gap-4">
                <a href="#" class="btn-teal px-6 py-3 rounded-lg">Rezervovat →</a>
                <a href="#" class="btn-outline-purple px-6 py-3 rounded-lg">Napsat hostiteli</a>
            </div>
        </div>
    </section>

</x-app-layout>