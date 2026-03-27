<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        @fluxAppearance
    </head>
    <body class="font-sans antialiased" 
        x-data
        x-init="
            if (window.location.hash) {
                let attempts = 0;
                let interval = setInterval(() => {
                    let el = document.querySelector(window.location.hash);
                    if (el) {
                        clearInterval(interval);
                        setTimeout(() => {
                            let y = el.getBoundingClientRect().top + window.scrollY - 80;
                            
                            // Only trigger smooth scroll if the browser didn't already natively jump there
                            if (Math.abs(window.scrollY - y) > 5) {
                                window.scrollTo({top: y, behavior: 'smooth'});
                            }
                        }, 50);
                    }
                    if (++attempts > 50) clearInterval(interval);
                }, 100);
            }
        ">

        <div class="min-h-screen">
            @include('layouts.navigation')

            <main class="bg-cream">
                {{ $slot }}
            </main>

            @include('layouts.footer')
        </div>

        @stack('modals')

        @livewireScripts
        @fluxScripts
    </body>
</html>