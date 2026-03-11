<x-app-layout>
    <div class="flex flex-col justify-start gap-lg h-screen">
        <h1 class="font-bold text-custom-xl">Stratos</h1>
        <h1 class="font-bold text-custom-xl">Apartments</h1>
        <h4 class="font-light text-custom-lg tracking-low">Navštivte nejlepší apartmán</h4>
        <h4 class="font-light text-custom-lg tracking-low">v <span class="font-medium italic text-custom-lg tracking-low leading-[80px]">Laa an der Thaya</span></h4>
        <div class="flex justify-center text-center bg-white w-[220px] h-[64px] rounded-[24px] cursor-pointer">
            <a href="{{ route('reservation') }}">
                <span class="text-primary font-light text-[36px] tracking-low hover:underline">
                    rezervovat
                </span>
            </a>
        </div>
    </div>

    <div class="flex flex-col justify-start gap-xl">
        <h3 class="font-medium text-[48px] leading-[32px] tracking-[-8%]">Proč zvolit Apartmán Stratos?</h3>
        <div class="flex gap-[48px] px-[10px]">
            @for ($i = 0; $i < 5; $i++)
                <div class="flex flex-col justify-center text-center w-[200px] h-[200px] bg-red-400 gap-[12px] p-[12px]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="mx-auto size-[60px]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                    </svg>
                    
                    <span class="text-[20px] leading-[32px] tracking-[-6%]">
                        Nejaky prvek z oblasti
                    </span>
                </div>
            @endfor
        </div>

        <h3 class="font-medium text-[48px] leading-[32px] tracking-[-8%]">Vybavení našeho apartmánu</h3>
        <div class="flex justify-start gap-md px-[10px]">
            @for ($i = 0; $i < 6; $i++)
                <img src="https://picsum.photos/{{rand(180, 420)}}/280" alt="random image">
            @endfor
        </div>

        <h3 class="font-medium text-[48px] leading-[32px] tracking-[-8%]">Kde apartmán najdete?</h3>
        <div class="w-full grid grid-cols-1 sm:grid-cols-7 gap-10">
            <div class="w-full h-[60vh] rounded-lg overflow-hidden col-span-5">
                <iframe
                    class="w-full h-full border-0"
                    loading="lazy"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?q=48.7179556,16.3910199&z=15&output=embed">
                </iframe>
            </div>
            <div class="col-span-2 flex flex-col gap-md">
                <div>
                    <h3 class="font-medium text-[40px] mb-2">Adresa</h3>
                    <p class="text-[24px] leading-[28px] mb-4">
                        Feldstraße 4<br>
                        2136 Laa an der Thaya<br>
                        Rakousko
                        </p>
                        
                    <a href="https://www.google.com/maps?q=48.7179556,16.3910199" target="_blank" rel="noopener" class="inline-block bg-primary text-white px-2 rounded-lg hover:underline">Zobrazit trasu</a>
                </div>

                <div>
                    <h4 class="font-medium text-[40px] mb-2">Kontakt</h4>
                    <p class="text-[20px] leading-[28px] mb-4">
                        Telefon: <a href="tel:+420123456789" class="hover:underline">+420 732 558 978</a><br>
                        Email: <a href="mailto:info@apartmanstratos.example" class="hover:underline">info@apartmanstratos.cz</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>