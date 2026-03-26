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
        @fluxAppearance
    </head>
    <body class="font-sans antialiased">

        <div class="min-h-screen">
            <!-- Navigation -->
            @include('layouts.navigation')

            <!-- Main Page Content -->
            <main class="bg-cream">
                {{ $slot }}
            </main>

            <!-- Footer -->
            @include('layouts.footer')
        </div>

        @stack('modals')

        @livewireScripts
        @fluxScripts
    </body>
</html>
