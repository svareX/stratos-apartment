<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @if(config('services.google.site_verification'))
            <meta name="google-site-verification" content="{{ config('services.google.site_verification') }}">
        @endif

        {!! seo() !!}

        @php
            $faviconPath = config('seo.favicon') ?? '/storage/icons/icon.png';
            $faviconUrl = \Illuminate\Support\Str::startsWith($faviconPath, ['http://','https://']) ? $faviconPath : asset(ltrim($faviconPath, '/'));
        @endphp
        <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
        <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
        <link rel="shortcut icon" href="{{ $faviconUrl }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        @fluxAppearance
        @if(config('services.google.gtm_id'))
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ config('services.google.gtm_id') }}');</script>
            <!-- End Google Tag Manager -->
        @endif
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

                            if (Math.abs(window.scrollY - y) > 5) {
                                window.scrollTo({top: y, behavior: 'smooth'});
                            }
                        }, 50);
                    }
                    if (++attempts > 50) clearInterval(interval);
                }, 100);
            }
        ">

        @if(config('services.google.gtm_id'))
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('services.google.gtm_id') }}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
        @endif

        <div class="min-h-screen">
            @include('layouts.navigation')

            <main class="bg-cream">
                {{ $slot }}
            </main>

            @include('layouts.footer')
        </div>

        <livewire:chatbot />

        @stack('modals')

        @livewireScripts
        @fluxScripts
    </body>
</html>
