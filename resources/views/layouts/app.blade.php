<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">

        <div class="min-h-screen">

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <footer class="bg-[#1A0A3B] text-white">
                <div class="max-w-7xl mx-auto px-12 pt-14 pb-8">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-8 border-b border-white/10 pb-10 mb-6">
                        <div class="md:col-span-2">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-9 h-9 rounded-full bg-[#4B2EA2] flex items-center justify-center text-white font-bold">S</div>
                                <div class="font-bold text-lg">Apartmán Stratos</div>
                            </div>
                            <p class="text-[14px] text-[rgba(255,255,255,0.45)] max-w-md">
                                Jeseníky nebo Laa.<br>Obojí je lepší než pondělí.
                            </p>
                        </div>

                        <div class="text-xxs">
                            <h4 class="font-bold uppercase text-[rgba(255,255,255,0.3)] tracking-[8%] mb-4">Apartmány</h4>
                            <ul class="space-y-1">
                                <li><a href="#" class="text-white/70 hover:text-white">Ramzová / Jeseníky</a></li>
                                <li><a href="#" class="text-white/70 hover:text-white">Laa an der Thaya</a></li>
                                <li><a href="#" class="text-white/70 hover:text-white">Galerie</a></li>
                            </ul>
                        </div>

                        <div class="text-xxs">
                            <h4 class="font-bold uppercase text-[rgba(255,255,255,0.3)] tracking-[8%] mb-4">Pobyt</h4>
                            <ul class="space-y-1">
                                <li><a href="#" class="text-white/70 hover:text-white">Výhodné balíčky</a></li>
                                <li><a href="#" class="text-white/70 hover:text-white">Ceník</a></li>
                                <li><a href="#" class="text-white/70 hover:text-white">Aktivity a tipy</a></li>
                            </ul>
                        </div>

                        <div class="text-xxs">
                            <h4 class="font-bold uppercase text-[rgba(255,255,255,0.3)] tracking-[8%] mb-4">Informace</h4>
                            <ul class="space-y-1">
                                <li><a href="#" class="text-white/70 hover:text-white">Kontakt</a></li>
                                <li><a href="#" class="text-white/70 hover:text-white">Časté dotazy</a></li>
                                <li><a href="#" class="text-white/70 hover:text-white">Podmínky</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-[rgba(255,255,255,0.3)] text-xxs ">
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
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
