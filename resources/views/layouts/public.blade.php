<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">

    @stack('head_preload')

    <!-- Fonts: Plus Jakarta Sans (Non-blocking with display swap) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" media="print"
        onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    </noscript>

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
