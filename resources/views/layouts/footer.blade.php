<footer class="bg-[#1A0A3B] text-white">
    <div class="max-w-7xl mx-auto px-8 md:px-12 pt-14 pb-8">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 gap-y-6 md:gap-8 border-b border-white/10 pb-6 md:pb-10 mb-3 md:mb-6">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-9 h-9 rounded-full bg-[#4B2EA2] flex items-center justify-center text-white font-bold">
                        S</div>
                    <div class="font-bold md:text-lg">Apartmán Stratos</div>
                </div>
                <p class="text-[14px] text-[rgba(255,255,255,0.45)] max-w-md">
                    Jeseníky nebo Laa.<br>Obojí je lepší než pondělí.
                </p>
            </div>

            <div class="text-xxs">
                <h4 class="font-bold uppercase text-[rgba(255,255,255,0.3)] tracking-[8%] mb-2 md:mb-4">Apartmány</h4>
                <ul class="space-y-1">
                    <li><a href="#" class="text-white/70 hover:text-white">Ramzová / Jeseníky</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white">Laa an der Thaya</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white">Galerie</a></li>
                </ul>
            </div>

            <div class="text-xxs">
                <h4 class="font-bold uppercase text-[rgba(255,255,255,0.3)] tracking-[8%] mb-2 md:mb-4">Pobyt</h4>
                <ul class="space-y-1">
                    <li><a href="#" class="text-white/70 hover:text-white">Výhodné balíčky</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white">Ceník</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white">Aktivity a tipy</a></li>
                </ul>
            </div>

            <div class="text-xxs">
                <h4 class="font-bold uppercase text-[rgba(255,255,255,0.3)] tracking-[8%] mb-2 md:mb-4">Informace</h4>
                <ul class="space-y-1">
                    <li><a href="#" class="text-white/70 hover:text-white">Kontakt</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white">Časté dotazy</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white">Podmínky</a></li>
                </ul>
            </div>
        </div>

        <div
            class="flex flex-col md:flex-row items-center justify-between gap-2 md:gap-4 text-sm text-[rgba(255,255,255,0.3)] text-xxs ">
            <div>© {{ date('Y') }} Apartmán Stratos — apartmanstratos.cz</div>
            <div class="flex gap-6">
                <a href="#" class="hover:text-white">Ochrana osobních údajů</a>
                <a href="#" class="hover:text-white">Cookies</a>
                <div class="flex items-center gap-1">
                    @php $current = app()->getLocale(); @endphp
                    <a href="{{ route('locale.switch', 'cs') }}">
                        <span class="inline-block mr-2" aria-hidden>🇨🇿</span>
                    </a>
                    <a href="{{ route('locale.switch', 'de') }}">
                        <span class="inline-block mr-2" aria-hidden>🇩🇪</span>
                    </a>
                    <a href="{{ route('locale.switch', 'en') }}">
                        <span class="inline-block mr-2" aria-hidden>🇺🇸</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>