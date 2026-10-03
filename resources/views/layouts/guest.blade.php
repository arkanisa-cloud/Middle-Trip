<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-ink bg-canvas antialiased selection:bg-primary selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 p-4">
            <div class="mb-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <svg class="w-8 h-8 text-primary group-hover:scale-105 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                    </svg>
                    <span class="text-2xl font-extrabold tracking-tight text-ink-heading">MiddleTrip</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md bg-white border border-hairline shadow-xs rounded-2xl p-6 sm:p-8">
                {{ $slot }}
            </div>

            <div class="mt-6 text-center text-xs text-muted">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors inline-flex items-center gap-1 font-semibold">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </body>
</html>
