<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">

    @stack('head_preload')

    <!-- Preload Self-Hosted Font: Plus Jakarta Sans -->
    <link rel="preload" href="{{ asset('fonts/plus-jakarta-sans.woff2') }}" as="font" type="font/woff2" crossorigin>

    <!-- Styles / Scripts -->
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="{{ $bodyClass }}">

    @if ($withNavbar)
        <!-- Animated Floating Navbar Component -->
        <x-navbar :active="$active" :hero="$hero" />
    @endif

    {{-- Main Content Slot --}}
    {{ $slot }}

    @if ($withFooter)
        <!-- Footer Component -->
        <x-footer />
    @endif

    @if ($withFab)
        <!-- Floating Contact Action Button (FAB) -->
        <x-fab-contact />
    @endif

    <!-- Stack Scripts -->
    @stack('scripts')
</body>

</html>
