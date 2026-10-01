<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $expedition['title'] }} - MiddleTrip</title>
    <meta name="description" content="{{ $expedition['description'] }}">

    <!-- Google Fonts: Plus Jakarta Sans Only -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-canvas text-ink antialiased selection:bg-primary selection:text-white font-sans min-h-screen flex flex-col justify-between">

    <!-- Top Navigation Component -->
    <x-navbar active="ekspedisi" :hero="false" />

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 md:pt-28 pb-20 w-full flex-1">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs font-medium text-muted mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="text-muted-soft">&gt;</span>
            <a href="{{ route('ekspedisi.index') }}" class="hover:text-primary transition-colors">Ekspedisi</a>
            <span class="text-muted-soft">&gt;</span>
            <span class="text-body-strong font-semibold truncate">{{ $expedition['title'] }}</span>
        </nav>

        <!-- Page Title & Chromatic Grade Badges -->
        <div class="mb-7">
            <h1 class="text-3xl sm:text-4xl lg:text-[42px] font-extrabold text-ink-heading tracking-tight mb-3.5">
                {{ $expedition['title'] }}
            </h1>

            <!-- Key Meta Badges -->
            <div class="flex flex-wrap items-center gap-2.5 text-xs font-semibold">
                <!-- Elevation Badge -->
                <div
                    class="inline-flex items-center gap-1.5 bg-surface-card text-body-strong px-3.5 py-1.5 rounded-full border border-hairline shadow-xs">
                    <svg class="w-3.5 h-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                    </svg>
                    <span class="font-bold">{{ $expedition['elevation'] }}</span>
                </div>

                <!-- Difficulty Characteristic Badge -->
                <div id="header-difficulty-container"
                    class="inline-flex items-center gap-1.5 bg-surface-card text-body-strong px-3.5 py-1.5 rounded-full border border-hairline shadow-xs">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span id="header-difficulty-text">{{ $expedition['difficulty_badge'] }}</span>
                </div>

                <!-- Chromatic Grade Badge -->
                @php
                    $gradeBadgeClass = match ($expedition['grade']) {
                        'Grade A' => 'bg-grade-a-bg text-grade-a-text border-grade-a-dot/30',
                        'Grade B' => 'bg-grade-b-bg text-grade-b-text border-grade-b-dot/30',
                        'Grade C' => 'bg-grade-c-bg text-grade-c-text border-grade-c-dot/30',
                        default => 'bg-gray-100 text-gray-800 border-gray-200',
                    };
                    $gradeDotClass = match ($expedition['grade']) {
                        'Grade A' => 'bg-grade-a-dot',
                        'Grade B' => 'bg-grade-b-dot',
                        'Grade C' => 'bg-grade-c-dot',
                        default => 'bg-gray-400',
                    };
                @endphp
                <div id="header-grade-container"
                    class="inline-flex items-center gap-1.5 {{ $gradeBadgeClass }} px-3.5 py-1.5 rounded-full border shadow-xs transition-colors duration-200">
                    <span id="header-grade-dot" class="w-2 h-2 rounded-full {{ $gradeDotClass }}"></span>
                    <span id="header-grade-text">{{ $expedition['grade_label'] }}</span>
                </div>

                <!-- Location Badge -->
                <div
                    class="inline-flex items-center gap-1.5 bg-surface-card text-muted px-3.5 py-1.5 rounded-full border border-hairline shadow-xs">
                    <svg class="w-3.5 h-3.5 text-muted-soft" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ $expedition['location'] }}</span>
                </div>
            </div>
        </div>

        <!-- Photo Gallery Grid (Matches referensi detail-gunung.html, dynamically adaptive & interactive) -->
        @php $photoCount = count($expedition['gallery'] ?? []); @endphp
        <section class="relative w-full mb-10 select-none" aria-label="Galeri Foto Ekspedisi">
            @if ($photoCount <= 1)
                <!-- 1 Photo: Full Width Hero -->
                <div class="w-full relative rounded-2xl overflow-hidden group h-[340px] sm:h-[450px] cursor-pointer"
                    onclick="openPhotoModal(0)">
                    <img src="{{ $expedition['gallery'][0]['url'] ?? $expedition['image'] }}"
                        alt="{{ $expedition['gallery'][0]['caption'] ?? $expedition['title'] }}"
                        onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/1200x800/203a43/ffffff?text=Ekspedisi' }}'"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 right-4">
                        <button type="button"
                            class="bg-black/55 backdrop-blur-md border border-white/20 text-white text-xs font-semibold px-4 py-2.5 rounded-full flex items-center gap-2 hover:bg-black/75 transition-all shadow-lg">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Lihat Foto Asli</span>
                        </button>
                    </div>
                </div>
            @elseif ($photoCount == 2)
                <!-- 2 Photos: 50-50 Split Side-by-Side (Matches referensi height h-[340px] sm:h-[450px]) -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <!-- Left Photo -->
                    <div class="md:col-span-6 relative rounded-2xl overflow-hidden group h-[340px] sm:h-[450px] cursor-pointer"
                        onclick="openPhotoModal(0)">
                        <img src="{{ $expedition['gallery'][0]['url'] ?? $expedition['image'] }}"
                            alt="{{ $expedition['gallery'][0]['caption'] ?? $expedition['title'] }}"
                            onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/900x900/203a43/ffffff?text=Ekspedisi' }}'"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent">
                        </div>
                    </div>
                    <!-- Right Photo with Center Action Badge -->
                    <div class="md:col-span-6 relative rounded-2xl overflow-hidden group h-[340px] sm:h-[450px] cursor-pointer"
                        onclick="openPhotoModal(1)">
                        <img src="{{ $expedition['gallery'][1]['url'] }}"
                            alt="{{ $expedition['gallery'][1]['caption'] ?? 'Dokumentasi Ekspedisi' }}"
                            onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/900x900/203a43/ffffff?text=Ekspedisi' }}'"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 brightness-95" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent">
                        </div>
                        <div
                            class="absolute inset-0 bg-black/30 group-hover:bg-black/40 flex items-center justify-center p-3 transition-colors">
                            <button type="button"
                                class="bg-black/55 backdrop-blur-md border border-white/20 text-white text-xs font-semibold px-4 py-2.5 rounded-full flex items-center gap-2 hover:bg-black/75 transition-all shadow-lg">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Lihat 2 Foto Asli</span>
                            </button>
                        </div>
                    </div>
                </div>
            @elseif ($photoCount == 3)
                <!-- 3 Photos: Hero Left (6 cols) + 2 Stacked Right (6 cols) -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <!-- Left Hero -->
                    <div class="md:col-span-6 relative rounded-2xl overflow-hidden group h-[340px] sm:h-[450px] cursor-pointer"
                        onclick="openPhotoModal(0)">
                        <img src="{{ $expedition['gallery'][0]['url'] ?? $expedition['image'] }}"
                            alt="{{ $expedition['gallery'][0]['caption'] ?? $expedition['title'] }}"
                            onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/900x900/203a43/ffffff?text=Ekspedisi' }}'"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent">
                        </div>
                    </div>
                    <!-- Right Stacked 2-Grid (Equal height h-[340px] sm:h-[450px]) -->
                    <div class="md:col-span-6 grid grid-cols-1 grid-rows-2 gap-3 h-[340px] sm:h-[450px]">
                        <!-- Top Photo -->
                        <div class="relative rounded-2xl overflow-hidden group h-full cursor-pointer"
                            onclick="openPhotoModal(1)">
                            <img src="{{ $expedition['gallery'][1]['url'] }}"
                                alt="{{ $expedition['gallery'][1]['caption'] ?? 'Dokumentasi Ekspedisi' }}"
                                onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/600x400/294861/ffffff?text=Ekspedisi' }}'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent">
                            </div>
                        </div>
                        <!-- Bottom Photo with Button -->
                        <div class="relative rounded-2xl overflow-hidden group h-full cursor-pointer"
                            onclick="openPhotoModal(2)">
                            <img src="{{ $expedition['gallery'][2]['url'] }}"
                                alt="{{ $expedition['gallery'][2]['caption'] ?? 'Dokumentasi Ekspedisi' }}"
                                onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/600x400/39566e/ffffff?text=Ekspedisi' }}'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 brightness-95" />
                            <div class="absolute inset-0 bg-black/35 flex items-center justify-center p-3">
                                <button type="button"
                                    class="bg-black/55 backdrop-blur-md border border-white/20 text-white text-xs font-semibold px-4 py-2.5 rounded-full flex items-center gap-2 hover:bg-black/75 transition-all shadow-lg">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Lihat 3 Foto Asli</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($photoCount == 4)
                <!-- 4 Photos: Hero Left (6 cols) + 3 Grid Right (6 cols: 1 top wide, 2 bottom split) -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <!-- Left Hero -->
                    <div class="md:col-span-6 relative rounded-2xl overflow-hidden group h-[340px] sm:h-[450px] cursor-pointer"
                        onclick="openPhotoModal(0)">
                        <img src="{{ $expedition['gallery'][0]['url'] ?? $expedition['image'] }}"
                            alt="{{ $expedition['gallery'][0]['caption'] ?? $expedition['title'] }}"
                            onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/900x900/203a43/ffffff?text=Ekspedisi' }}'"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent">
                        </div>
                    </div>
                    <!-- Right 3-Grid (Equal height h-[340px] sm:h-[450px]) -->
                    <div class="md:col-span-6 grid grid-cols-2 grid-rows-2 gap-3 h-[340px] sm:h-[450px]">
                        <div class="col-span-2 relative rounded-2xl overflow-hidden group h-full cursor-pointer"
                            onclick="openPhotoModal(1)">
                            <img src="{{ $expedition['gallery'][1]['url'] }}"
                                alt="{{ $expedition['gallery'][1]['caption'] ?? 'Dokumentasi' }}"
                                onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/600x400/294861/ffffff?text=Ekspedisi' }}'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent">
                            </div>
                        </div>
                        <div class="col-span-1 relative rounded-2xl overflow-hidden group h-full cursor-pointer"
                            onclick="openPhotoModal(2)">
                            <img src="{{ $expedition['gallery'][2]['url'] }}"
                                alt="{{ $expedition['gallery'][2]['caption'] ?? 'Dokumentasi' }}"
                                onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/600x400/39566e/ffffff?text=Ekspedisi' }}'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent">
                            </div>
                        </div>
                        <div class="col-span-1 relative rounded-2xl overflow-hidden group h-full cursor-pointer"
                            onclick="openPhotoModal(3)">
                            <img src="{{ $expedition['gallery'][3]['url'] }}"
                                alt="{{ $expedition['gallery'][3]['caption'] ?? 'Dokumentasi' }}"
                                onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/600x400/232526/ffffff?text=Ekspedisi' }}'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 brightness-95" />
                            <div class="absolute inset-0 bg-black/35 flex items-center justify-center p-2">
                                <button type="button"
                                    class="bg-black/55 backdrop-blur-md border border-white/20 text-white text-[11px] sm:text-xs font-semibold px-3 py-2 rounded-full flex items-center gap-1.5 hover:bg-black/75 transition-all shadow-lg">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Lihat 4 Foto</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- 5+ Photos: Full Bento Grid (Matches referensi detail-gunung.html) -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <!-- Big Hero Image Left -->
                    <div class="md:col-span-6 relative rounded-2xl overflow-hidden group h-[340px] sm:h-[450px] cursor-pointer"
                        onclick="openPhotoModal(0)">
                        <img src="{{ $expedition['gallery'][0]['url'] ?? $expedition['image'] }}"
                            alt="{{ $expedition['gallery'][0]['caption'] ?? $expedition['title'] }}"
                            onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/900x900/203a43/ffffff?text=Sabana+Merbabu' }}'"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent">
                        </div>
                    </div>

                    <!-- Right 4-Grid -->
                    <div class="md:col-span-6 grid grid-cols-2 gap-3 h-[340px] sm:h-[450px]">
                        <!-- Top Left -->
                        <div class="relative rounded-2xl overflow-hidden group h-full cursor-pointer"
                            onclick="openPhotoModal(1)">
                            <img src="{{ $expedition['gallery'][1]['url'] }}"
                                alt="{{ $expedition['gallery'][1]['caption'] ?? 'Dokumentasi Ekspedisi' }}"
                                onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/600x400/294861/ffffff?text=Dokumentasi' }}'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy" />
                        </div>

                        <!-- Top Right -->
                        <div class="relative rounded-2xl overflow-hidden group h-full cursor-pointer"
                            onclick="openPhotoModal(2)">
                            <img src="{{ $expedition['gallery'][2]['url'] }}"
                                alt="{{ $expedition['gallery'][2]['caption'] ?? 'Dokumentasi Ekspedisi' }}"
                                onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/600x400/39566e/ffffff?text=Dokumentasi' }}'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy" />
                        </div>

                        <!-- Bottom Left -->
                        <div class="relative rounded-2xl overflow-hidden group h-full cursor-pointer"
                            onclick="openPhotoModal(3)">
                            <img src="{{ $expedition['gallery'][3]['url'] }}"
                                alt="{{ $expedition['gallery'][3]['caption'] ?? 'Dokumentasi Ekspedisi' }}"
                                onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/600x400/536976/ffffff?text=Dokumentasi' }}'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy" />
                        </div>

                        <!-- Bottom Right with "Lihat 12+ Foto Asli" -->
                        <div class="relative rounded-2xl overflow-hidden group h-full cursor-pointer"
                            onclick="openPhotoModal(4)">
                            <img src="{{ $expedition['gallery'][4]['url'] }}"
                                alt="{{ $expedition['gallery'][4]['caption'] ?? 'Dokumentasi Ekspedisi' }}"
                                onerror="this.src='{{ $expedition['image'] ?? 'https://placehold.co/600x400/232526/ffffff?text=Dokumentasi' }}'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 brightness-90"
                                loading="lazy" />
                            <div class="absolute inset-0 bg-black/35 flex items-center justify-center p-3">
                                <button type="button"
                                    class="bg-black/55 backdrop-blur-md border border-white/20 text-white text-xs font-semibold px-4 py-2.5 rounded-full flex items-center gap-2 hover:bg-black/75 transition-all shadow-lg">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Lihat {{ $photoCount > 5 ? $photoCount . '+ ' : '' }}Foto Asli</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        <!-- Two Columns Layout: Main Content (Left) & Booking Sticky Sidebar (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 relative">

            <!-- LEFT CONTENT COLUMN (8 cols / ~66%) -->
            <div class="lg:col-span-8 space-y-10">

                <!-- Quick Nav Pills (Normal static flow - tidak sticky) -->
                <div
                    class="inline-flex bg-gray-200/60 p-1.5 rounded-full border border-hairline/80 max-w-full overflow-x-auto text-xs sm:text-sm font-semibold text-muted shadow-xs">
                    <a href="#overview"
                        class="quick-nav-pill bg-white text-ink-heading px-5 py-2 rounded-full shadow-sm transition whitespace-nowrap">
                        Ringkasan
                    </a>
                    <a href="#elevasi"
                        class="quick-nav-pill px-5 py-2 hover:text-ink-heading transition-colors whitespace-nowrap">
                        Elevasi & Rute
                    </a>
                    <a href="#itinerary"
                        class="quick-nav-pill px-5 py-2 hover:text-ink-heading transition-colors whitespace-nowrap">
                        Itinerary
                    </a>
                    <a href="#fasilitas"
                        class="quick-nav-pill px-5 py-2 hover:text-ink-heading transition-colors whitespace-nowrap">
                        Fasilitas
                    </a>
                </div>

                <!-- SECTION 1: OVERVIEW -->
                <section id="overview" class="space-y-6 pt-2 scroll-mt-28">
                    <h2 class="text-2xl font-extrabold text-ink-heading tracking-tight">Overview</h2>
                    <p class="text-body text-sm leading-relaxed text-justify">
                        {{ $expedition['overview'] }}
                    </p>

                    <!-- 4 Stat Summary Cards (Dinamis sesuai jalur yang dipilih) -->
                    <div id="overview-stats-grid"
                        class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 pt-2 transition-all duration-300">
                        @php
                            $activeRoute = $expedition['routes'][0] ?? null;
                            $currentStats = $activeRoute['stats'] ?? $expedition['stats'];
                        @endphp
                        @foreach ($currentStats as $stat)
                            <div
                                class="border border-hairline rounded-2xl p-4 text-center bg-surface-card shadow-xs hover:border-primary/40 hover:shadow-sm transition-all duration-200">
                                <div
                                    class="w-9 h-9 mx-auto mb-2.5 rounded-xl bg-primary-subtle text-primary flex items-center justify-center">
                                    @if ($stat['icon'] === 'milestone')
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                    @elseif($stat['icon'] === 'clock')
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($stat['icon'] === 'thermometer')
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    @elseif($stat['icon'] === 'droplet')
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                    @endif
                                </div>
                                <p class="text-[11px] font-medium text-muted mb-0.5">{{ $stat['label'] }}</p>
                                <p class="text-sm font-bold text-ink-heading">{{ $stat['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- SECTION 2: ELEVASI & RUTE PENDAKIAN (Dinamis sesuai jalur) -->
                @php
                    $currentElevProfile = $activeRoute['elevation_profile'] ?? $expedition['elevation_profile'];
                @endphp
                <section id="elevasi" class="space-y-4 pt-2 scroll-mt-28">
                    <h2 id="elevation-section-title"
                        class="text-2xl font-extrabold text-ink-heading tracking-tight transition-all duration-300">
                        {{ $currentElevProfile['title'] }}
                    </h2>

                    <div
                        class="border border-hairline rounded-3xl p-6 bg-surface-card shadow-xs transition-all duration-300">
                        <!-- Elevation Graph Illustration (Responsive SVG) -->
                        <div class="w-full h-60 sm:h-64 py-2 relative flex items-center justify-center">
                            <svg id="elevation-svg" viewBox="0 0 700 240" class="w-full h-full overflow-visible"
                                preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="elevationLineGrad" x1="0" y1="0"
                                        x2="1" y2="0">
                                        <stop offset="0%" stop-color="#D97706" />
                                        <stop offset="100%" stop-color="#A0401C" />
                                    </linearGradient>
                                    <linearGradient id="areaGrad" x1="0" y1="0" x2="0"
                                        y2="1">
                                        <stop offset="0%" stop-color="#A0401C" stop-opacity="0.25" />
                                        <stop offset="100%" stop-color="#ffffff" stop-opacity="0.0" />
                                    </linearGradient>
                                </defs>

                                <!-- Soft Mountain Silhouette Area -->
                                <path id="elevation-area-path" d="{{ $currentElevProfile['area'] }}"
                                    fill="url(#areaGrad)" />

                                <!-- Elevation Line -->
                                <path id="elevation-line-path" d="{{ $currentElevProfile['path'] }}" fill="none"
                                    stroke="url(#elevationLineGrad)" stroke-width="3.5" stroke-linecap="round"
                                    stroke-linejoin="round" />

                                <!-- Points & Labels -->
                                <g id="elevation-points-group">
                                    @foreach ($currentElevProfile['points'] as $index => $point)
                                        @php
                                            $isLast = $index === count($currentElevProfile['points']) - 1;
                                        @endphp
                                        <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}"
                                            r="{{ $isLast ? '6' : '4.5' }}"
                                            fill="{{ $isLast ? '#A0401C' : '#FFFFFF' }}"
                                            stroke="{{ $isLast ? '#FFFFFF' : '#A0401C' }}" stroke-width="2.5" />
                                        <text x="{{ $point['x'] }}" y="{{ $point['y'] - 12 }}" text-anchor="middle"
                                            class="text-[10px] font-bold fill-muted-soft">
                                            {{ $point['elevation'] }}
                                        </text>
                                        <text x="{{ $point['x'] }}" y="{{ $point['y'] + 18 }}" text-anchor="middle"
                                            class="text-[11px] font-semibold fill-body-strong">
                                            {{ $point['name'] }}
                                        </text>
                                    @endforeach
                                </g>
                            </svg>
                        </div>

                        <!-- Route Notes & Warning Badges -->
                        <div id="elevation-notes-container"
                            class="grid grid-cols-1 md:grid-cols-3 gap-2.5 pt-5 border-t border-hairline-soft text-xs transition-all duration-300">
                            @foreach ($currentElevProfile['notes'] as $note)
                                <div
                                    class="flex items-center gap-2.5 {{ $note['badge_class'] }} border px-3.5 py-2.5 rounded-2xl">
                                    <div
                                        class="w-6 h-6 rounded-full {{ $note['icon_class'] }} flex items-center justify-center shrink-0">
                                        @if ($note['type'] === 'water')
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                            </svg>
                                        @elseif($note['type'] === 'wind')
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="text-[11px] leading-tight">
                                        <span class="font-bold block">{{ $note['title'] }}</span>
                                        <span class="opacity-90">{{ $note['desc'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- SECTION 3: ITINERARY 2D1N (Dinamis sesuai jalur) -->
                @php
                    $currentItinerary = $activeRoute['itinerary'] ?? $expedition['itinerary'];
                @endphp
                <section id="itinerary" class="space-y-4 pt-2 scroll-mt-28">
                    <h2 id="itinerary-section-title"
                        class="text-2xl font-extrabold text-ink-heading tracking-tight transition-all duration-300">
                        {{ $currentItinerary['title'] }}
                    </h2>

                    <div id="itinerary-days-container"
                        class="relative pl-6 space-y-6 before:content-[''] before:absolute before:top-4 before:bottom-4 before:left-[11px] before:w-[2px] before:bg-hairline transition-all duration-300">
                        @foreach ($currentItinerary['days'] as $dIndex => $day)
                            <div class="relative group">
                                <!-- Dot indicator -->
                                <div
                                    class="absolute -left-6 top-1.5 w-[22px] h-[22px] rounded-full {{ $dIndex === 0 ? 'bg-primary' : 'bg-surface-dark' }} border-4 border-white shadow-sm flex items-center justify-center">
                                </div>

                                <!-- Card content -->
                                <div class="bg-surface-card border border-hairline rounded-3xl p-5 sm:p-6 shadow-xs">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-base font-bold text-ink-heading">
                                            {{ $day['title'] }}
                                        </h3>
                                        <span
                                            class="px-3 py-0.5 rounded-full bg-primary-subtle text-primary text-xs font-bold">
                                            {{ $day['day'] }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-muted mb-4 leading-relaxed">
                                        {{ $day['description'] }}
                                    </p>

                                    <!-- Schedule timeline items -->
                                    <div
                                        class="space-y-2.5 text-xs text-body-strong pt-2 border-t border-hairline-soft">
                                        @foreach ($day['timeline'] as $item)
                                            <div class="flex items-center gap-3">
                                                <svg class="w-3.5 h-3.5 text-muted-soft shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span
                                                    class="font-bold text-ink-heading w-12 shrink-0">{{ $item['time'] }}</span>
                                                <span class="text-body font-medium">{{ $item['activity'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- SECTION 4: FASILITAS TERMASUK & TIDAK TERMASUK -->
                <section id="fasilitas" class="space-y-4 pt-2 scroll-mt-28">
                    <h2 class="text-2xl font-extrabold text-ink-heading tracking-tight">Fasilitas Ekspedisi</h2>

                    <!-- Termasuk (Included) Card -->
                    <div class="border border-hairline rounded-3xl p-6 bg-surface-card shadow-xs">
                        <div
                            class="flex items-center justify-center gap-1.5 text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200/60 rounded-full py-1.5 px-4 w-fit mx-auto mb-6">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Termasuk (Included)</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-xs text-body">
                            @foreach ($expedition['facilities']['included'] as $category => $items)
                                <div>
                                    <h4 class="font-bold text-ink-heading mb-2.5 flex items-center gap-2 text-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $category }}
                                    </h4>
                                    <ul class="space-y-2 pl-3.5 border-l border-emerald-100">
                                        @foreach ($items as $facility)
                                            <li class="flex items-center gap-2">
                                                <span class="text-emerald-600 font-bold text-sm">✔</span>
                                                <span class="font-medium text-body-strong">{{ $facility }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tidak Termasuk (Exclude) Card -->
                    <div class="border border-hairline rounded-3xl p-6 bg-surface-card shadow-xs">
                        <div
                            class="flex items-center justify-center gap-1.5 text-xs font-bold text-red-700 bg-red-50 border border-red-200/60 rounded-full py-1.5 px-4 w-fit mx-auto mb-6">
                            <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Tidak Termasuk (Exclude)</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-xs text-body">
                            @foreach ($expedition['facilities']['excluded'] as $category => $items)
                                <div>
                                    <h4 class="font-bold text-ink-heading mb-2.5 flex items-center gap-2 text-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                        {{ $category }}
                                    </h4>
                                    <ul class="space-y-2 pl-3.5 border-l border-red-100">
                                        @foreach ($items as $facility)
                                            <li class="flex items-center gap-2">
                                                <span class="text-red-500 font-bold text-sm">✕</span>
                                                <span class="font-medium text-muted">{{ $facility }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </section>

            </div>

            <!-- RIGHT SIDEBAR: BOOKING CARD (Sticky Sidebar) -->
            <aside class="lg:col-span-4 w-full">
                <div
                    class="sticky top-24 z-20 bg-surface-card border border-hairline rounded-3xl p-4 sm:p-5 shadow-sm space-y-3">

                    <!-- Config Body -->
                    <div class="space-y-3">

                        <!-- Card Title -->
                        <div class="flex items-center gap-2 text-ink-heading font-bold text-sm">
                            <div
                                class="w-6 h-6 rounded-full bg-primary-subtle text-primary flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                                </svg>
                            </div>
                            <span class="text-sm tracking-tight">Atur Perjalananmu!</span>
                        </div>

                        <!-- 1. Tipe Pendakian (Compact) -->
                        <div class="space-y-1">
                            <label class="block text-[10.5px] font-bold text-ink-heading uppercase tracking-wider">1.
                                Tipe Pendakian</label>
                            <div class="grid grid-cols-2 gap-2">
                                <!-- Camping Button -->
                                <button type="button" id="btn-tipe-camping" onclick="setHikeType('camping')"
                                    class="border-2 border-ink-heading bg-gray-50/80 rounded-xl p-2 text-left transition-all cursor-pointer">
                                    <div class="flex items-center justify-between">
                                        <span id="tipe-camping-label"
                                            class="block text-xs font-bold text-ink-heading">Camping</span>
                                        <span id="tipe-camping-badge"
                                            class="text-[10px] text-primary font-bold">{{ $currentItinerary['duration_label'] ?? '2D1N' }}</span>
                                    </div>
                                    <span id="tipe-camping-subtitle"
                                        class="block text-[10px] text-muted-soft">{{ !empty($currentItinerary['days_count']) && $currentItinerary['days_count'] > 1 ? $currentItinerary['days_count'] - 1 . ' Malam Tenda' : '1 Malam Tenda' }}</span>
                                </button>
                                <!-- Tek-tok Button -->
                                <button type="button" id="btn-tipe-tektok" onclick="setHikeType('tektok')"
                                    class="border border-hairline hover:border-gray-300 rounded-xl p-2 text-left transition-all cursor-pointer">
                                    <div class="flex items-center justify-between">
                                        <span id="tipe-tektok-label"
                                            class="block text-xs font-bold text-muted">Tek-tok</span>
                                        <span class="text-[10px] text-muted font-medium">1 Day</span>
                                    </div>
                                    <span class="block text-[10px] text-muted-soft">Tanpa Menginap</span>
                                </button>
                            </div>
                        </div>

                        <!-- 2. Pilih Jalur (Via) — Standard Single-Select Dropdown (UI-kit-dropdown.md Varian 2) -->
                        <div class="space-y-1">
                            <label class="block text-[10.5px] font-bold text-ink-heading uppercase tracking-wider">2.
                                Pilih Jalur (Via)</label>
                            <div class="relative w-full">
                                <!-- Trigger Kapsul -->
                                <div id="jalur-select-trigger" onclick="toggleJalurDropdown()"
                                    class="flex items-center justify-between gap-2 w-full px-3 py-1.5 bg-white border border-hairline hover:border-gray-300 rounded-xl cursor-pointer transition select-none shadow-2xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <svg class="w-3.5 h-3.5 text-muted-soft shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                        <span id="jalur-display-label"
                                            class="text-xs font-semibold text-body-strong block truncate">
                                            {{ $expedition['routes'][0]['name'] }}
                                            ({{ $expedition['routes'][0]['badge'] }})
                                        </span>
                                    </div>
                                    <input type="hidden" name="jalur" id="jalur-hidden-input"
                                        value="{{ $expedition['routes'][0]['name'] }}" />
                                    <svg id="jalur-chevron"
                                        class="w-3.5 h-3.5 text-muted transition-transform duration-200 shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <!-- Elevated Menu -->
                                <div id="jalur-dropdown-list"
                                    class="hidden absolute left-0 right-0 mt-1 bg-white border border-hairline rounded-2xl shadow-xl py-1.5 z-50 text-xs overflow-hidden">
                                    <div
                                        class="px-3 py-1 text-[10px] uppercase font-bold text-muted-soft tracking-wider">
                                        Pilih Jalur Pendakian
                                    </div>
                                    @foreach ($expedition['routes'] as $route)
                                        <button type="button"
                                            onclick="selectJalurOption('{{ $route['id'] }}', '{{ $route['name'] }}', '{{ $route['badge'] }}')"
                                            class="jalur-option w-full text-left px-3.5 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer transition-colors">
                                            <span class="font-semibold">{{ $route['name'] }}</span>
                                            <span
                                                class="text-[10px] text-muted-soft font-normal bg-gray-100 px-2 py-0.5 rounded-full">{{ $route['badge'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- 3. Jenis Paket -->
                        <div class="space-y-1">
                            <label class="block text-[10.5px] font-bold text-ink-heading uppercase tracking-wider">3.
                                Jenis Paket</label>
                            <div
                                class="bg-gray-100/80 p-1 rounded-xl grid grid-cols-2 text-xs font-semibold text-center">
                                <button type="button" id="btn-paket-open" onclick="setPackageType('open')"
                                    class="bg-white text-ink-heading py-1 rounded-lg shadow-xs transition cursor-pointer text-xs">
                                    Open Trip
                                </button>
                                <button type="button" id="btn-paket-private" onclick="setPackageType('private')"
                                    class="text-muted hover:text-ink-heading py-1 transition cursor-pointer text-xs">
                                    Private Trip
                                </button>
                            </div>
                        </div>

                        <!-- 4. Tanggal Keberangkatan -->
                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <label id="label-departure-section"
                                    class="block text-[10.5px] font-bold text-ink-heading uppercase tracking-wider">4.
                                    Tanggal Terdekat</label>
                                <span id="badge-private-date-notice"
                                    class="hidden text-[9.5px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-full">
                                    Bebas Pilih Tanggal
                                </span>
                            </div>

                            <!-- Open Trip Fixed Date Container -->
                            <div id="container-open-date" class="relative">
                                @if (!empty($expedition['has_open_schedule']))
                                    <input type="text" value="{{ $expedition['departure_date'] }}" readonly
                                        class="w-full bg-white border border-hairline rounded-xl py-1.5 px-3 text-xs text-body-strong font-medium focus:outline-none cursor-default" />
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-muted-soft">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @else
                                    <div
                                        class="w-full bg-amber-50/80 border border-amber-200/90 rounded-xl py-2 px-3 text-xs text-amber-900 font-medium flex items-center justify-between">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Belum Ada Jadwal
                                        </span>
                                        <span
                                            class="text-[9.5px] bg-amber-200/70 text-amber-900 px-2 py-0.5 rounded-full font-bold">Segera
                                            Hadir</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Private Trip Free Date Container -->
                            <div id="container-private-date" class="hidden space-y-1">
                                <div class="relative">
                                    <input type="date" id="input-sidebar-private-date"
                                        min="{{ now()->addDays(1)->toDateString() }}"
                                        value="{{ now()->addDays(7)->toDateString() }}"
                                        onchange="handleSidebarDateChange(this.value)"
                                        class="w-full bg-white border border-primary/60 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl py-1.5 px-3 text-xs text-ink-heading font-semibold focus:outline-none cursor-pointer shadow-xs" />
                                </div>
                                <p class="text-[10px] text-muted-soft">
                                    Pilih tanggal bebas sesuai agenda rombongan privat Anda.
                                </p>
                            </div>
                        </div>

                        <!-- Target Kuota Peserta (Hanya untuk Open Trip) -->
                        <div id="container-open-quota" class="space-y-1 pt-0.5">
                            @if (!empty($expedition['has_open_schedule']))
                                <div class="flex justify-between text-[10.5px] font-bold">
                                    <span class="text-ink-heading">Peserta saat ini</span>
                                    <span class="text-primary">{{ $expedition['quota_current'] }} dari {{ $expedition['quota_max'] }} peserta</span>
                                </div>
                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    @php
                                        $quotaPercent = min(
                                            100,
                                            round(
                                                ($expedition['quota_current'] / max(1, $expedition['quota_max'])) * 100,
                                            ),
                                        );
                                    @endphp
                                    <div class="bg-primary h-1.5 rounded-full transition-all duration-500"
                                        style="width: {{ $quotaPercent }}%"></div>
                                </div>
                                <p class="text-[10px] text-right text-muted-soft">
                                    Tersisa {{ max(0, $expedition['quota_max'] - $expedition['quota_current']) }} slot lagi untuk keberangkatan
                                </p>
                            @else
                                <p class="text-[10.5px] text-muted-soft italic py-1">
                                    Slot kuota tiket open trip belum dibuka oleh operator.
                                </p>
                            @endif
                        </div>

                        <!-- Private Trip Exclusive Info (Hanya untuk Private Trip) -->
                        <div id="container-private-info"
                            class="hidden space-y-1 pt-0.5 bg-emerald-50/60 border border-emerald-100/80 rounded-xl p-2.5">
                            <div class="flex items-center gap-1.5 text-emerald-900 text-[11px] font-bold">
                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Eksklusif Rombongan Anda</span>
                            </div>
                            <p class="text-[10px] text-emerald-700 leading-snug">
                                Rombongan tidak digabung peserta lain. Porter dan pemandu khusus dialokasikan di tanggal
                                pilihan Anda.
                            </p>
                        </div>

                    </div>

                    <!-- Pinned Bottom Action (Harga + Tombol Booking Sekarang SELALU Terlihat & Tidak Pernah Ketutupan) -->
                    <div class="shrink-0 pt-3 border-t border-hairline space-y-2 bg-surface-card">

                        <!-- Dynamic Pricing Display -->
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs text-muted font-medium">Harga Saat Ini</span>
                            <div class="text-right shrink-0">
                                <span id="display-price"
                                    class="text-xl font-extrabold text-ink-heading whitespace-nowrap">
                                    {{ $expedition['price_formatted'] }}
                                </span>
                                <span class="text-[10px] text-muted block -mt-1 font-normal">/ pax</span>
                            </div>
                        </div>

                        <!-- Booking CTA Button (Always Prominent & Clickable) -->
                        <button id="btn-booking" type="button" onclick="handleBookingClick()"
                            class="w-full bg-primary hover:bg-primary-hover active:bg-primary-active active:scale-[0.99] transition-all text-white py-2.5 px-4 rounded-full font-bold text-sm shadow-md hover:shadow-lg flex items-center justify-center gap-2 cursor-pointer">
                            <span>Booking Sekarang</span>
                            <span class="text-base">→</span>
                        </button>

                        <!-- 100% Refund Guarantee note -->
                        <div class="flex items-center justify-center gap-1 text-[10px] text-muted text-center pt-0.5">
                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>100% Refund jika trip batal akibat cuaca ekstrem</span>
                        </div>

                    </div>

                </div>
            </aside>

        </div>
    </main>

    <!-- MOBILE STICKY BOTTOM BOOKING BAR (Muncul di layar < lg agar tombol booking tidak pernah tertutup atau terpotong) -->
    <div
        class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-hairline px-4 py-3 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] flex items-center justify-between gap-4">
        <div>
            <span class="text-[10.5px] font-medium text-muted block">Harga Saat Ini</span>
            <span id="mobile-display-price" class="text-base font-extrabold text-ink-heading whitespace-nowrap">
                {{ $expedition['price_formatted'] }}
            </span>
        </div>
        <button type="button" onclick="handleBookingClick()"
            class="bg-primary hover:bg-primary-hover active:bg-primary-active text-white text-xs font-bold px-5 py-2.5 rounded-full shadow-md flex items-center gap-1.5 cursor-pointer shrink-0">
            <span>Booking Sekarang</span>
            <span>→</span>
        </button>
    </div>

    <!-- Footer Component -->
    <footer class="w-full border-t border-hairline/70 bg-canvas-alt py-12 text-center text-muted">
        <div class="max-w-xl mx-auto px-4 flex flex-col items-center gap-4">
            <!-- Brand Logo -->
            <div class="flex items-center gap-2 font-bold text-ink-heading text-base tracking-tight">
                <svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                </svg>
                <span>MiddleTrip</span>
            </div>

            <!-- Links -->
            <div class="flex flex-wrap justify-center items-center gap-6 text-xs text-muted font-medium">
                <a href="{{ route('home') }}" class="hover:text-primary transition">Home</a>
                <a href="{{ route('ekspedisi.index') }}" class="hover:text-primary transition">Ekspedisi</a>
                <a href="{{ route('home') }}#contact" class="hover:text-primary transition">Kontak</a>
                <a href="#" class="hover:text-primary transition">Instagram</a>
                <a href="#" class="hover:text-primary transition">Kebijakan Privasi</a>
            </div>

            <!-- Copyright -->
            <p class="text-[11px] text-muted-soft mt-1">
                &copy; {{ date('Y') }} MiddleTrip Expedition Co. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Configuration for Booking Modal -->
    <script>
        window.bookingModalConfig = @json($bookingConfig);
    </script>

    <!-- Booking Modal: Pesan Tiket (Matches referensi modal-pemesanan.html) -->
    <div id="bookingModal" x-data="bookingModalComponent(window.bookingModalConfig)"
        class="fixed inset-0 bg-black/45 backdrop-blur-[2px] flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300 px-4 py-6 overflow-y-auto no-scrollbar">
        <div
            class="bg-white rounded-[28px] p-6 sm:p-7 max-w-4xl w-full shadow-2xl transform scale-95 transition-all duration-300 my-auto border border-slate-100 relative max-h-[92vh] overflow-y-auto no-scrollbar">

            <!-- Modal Header -->
            <div class="flex items-start justify-between pb-4 border-b border-slate-100/80 mb-5">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Pesan Tiket</h2>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $expedition['title'] }}</p>
                </div>
                <button type="button" onclick="closeBookingModal()"
                    class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors text-sm font-bold cursor-pointer"
                    aria-label="Tutup Modal">
                    ✕
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">

                <!-- LEFT COLUMN: Tiket Details, Meeting Point, Tambahan (7 Cols) -->
                <div class="md:col-span-7 space-y-5 text-xs">

                    <!-- Section: Details & Participant Counter -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-900 text-xs">Details</h4>
                            <span class="text-[10.5px] font-bold px-2.5 py-0.5 rounded-full"
                                :class="hikingType === 'tektok' ? 'bg-amber-50 text-amber-700 border border-amber-200' :
                                    'bg-primary-subtle text-primary border border-primary/20'"
                                x-text="hikingType === 'tektok' ? 'Tek-tok' : 'Camping'">
                            </span>
                        </div>
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <span class="text-slate-800 font-semibold text-xs">Harga Saat Ini : <span
                                    class="text-slate-900 font-bold"
                                    x-text="formatCurrency(currentPricePerPax()) + '/orang'"></span></span>

                            <!-- Stepper Counter -->
                            <div
                                class="inline-flex items-center gap-2 bg-slate-50 border border-slate-200 px-2 py-1 rounded-full">
                                <button type="button" @click="changePax(-1)"
                                    class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center hover:bg-primary-hover transition-colors font-bold text-xs cursor-pointer">
                                    −
                                </button>
                                <span id="paxCountDisplay"
                                    class="font-bold text-slate-800 text-xs min-w-12 text-center"
                                    x-text="paxCount + ' Orang'">1 Orang</span>
                                <button type="button" @click="changePax(1)"
                                    class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center hover:bg-primary-hover transition-colors font-bold text-xs cursor-pointer">
                                    +
                                </button>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400">Semakin banyak peserta kuota semakin murah</p>
                    </div>

                    <!-- Section: Meeting Point (Penjemputan) -->
                    <div class="space-y-2">
                        <h4 class="font-bold text-slate-900 text-xs">Meeting Point (Penjemputan)</h4>
                        <div class="space-y-2" id="meetingPointContainer">
                            <template x-for="mp in meetingPoints" :key="mp.id">
                                <label
                                    class="flex items-center justify-between p-3 rounded-2xl border border-slate-200 hover:border-slate-300 cursor-pointer transition-colors bg-white group">
                                    <div class="flex items-center gap-2.5">
                                        <input type="radio" name="meetingPoint" :value="mp.id"
                                            :checked="meetingPointId === mp.id" @change="selectMeetingPoint(mp)"
                                            class="w-4 h-4 border-slate-300 text-primary focus:ring-primary focus:ring-offset-0 accent-primary cursor-pointer transition-colors">
                                        <span class="font-medium text-slate-800 text-xs" x-text="mp.name"></span>
                                    </div>
                                    <span class="text-[11px] font-semibold bg-slate-100 px-2.5 py-0.5 rounded-full"
                                        :class="mp.additional_price_per_pax > 0 ? 'text-slate-600' : 'text-slate-500'"
                                        x-text="formatBadgePrice(mp.additional_price_per_pax)"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Section: Tambahan (Add-ons) -->
                    <div class="space-y-2" x-show="addons.length > 0">
                        <h4 class="font-bold text-slate-900 text-xs">Tambahan</h4>
                        <div class="space-y-2" id="addonContainer">
                            <template x-for="addon in addons" :key="addon.id">
                                <label
                                    class="flex items-center justify-between p-3 rounded-2xl border border-slate-200 hover:border-slate-300 cursor-pointer transition-colors bg-white">
                                    <div class="flex items-center gap-2.5">
                                        <input type="checkbox" :checked="!!selectedAddons[addon.id]"
                                            @change="toggleAddon(addon)"
                                            class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary focus:ring-offset-0 accent-primary cursor-pointer transition-colors">
                                        <span class="font-medium text-slate-800 text-xs" x-text="addon.name"></span>
                                    </div>
                                    <span
                                        class="text-[11px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full"
                                        x-text="formatBadgePrice(addon.price)"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Keberangkatan & Rincian Pesanan (5 Cols) -->
                <div class="md:col-span-5 bg-slate-50/70 border border-slate-200/80 rounded-2xl p-4 sm:p-5 space-y-4">

                    <!-- Keberangkatan Box -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-900 text-xs">Keberangkatan</h4>
                            <span x-show="tripType === 'private'"
                                class="text-[9.5px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-full">
                                Bebas Atur Tanggal
                            </span>
                        </div>
                        <div class="space-y-1.5">
                            <!-- Open Trip: Tanggal batch terjadwal -->
                            <div x-show="tripType === 'open'"
                                class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 shadow-2xs flex items-center justify-between">
                                <span x-text="currentDepartureDateShort">15/08/2026</span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <!-- Private Trip: Tanggal Bebas Dipilih Pemesan -->
                            <div x-show="tripType === 'private'" class="space-y-1">
                                <input type="date" x-model="customDepartureDate" :min="minPrivateDate"
                                    @change="onPrivateDateChange()"
                                    class="w-full bg-white border border-primary/60 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 shadow-2xs transition-colors cursor-pointer" />
                                <p class="text-[10px] text-emerald-600 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Jadwal privat eksklusif rombongan
                                </p>
                            </div>
                            <div
                                class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 shadow-2xs flex items-center justify-between">
                                <span id="summaryRoute" x-text="selectedRouteName">Via Selo (Boyolali)</span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Rincian Pesanan -->
                    <div class="space-y-2 pt-1 border-t border-slate-200/60">
                        <h4 class="font-bold text-slate-900 text-xs">Rincian Pesanan</h4>

                        <div class="space-y-1.5 text-xs">
                            <div class="flex items-start justify-between gap-3 text-slate-500 text-[11px]">
                                <span class="min-w-0">Trip Dimulai</span>
                                <span class="font-medium text-slate-700 shrink-0 whitespace-nowrap text-right"
                                    x-text="currentDepartureDateFull">15 Agustus 2026</span>
                            </div>
                            <div class="flex items-start justify-between gap-3 text-slate-500 text-[11px]">
                                <span class="min-w-0">Trip Selesai</span>
                                <span class="font-medium text-slate-700 shrink-0 whitespace-nowrap text-right"
                                    x-text="currentReturnDateFull">16 Agustus 2026</span>
                            </div>
                            <div class="flex items-start justify-between gap-3 text-slate-500 text-[11px]">
                                <span class="min-w-0">Durasi</span>
                                <span class="font-medium text-slate-700 shrink-0 whitespace-nowrap text-right"
                                    x-text="hikingType === 'tektok' ? '1 Hari (Tek-tok)' : '{{ $expedition['duration_days'] ?? 2 }} Hari {{ max(1, ($expedition['duration_days'] ?? 2) - 1) }} Malam'">
                                    {{ $expedition['duration_days'] ?? 2 }} Hari
                                    {{ max(1, ($expedition['duration_days'] ?? 2) - 1) }} Malam
                                </span>
                            </div>

                            <!-- Dynamic Price Items -->
                            <div class="flex items-start justify-between gap-3 text-slate-600 text-[11px] pt-1">
                                <span id="summaryTripLabel" class="min-w-0 break-words leading-snug"
                                    x-text="(tripType === 'open' ? 'Open Trip' : 'Private Trip') + ' • ' + (hikingType === 'tektok' ? 'Tek-tok' : 'Camping') + ' (' + paxCount + 'x)'">Open
                                    Trip (1x)</span>
                                <span id="summaryTripPrice"
                                    class="font-semibold text-slate-800 shrink-0 whitespace-nowrap text-right"
                                    x-text="formatCurrency(ticketTotal())">Rp 500.000</span>
                            </div>

                            <!-- Shuttle fee (if any) -->
                            <div id="summaryShuttleRow"
                                class="flex items-start justify-between gap-3 text-slate-600 text-[11px]"
                                x-show="shuttleTotal() > 0">
                                <span id="summaryShuttleLabel" class="min-w-0 break-words leading-snug"
                                    x-text="shuttleSummaryLabel">Shuttle Fee</span>
                                <span id="summaryShuttlePrice"
                                    class="font-semibold text-slate-800 shrink-0 whitespace-nowrap text-right"
                                    x-text="formatCurrency(shuttleTotal())">Rp 0</span>
                            </div>

                            <!-- Addon Items Container -->
                            <div id="summaryAddonsContainer" class="space-y-1.5 pt-0.5" x-show="addonsTotal() > 0">
                                <template x-for="(addon, id) in selectedAddons" :key="id">
                                    <div class="flex items-start justify-between gap-3 text-slate-600 text-[11px]">
                                        <span class="min-w-0 break-words leading-snug" x-text="addon.name"></span>
                                        <span
                                            class="font-semibold text-slate-800 shrink-0 whitespace-nowrap text-right"
                                            x-text="formatCurrency(addon.price * (addon.quantity || 1))"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Total Section -->
                    <div class="pt-3 border-t border-slate-200/80">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs text-slate-500 font-medium">Current Total</span>
                            <span id="modalCurrentTotal"
                                class="text-base font-extrabold text-slate-900 shrink-0 whitespace-nowrap text-right"
                                x-text="formatCurrency(grandTotal())">Rp 515.000</span>
                        </div>
                    </div>

                    <!-- Error Alert -->
                    <div x-show="errorMessage" x-cloak
                        class="p-2.5 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl">
                        <p class="font-medium" x-text="errorMessage"></p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2 pt-2">
                        <!-- Bayar Sekarang -->
                        <button type="button" @click="submitBooking()" :disabled="isSubmitting"
                            class="w-full bg-primary hover:bg-primary-hover active:scale-[0.99] text-white py-2.5 px-4 rounded-xl font-bold text-xs shadow-sm transition-all text-center block disabled:opacity-50 cursor-pointer">
                            <span x-show="!isSubmitting">Bayar Sekarang</span>
                            <span x-show="isSubmitting" class="flex items-center justify-center gap-1.5">
                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span>Memproses...</span>
                            </span>
                        </button>

                        <!-- Tanya Via Whatsapp -->
                        <a href="https://wa.me/?text=Halo%20MiddleTrip,%20saya%20ingin%20tanya%20tentang%20{{ urlencode($expedition['title']) }}"
                            target="_blank" rel="noopener noreferrer"
                            class="w-full bg-white hover:bg-emerald-50 border border-emerald-500 text-emerald-600 py-2.5 px-4 rounded-xl font-semibold text-xs transition-all flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.42-.101.825z" />
                            </svg>
                            <span>Tanya Via Whatsapp</span>
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Gallery Lightbox Modal -->
    <div id="galleryModal"
        class="fixed inset-0 bg-black/90 backdrop-blur-md flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300 p-4">
        <div class="relative max-w-4xl w-full flex flex-col items-center">
            <!-- Close Button -->
            <button type="button" onclick="closePhotoModal()"
                class="absolute -top-12 right-0 text-white hover:text-gray-300 p-2 text-xl font-bold cursor-pointer">
                ✕ Tutup
            </button>
            <!-- Large Image Container -->
            <div class="w-full max-h-[75vh] flex items-center justify-center overflow-hidden rounded-2xl">
                <img id="lightbox-image" src="" alt="Foto preview"
                    class="max-h-[75vh] w-auto object-contain rounded-2xl shadow-2xl">
            </div>
            <!-- Caption -->
            <p id="lightbox-caption" class="text-white text-sm mt-4 font-medium text-center"></p>
            <!-- Prev & Next Controls -->
            <div class="flex items-center gap-4 mt-4">
                <button type="button" onclick="navigatePhoto(-1)"
                    class="px-4 py-1.5 rounded-full bg-white/20 hover:bg-white/30 text-white text-xs font-bold transition cursor-pointer">
                    ← Sebelumnya
                </button>
                <span id="lightbox-counter" class="text-white/70 text-xs font-mono"></span>
                <button type="button" onclick="navigatePhoto(1)"
                    class="px-4 py-1.5 rounded-full bg-white/20 hover:bg-white/30 text-white text-xs font-bold transition cursor-pointer">
                    Selanjutnya →
                </button>
            </div>
        </div>
    </div>

    <!-- Page Interactive Script -->
    <script>
        // State variables
        const expeditionData = {
            title: "{{ addslashes($expedition['title']) }}",
            mountain: "{{ addslashes($expedition['mountain']) }}",
            departure_date: "{{ $expedition['departure_date'] }}",
            difficulty_badge: "{{ $expedition['difficulty_badge'] ?? '' }}",
            grade: "{{ $expedition['grade'] ?? 'Grade A' }}",
            grade_label: "{{ $expedition['grade_label'] ?? 'Grade A' }}",
            price_camping_open: {{ $expedition['price'] }},
            price_tektok_open: {{ $expedition['price_tektok'] ?? $expedition['price'] * 0.8 }},
            price_camping_private: {{ $expedition['price_private'] ?? $expedition['price'] * 1.5 }},
            price_tektok_private: {{ $expedition['price_private_tektok'] ?? $expedition['price'] * 1.2 }},
            gallery: @json($expedition['gallery'] ?? []),
            routes: @json($expedition['routes'] ?? []),
            defaultStats: @json($expedition['stats'] ?? []),
            defaultElevation: @json($expedition['elevation_profile'] ?? []),
            defaultItinerary: @json($expedition['itinerary'] ?? [])
        };

        let selectedHikeType = 'camping'; // 'camping' or 'tektok'
        let selectedPackage = 'open'; // 'open' or 'private'
        let currentPhotoIndex = 0;
        let selectedRouteId = expeditionData.routes.length > 0 ? expeditionData.routes[0].id : null;

        function formatIDR(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }

        function calculateCurrentPrice() {
            if (selectedHikeType === 'camping' && selectedPackage === 'open') {
                return expeditionData.price_camping_open;
            } else if (selectedHikeType === 'tektok' && selectedPackage === 'open') {
                return expeditionData.price_tektok_open;
            } else if (selectedHikeType === 'camping' && selectedPackage === 'private') {
                return expeditionData.price_camping_private;
            } else {
                return expeditionData.price_tektok_private;
            }
        }

        function updatePriceDisplay() {
            const price = calculateCurrentPrice();
            const formatted = formatIDR(price);
            const displayEl = document.getElementById('display-price');
            if (displayEl) displayEl.innerText = formatted;
            const mobileDisplayEl = document.getElementById('mobile-display-price');
            if (mobileDisplayEl) mobileDisplayEl.innerText = formatted;
        }

        function setHikeType(type) {
            selectedHikeType = type;
            const btnCamping = document.getElementById('btn-tipe-camping');
            const btnTektok = document.getElementById('btn-tipe-tektok');
            const labelCamping = document.getElementById('tipe-camping-label');
            const labelTektok = document.getElementById('tipe-tektok-label');

            if (type === 'camping') {
                if (btnCamping) btnCamping.className =
                    'border-2 border-ink-heading bg-gray-50/80 rounded-xl p-2 text-left transition-all cursor-pointer';
                if (labelCamping) labelCamping.className = 'block text-xs font-bold text-ink-heading';
                if (btnTektok) btnTektok.className =
                    'border border-hairline hover:border-gray-300 rounded-xl p-2 text-left transition-all cursor-pointer';
                if (labelTektok) labelTektok.className = 'block text-xs font-bold text-muted';
            } else {
                if (btnTektok) btnTektok.className =
                    'border-2 border-ink-heading bg-gray-50/80 rounded-xl p-2 text-left transition-all cursor-pointer';
                if (labelTektok) labelTektok.className = 'block text-xs font-bold text-ink-heading';
                if (btnCamping) btnCamping.className =
                    'border border-hairline hover:border-gray-300 rounded-xl p-2 text-left transition-all cursor-pointer';
                if (labelCamping) labelCamping.className = 'block text-xs font-bold text-muted';
            }

            // Update Itinerary Section dynamically based on selected route and hike type
            const curRoute = (expeditionData.routes && expeditionData.routes.length > 0) ?
                (expeditionData.routes.find(r => String(r.id) === String(selectedRouteId)) || expeditionData.routes[0]) :
                null;
            if (curRoute) {
                const rawItinerary = curRoute.itinerary || expeditionData.defaultItinerary;
                const activeItin = (type === 'tektok' && rawItinerary.tektok) ?
                    rawItinerary.tektok :
                    (rawItinerary.camping || rawItinerary);
                renderItinerary(activeItin);
            }

            updatePriceDisplay();
        }

        function setPackageType(type) {
            selectedPackage = type;
            const btnOpen = document.getElementById('btn-paket-open');
            const btnPrivate = document.getElementById('btn-paket-private');

            if (type === 'open') {
                if (btnOpen) btnOpen.className =
                    'bg-white text-ink-heading py-1 rounded-lg shadow-xs transition cursor-pointer text-xs';
                if (btnPrivate) btnPrivate.className =
                    'text-muted hover:text-ink-heading py-1 transition cursor-pointer text-xs';
            } else {
                if (btnPrivate) btnPrivate.className =
                    'bg-white text-ink-heading py-1 rounded-lg shadow-xs transition cursor-pointer text-xs';
                if (btnOpen) btnOpen.className = 'text-muted hover:text-ink-heading py-1 transition cursor-pointer text-xs';
            }

            // Update Kontrol Tanggal & Kuota vs Info Privat di Sidebar
            const labelDeparture = document.getElementById('label-departure-section');
            const badgePrivateNotice = document.getElementById('badge-private-date-notice');
            const containerOpenDate = document.getElementById('container-open-date');
            const containerPrivateDate = document.getElementById('container-private-date');
            const containerOpenQuota = document.getElementById('container-open-quota');
            const containerPrivateInfo = document.getElementById('container-private-info');

            if (type === 'open') {
                if (labelDeparture) labelDeparture.innerText = '4. Tanggal Terdekat';
                if (badgePrivateNotice) badgePrivateNotice.classList.add('hidden');
                if (containerOpenDate) containerOpenDate.classList.remove('hidden');
                if (containerPrivateDate) containerPrivateDate.classList.add('hidden');
                if (containerOpenQuota) containerOpenQuota.classList.remove('hidden');
                if (containerPrivateInfo) containerPrivateInfo.classList.add('hidden');
            } else {
                if (labelDeparture) labelDeparture.innerText = '4. Tanggal Pendakian';
                if (badgePrivateNotice) badgePrivateNotice.classList.remove('hidden');
                if (containerOpenDate) containerOpenDate.classList.add('hidden');
                if (containerPrivateDate) containerPrivateDate.classList.remove('hidden');
                if (containerOpenQuota) containerOpenQuota.classList.add('hidden');
                if (containerPrivateInfo) containerPrivateInfo.classList.remove('hidden');
            }

            // Update status tombol CTA Booking
            const btnBooking = document.getElementById('btn-booking');
            const hasOpenSchedule = window.bookingModalConfig ? window.bookingModalConfig.hasOpenSchedule : true;

            if (btnBooking) {
                if (type === 'open' && !hasOpenSchedule) {
                    btnBooking.className =
                        'w-full bg-slate-100 text-slate-400 py-2.5 px-4 rounded-full font-bold text-xs shadow-xs flex items-center justify-center gap-1.5 cursor-not-allowed border border-slate-200';
                    btnBooking.innerHTML = '<span>Jadwal Open Trip Belum Tersedia</span>';
                } else if (type === 'private' && window.bookingModalConfig && !window.bookingModalConfig.hasPrivateTrip) {
                    btnBooking.className =
                        'w-full bg-slate-100 text-slate-400 py-2.5 px-4 rounded-full font-bold text-xs shadow-xs flex items-center justify-center gap-1.5 cursor-not-allowed border border-slate-200';
                    btnBooking.innerHTML = '<span>Layanan Private Trip Tidak Tersedia</span>';
                } else {
                    btnBooking.className =
                        'w-full bg-primary hover:bg-primary-hover active:bg-primary-active active:scale-[0.99] transition-all text-white py-2.5 px-4 rounded-full font-bold text-sm shadow-md hover:shadow-lg flex items-center justify-center gap-2 cursor-pointer';
                    btnBooking.innerHTML = '<span>Booking Sekarang</span><span class="text-base">→</span>';
                }
            }

            updatePriceDisplay();
        }

        function handleSidebarDateChange(val) {
            window.dispatchEvent(new CustomEvent('sidebar-date-changed', {
                detail: {
                    date: val
                }
            }));
        }

        // Dropdown Jalur
        function toggleJalurDropdown() {
            const menu = document.getElementById('jalur-dropdown-list');
            const chevron = document.getElementById('jalur-chevron');
            const isHidden = menu.classList.contains('hidden');

            if (isHidden) {
                menu.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                menu.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }

        function closeJalurDropdown() {
            const menu = document.getElementById('jalur-dropdown-list');
            const chevron = document.getElementById('jalur-chevron');
            if (menu) menu.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }

        function selectJalurOption(routeId, routeName, badge) {
            selectedRouteId = parseInt(routeId, 10) || routeId;
            document.getElementById('jalur-display-label').innerText = `${routeName} (${badge})`;
            document.getElementById('jalur-hidden-input').value = routeName;
            closeJalurDropdown();

            // Temukan data rute yang dipilih (support string/number id, name, dan slug)
            const route = expeditionData.routes.find(r =>
                String(r.id) === String(routeId) ||
                (r.name && r.name.toLowerCase().trim() === routeName.toLowerCase().trim()) ||
                (r.slug && (r.slug === routeId || r.slug === 'via-' + routeId))
            );

            if (route) {
                updateLeftContentForRoute(route);
                updateHeaderBadges(route);
            }
        }

        function updateHeaderBadges(route) {
            const diffEl = document.getElementById('header-difficulty-text');
            if (diffEl && (route.badge || route.difficulty_badge)) {
                diffEl.innerText = route.difficulty_badge || route.badge;
            }

            const grade = route.grade || expeditionData.grade || 'Grade A';
            const gradeTextEl = document.getElementById('header-grade-text');
            const gradeContainerEl = document.getElementById('header-grade-container');
            const gradeDotEl = document.getElementById('header-grade-dot');

            if (gradeTextEl && gradeContainerEl && gradeDotEl) {
                gradeTextEl.innerText = route.grade_label || (grade === 'Grade A' ? 'Grade A - Jalur Tertata' : (grade ===
                    'Grade B' ? 'Grade B - Jalur Sedang' : 'Grade C - Jalur Berat'));

                gradeContainerEl.className =
                    'inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border shadow-xs transition-colors duration-200';
                gradeDotEl.className = 'w-2 h-2 rounded-full';

                if (grade === 'Grade A') {
                    gradeContainerEl.classList.add('bg-grade-a-bg', 'text-grade-a-text', 'border-grade-a-dot/30');
                    gradeDotEl.classList.add('bg-grade-a-dot');
                } else if (grade === 'Grade B') {
                    gradeContainerEl.classList.add('bg-grade-b-bg', 'text-grade-b-text', 'border-grade-b-dot/30');
                    gradeDotEl.classList.add('bg-grade-b-dot');
                } else if (grade === 'Grade C') {
                    gradeContainerEl.classList.add('bg-grade-c-bg', 'text-grade-c-text', 'border-grade-c-dot/30');
                    gradeDotEl.classList.add('bg-grade-c-dot');
                } else {
                    gradeContainerEl.classList.add('bg-gray-100', 'text-gray-800', 'border-gray-200');
                    gradeDotEl.classList.add('bg-gray-400');
                }
            }
        }

        function updateLeftContentForRoute(route) {
            const stats = route.stats || expeditionData.defaultStats;
            const elevation = route.elevation_profile || expeditionData.defaultElevation;
            const rawItinerary = route.itinerary || expeditionData.defaultItinerary;
            const itinerary = (selectedHikeType === 'tektok' && rawItinerary.tektok) ?
                rawItinerary.tektok :
                (rawItinerary.camping || rawItinerary);

            // 1. Render Stats Grid
            renderStats(stats);

            // 2. Render Elevation Profile & SVG
            renderElevationProfile(elevation);

            // 3. Render Itinerary
            renderItinerary(itinerary);
        }

        function renderStats(stats) {
            const container = document.getElementById('overview-stats-grid');
            if (!container || !stats || !stats.length) return;

            container.classList.add('opacity-40');
            setTimeout(() => {
                container.innerHTML = stats.map(stat => {
                    let iconSvg = '';
                    if (stat.icon === 'milestone') {
                        iconSvg =
                            `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>`;
                    } else if (stat.icon === 'clock') {
                        iconSvg =
                            `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
                    } else if (stat.icon === 'thermometer') {
                        iconSvg =
                            `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>`;
                    } else {
                        iconSvg =
                            `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>`;
                    }

                    return `
                        <div class="border border-hairline rounded-2xl p-4 text-center bg-surface-card shadow-xs hover:border-primary/40 hover:shadow-sm transition-all duration-200">
                            <div class="w-9 h-9 mx-auto mb-2.5 rounded-xl bg-primary-subtle text-primary flex items-center justify-center">
                                ${iconSvg}
                            </div>
                            <p class="text-[11px] font-medium text-muted mb-0.5">${stat.label}</p>
                            <p class="text-sm font-bold text-ink-heading">${stat.value}</p>
                        </div>
                    `;
                }).join('');
                container.classList.remove('opacity-40');
            }, 100);
        }

        function renderElevationProfile(elevation) {
            const titleEl = document.getElementById('elevation-section-title');
            if (titleEl && elevation.title) {
                titleEl.innerText = elevation.title;
            }

            const svgArea = document.getElementById('elevation-area-path');
            const svgLine = document.getElementById('elevation-line-path');
            const pointsGroup = document.getElementById('elevation-points-group');

            if (svgArea && elevation.area) svgArea.setAttribute('d', elevation.area);
            if (svgLine && elevation.path) svgLine.setAttribute('d', elevation.path);

            if (pointsGroup && elevation.points) {
                pointsGroup.innerHTML = '';
                elevation.points.forEach((p, idx) => {
                    const isLast = idx === elevation.points.length - 1;

                    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    circle.setAttribute('cx', p.x);
                    circle.setAttribute('cy', p.y);
                    circle.setAttribute('r', isLast ? '6' : '4.5');
                    circle.setAttribute('fill', isLast ? '#A0401C' : '#FFFFFF');
                    circle.setAttribute('stroke', isLast ? '#FFFFFF' : '#A0401C');
                    circle.setAttribute('stroke-width', '2.5');
                    pointsGroup.appendChild(circle);

                    const textElev = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                    textElev.setAttribute('x', p.x);
                    textElev.setAttribute('y', p.y - 12);
                    textElev.setAttribute('text-anchor', 'middle');
                    textElev.setAttribute('class', 'text-[10px] font-bold fill-muted-soft');
                    textElev.textContent = p.elevation;
                    pointsGroup.appendChild(textElev);

                    const textName = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                    textName.setAttribute('x', p.x);
                    textName.setAttribute('y', p.y + 18);
                    textName.setAttribute('text-anchor', 'middle');
                    textName.setAttribute('class', 'text-[11px] font-semibold fill-body-strong');
                    textName.textContent = p.name;
                    pointsGroup.appendChild(textName);
                });
            }

            const notesContainer = document.getElementById('elevation-notes-container');
            if (notesContainer && elevation.notes) {
                notesContainer.innerHTML = elevation.notes.map(note => {
                    let iconSvg = '';
                    if (note.type === 'water') {
                        iconSvg =
                            `<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>`;
                    } else if (note.type === 'wind') {
                        iconSvg =
                            `<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`;
                    } else {
                        iconSvg =
                            `<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" /></svg>`;
                    }
                    return `
                        <div class="flex items-center gap-2.5 ${note.badge_class} border px-3.5 py-2.5 rounded-2xl">
                            <div class="w-6 h-6 rounded-full ${note.icon_class} flex items-center justify-center shrink-0">
                                ${iconSvg}
                            </div>
                            <div class="text-[11px] leading-tight">
                                <span class="font-bold block">${note.title}</span>
                                <span class="opacity-90">${note.desc}</span>
                            </div>
                        </div>
                    `;
                }).join('');
            }
        }

        function renderItinerary(itinerary) {
            const titleEl = document.getElementById('itinerary-section-title');
            if (titleEl && itinerary.title) {
                titleEl.innerText = itinerary.title;
            }

            const campingBadgeEl = document.getElementById('tipe-camping-badge');
            const campingSubEl = document.getElementById('tipe-camping-subtitle');
            if (campingBadgeEl && itinerary && selectedHikeType === 'camping') {
                const daysCount = itinerary.days ? itinerary.days.length : 2;
                const durLabel = itinerary.duration_label || (daysCount === 1 ? '1D' : `${daysCount}D${daysCount - 1}N`);
                campingBadgeEl.innerText = durLabel;
                if (campingSubEl) {
                    campingSubEl.innerText = daysCount === 1 ? 'Tanpa Menginap' : `${daysCount - 1} Malam Tenda`;
                }
            }

            const daysContainer = document.getElementById('itinerary-days-container');
            if (daysContainer && itinerary.days) {
                daysContainer.innerHTML = itinerary.days.map((day, dIndex) => {
                    const markerColor = dIndex === 0 ? 'bg-primary' : 'bg-surface-dark';
                    const timelineHtml = (day.timeline || []).map(item => `
                        <div class="flex items-center gap-3">
                            <svg class="w-3.5 h-3.5 text-muted-soft shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-bold text-ink-heading w-12 shrink-0">${item.time}</span>
                            <span class="text-body font-medium">${item.activity}</span>
                        </div>
                    `).join('');

                    return `
                        <div class="relative group">
                            <div class="absolute -left-6 top-1.5 w-[22px] h-[22px] rounded-full ${markerColor} border-4 border-white shadow-sm flex items-center justify-center"></div>
                            <div class="bg-surface-card border border-hairline rounded-3xl p-5 sm:p-6 shadow-xs">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-base font-bold text-ink-heading">${day.title}</h3>
                                    <span class="px-3 py-0.5 rounded-full bg-primary-subtle text-primary text-xs font-bold">${day.day}</span>
                                </div>
                                <p class="text-xs text-muted mb-4 leading-relaxed">${day.description}</p>
                                <div class="space-y-2.5 text-xs text-body-strong pt-2 border-t border-hairline-soft">
                                    ${timelineHtml}
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }
        }

        // Outside Click Listener for Dropdown Dismissal
        document.addEventListener('click', (e) => {
            const trigger = document.getElementById('jalur-select-trigger');
            const menu = document.getElementById('jalur-dropdown-list');
            if (trigger && menu) {
                if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                    closeJalurDropdown();
                }
            }
        });

        // Register Alpine.js Component for Booking Modal
        function bookingModalComponent(config) {
            config = config || window.bookingModalConfig || {};
            return {
                isLoggedIn: config.isLoggedIn || false,
                loginUrl: config.loginUrl || '{{ route('login') }}',
                tripType: config.defaultTripType || 'open',
                hikingType: config.defaultHikingType || 'camping',
                openExpeditionId: config.openExpeditionId,
                privateExpeditionId: config.privateExpeditionId,
                routeId: config.routeId,
                routes: config.routes || [],
                priceTiers: config.priceTiers || [],
                meetingPoints: config.meetingPoints || [],
                addons: config.addons || [],
                bookingFeePerPax: config.bookingFeePerPax || 150000,
                basePrice: config.basePrice || 500000,
                pricePrivate: config.pricePrivate || 750000,
                priceTektok: config.priceTektok || (config.basePrice ? Math.round(config.basePrice * 0.8) : 400000),
                pricePrivateTektok: config.pricePrivateTektok || (config.pricePrivate ? Math.round(config.pricePrivate *
                    0.8) : 600000),
                maxQuota: config.maxQuota || 10,
                durationNights: config.durationNights || 1,
                minPrivateDate: config.minPrivateDate || new Date().toISOString().split('T')[0],
                customDepartureDate: config.defaultPrivateDate || new Date(Date.now() + 7 * 86400000).toISOString().split(
                    'T')[0],
                departureDateOpenShort: config.departureDateOpenShort || '15/08/2026',
                departureDateOpenFull: config.departureDateOpenFull || '15 Agustus 2026',
                returnDateOpenFull: config.returnDateOpenFull || '16 Agustus 2026',
                departureDatePrivateShort: config.departureDatePrivateShort || '15/08/2026',
                departureDatePrivateFull: config.departureDatePrivateFull || '15 Agustus 2026',
                returnDatePrivateFull: config.returnDatePrivateFull || '16 Agustus 2026',
                authCustomer: config.authCustomer || {},

                paxCount: 1,
                meetingPointId: null,
                selectedMeetingPointPrice: 0,
                selectedMeetingPointLabel: 'Basecamp',
                selectedAddons: {},

                isSubmitting: false,
                errorMessage: '',

                init() {
                    if (!this.routeId && this.routes && this.routes.length > 0) {
                        this.routeId = parseInt(this.routes[0].id, 10);
                    }
                    if (this.meetingPoints && this.meetingPoints.length > 0) {
                        const defaultMp = this.meetingPoints.find(m => m.is_default) || this.meetingPoints[0];
                        this.selectMeetingPoint(defaultMp);
                    }
                    if (this.addons && this.addons.length > 0) {
                        const hydro = this.addons.find(a => a.name.toLowerCase().includes('hydropack'));
                        if (hydro) {
                            this.toggleAddon(hydro);
                        }
                    }

                    // Listen to global open modal event
                    window.addEventListener('open-booking-modal', (e) => {
                        if (e.detail && e.detail.packageType) {
                            this.setTripType(e.detail.packageType);
                        }
                        if (e.detail && e.detail.hikeType) {
                            this.hikingType = e.detail.hikeType;
                        }
                        if (e.detail && e.detail.routeId) {
                            this.routeId = parseInt(e.detail.routeId, 10) || e.detail.routeId;
                        }
                        if (e.detail && e.detail.selectedDate) {
                            this.customDepartureDate = e.detail.selectedDate;
                        }
                        const modal = document.getElementById('bookingModal');
                        if (modal) {
                            modal.classList.remove('opacity-0', 'pointer-events-none');
                            if (modal.firstElementChild) {
                                modal.firstElementChild.classList.remove('scale-95');
                                modal.firstElementChild.classList.add('scale-100');
                            }
                        }
                    });

                    // Listen to sidebar date input change
                    window.addEventListener('sidebar-date-changed', (e) => {
                        if (e.detail && e.detail.date) {
                            this.customDepartureDate = e.detail.date;
                        }
                    });
                },

                formatDateIndonesian(dateInput) {
                    if (!dateInput) return '';
                    const d = typeof dateInput === 'string' ? new Date(dateInput + 'T00:00:00') : dateInput;
                    if (isNaN(d.getTime())) return String(dateInput);
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                        'Oktober', 'November', 'Desember'
                    ];
                    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
                },

                onPrivateDateChange() {
                    const sidebarDateInput = document.getElementById('input-sidebar-private-date');
                    if (sidebarDateInput && this.customDepartureDate) {
                        sidebarDateInput.value = this.customDepartureDate;
                    }
                },

                get currentDepartureDateShort() {
                    if (this.tripType === 'private' && this.customDepartureDate) {
                        const parts = this.customDepartureDate.split('-');
                        if (parts.length === 3) return `${parts[2]}/${parts[1]}/${parts[0]}`;
                    }
                    return this.tripType === 'open' ? this.departureDateOpenShort : this.departureDatePrivateShort;
                },

                get currentDepartureDateFull() {
                    if (this.tripType === 'private' && this.customDepartureDate) {
                        return this.formatDateIndonesian(this.customDepartureDate);
                    }
                    return this.tripType === 'open' ? this.departureDateOpenFull : this.departureDatePrivateFull;
                },

                get currentReturnDateFull() {
                    if (this.hikingType === 'tektok') {
                        return this.currentDepartureDateFull;
                    }
                    if (this.tripType === 'private' && this.customDepartureDate) {
                        const d = new Date(this.customDepartureDate + 'T00:00:00');
                        d.setDate(d.getDate() + (this.durationNights || 1));
                        return this.formatDateIndonesian(d);
                    }
                    return this.tripType === 'open' ? this.returnDateOpenFull : this.returnDatePrivateFull;
                },

                get selectedRouteName() {
                    if (this.routes && this.routes.length > 0) {
                        const found = this.routes.find(r => String(r.id) === String(this.routeId) || r.name === this
                            .routeId);
                        if (found) return found.name;
                        return this.routes[0].name;
                    }
                    return '{{ $expedition['routes'][0]['name'] ?? 'Via Selo (Boyolali)' }}';
                },

                get shuttleSummaryLabel() {
                    if (!this.selectedMeetingPointLabel) return 'Shuttle Fee';
                    let name = this.selectedMeetingPointLabel.trim();
                    if (name.toLowerCase().startsWith('shuttle ')) {
                        name = name.slice(8).trim();
                    }
                    return `Shuttle (${name})`;
                },

                setTripType(type) {
                    this.tripType = type;
                    selectedPackage = type;
                    if (typeof updatePackageUI === 'function') {
                        updatePackageUI(type);
                    }
                },

                changePax(delta) {
                    const next = this.paxCount + delta;
                    if (next >= 1 && next <= this.maxQuota) {
                        this.paxCount = next;
                        modalPaxCount = next;
                        const paxEl = document.getElementById('paxCountDisplay');
                        if (paxEl) paxEl.innerText = `${this.paxCount} Orang`;
                    }
                },

                selectMeetingPoint(mp) {
                    this.meetingPointId = mp.id;
                    this.selectedMeetingPointPrice = parseInt(mp.additional_price_per_pax || 0, 10);
                    this.selectedMeetingPointLabel = mp.name;
                },

                toggleAddon(addon) {
                    if (this.selectedAddons[addon.id]) {
                        delete this.selectedAddons[addon.id];
                    } else {
                        this.selectedAddons[addon.id] = {
                            id: addon.id,
                            name: addon.name,
                            price: parseInt(addon.price, 10),
                            quantity: 1
                        };
                    }
                },

                formatCurrency(amount) {
                    return 'Rp ' + (amount || 0).toLocaleString('id-ID');
                },

                formatBadgePrice(amount) {
                    if (!amount || amount === 0) return 'Gratis';
                    if (amount >= 1000 && amount % 1000 === 0) {
                        return '+Rp ' + (amount / 1000) + 'k';
                    }
                    return '+Rp ' + amount.toLocaleString('id-ID');
                },

                getTierPrice(pax) {
                    if (!this.priceTiers || this.priceTiers.length === 0) {
                        return this.basePrice;
                    }
                    for (let tier of this.priceTiers) {
                        if (pax >= tier.min_pax && pax <= tier.max_pax) {
                            return parseInt(tier.price_per_pax, 10);
                        }
                    }
                    return parseInt(this.priceTiers[this.priceTiers.length - 1].price_per_pax, 10);
                },

                currentPricePerPax() {
                    if (this.tripType === 'private') {
                        if (this.hikingType === 'tektok' && this.pricePrivateTektok > 0) {
                            return this.pricePrivateTektok;
                        }
                        return this.pricePrivate || 750000;
                    }
                    if (this.hikingType === 'tektok') {
                        const campingPrice = this.getTierPrice(this.paxCount);
                        if (this.basePrice > 0 && this.priceTektok > 0) {
                            const ratio = this.priceTektok / this.basePrice;
                            return Math.round(campingPrice * ratio);
                        }
                        return this.priceTektok || campingPrice;
                    }
                    return this.getTierPrice(this.paxCount);
                },

                ticketTotal() {
                    return this.currentPricePerPax() * this.paxCount;
                },

                shuttleTotal() {
                    return this.selectedMeetingPointPrice * this.paxCount;
                },

                addonsTotal() {
                    let total = 0;
                    for (let key in this.selectedAddons) {
                        total += (this.selectedAddons[key].price * (this.selectedAddons[key].quantity || 1));
                    }
                    return total;
                },

                grandTotal() {
                    return this.ticketTotal() + this.shuttleTotal() + this.addonsTotal();
                },

                async submitBooking() {
                    this.errorMessage = '';

                    // Jika belum login, langsung diarahkan ke halaman login Laravel Breeze
                    if (!this.isLoggedIn) {
                        window.location.href = this.loginUrl;
                        return;
                    }

                    const customerName = (this.authCustomer.name || 'Pendaki MiddleTrip').trim();
                    const customerEmail = (this.authCustomer.email || 'pendaki@middletrip.com').trim();
                    const customerPhone = (this.authCustomer.phone || '081234567890').trim();
                    const customerNik = (this.authCustomer.nik || '3301234567890001').trim();

                    const participantsPayload = [];
                    for (let i = 0; i < this.paxCount; i++) {
                        participantsPayload.push({
                            full_name: i === 0 ? customerName : `${customerName} Anggota #${i + 1}`,
                            nik: i === 0 ? customerNik : String(3301234567890000 + (i + 1)),
                            is_leader: i === 0
                        });
                    }

                    const addonsPayload = Object.values(this.selectedAddons).map(a => ({
                        id: a.id,
                        quantity: a.quantity || 1
                    }));

                    const expeditionId = this.tripType === 'private' ?
                        (this.privateExpeditionId || this.openExpeditionId) :
                        this.openExpeditionId;

                    const finalRouteId = this.routeId ?
                        parseInt(this.routeId, 10) :
                        (this.routes && this.routes.length > 0 ? parseInt(this.routes[0].id, 10) : null);

                    const payload = {
                        expedition_id: expeditionId ? parseInt(expeditionId, 10) : null,
                        route_id: finalRouteId,
                        meeting_point_id: this.meetingPointId ? parseInt(this.meetingPointId, 10) : null,
                        trip_type: this.tripType,
                        hiking_type: this.hikingType,
                        departure_date: this.tripType === 'private' ? this.customDepartureDate : null,
                        customer_name: customerName,
                        customer_email: customerEmail,
                        customer_phone: customerPhone,
                        customer_nik: customerNik,
                        pax_count: this.paxCount,
                        participants: participantsPayload,
                        addons: addonsPayload
                    };

                    this.isSubmitting = true;

                    try {
                        const res = await fetch('{{ route('bookings.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(payload)
                        });

                        if (res.status === 401) {
                            window.location.href = this.loginUrl;
                            return;
                        }

                        const data = await res.json();

                        if (!res.ok) {
                            if (data.errors) {
                                const firstKey = Object.keys(data.errors)[0];
                                this.errorMessage = data.errors[firstKey][0];
                            } else {
                                this.errorMessage = data.message ||
                                    'Gagal memproses booking. Silakan coba lagi.';
                            }
                            this.isSubmitting = false;
                            return;
                        }

                        // Redirect ke halaman checkout yang sesuai (Open Trip: Step 1 DP, Private Trip: Langsung 100%)
                        window.location.href = data.redirect_url;
                    } catch (err) {
                        this.errorMessage =
                            'Terjadi gangguan jaringan internet. Silakan periksa koneksi Anda.';
                        this.isSubmitting = false;
                    }
                }
            };
        }

        window.bookingModalComponent = bookingModalComponent;
        if (window.Alpine) {
            window.Alpine.data('bookingModalComponent', bookingModalComponent);
        } else {
            document.addEventListener('alpine:init', () => {
                Alpine.data('bookingModalComponent', bookingModalComponent);
            });
        }

        // Booking Modal State & Logic
        let modalPaxCount = 1;

        function handleBookingClick() {
            const hasOpenSchedule = window.bookingModalConfig ? window.bookingModalConfig.hasOpenSchedule : true;

            if (selectedPackage === 'open' && !hasOpenSchedule) {
                if (window.bookingModalConfig && window.bookingModalConfig.hasPrivateTrip) {
                    setPackageType('private');
                    return;
                }
                return;
            }

            const sidebarDateInput = document.getElementById('input-sidebar-private-date');
            const chosenDate = (selectedPackage === 'private' && sidebarDateInput) ? sidebarDateInput.value : null;

            const modal = document.getElementById('bookingModal');
            if (modal) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                if (modal.firstElementChild) {
                    modal.firstElementChild.classList.remove('scale-95');
                    modal.firstElementChild.classList.add('scale-100');
                }
            }

            const finalRouteId = selectedRouteId ?
                parseInt(selectedRouteId, 10) :
                (window.bookingModalConfig && window.bookingModalConfig.routeId ? parseInt(window.bookingModalConfig
                    .routeId, 10) : null);

            window.dispatchEvent(new CustomEvent('open-booking-modal', {
                detail: {
                    packageType: selectedPackage,
                    hikeType: selectedHikeType,
                    routeId: finalRouteId,
                    selectedDate: chosenDate
                }
            }));
        }

        function closeBookingModal() {
            const modal = document.getElementById('bookingModal');
            if (modal) {
                modal.classList.add('opacity-0', 'pointer-events-none');
                if (modal.firstElementChild) {
                    modal.firstElementChild.classList.remove('scale-100');
                    modal.firstElementChild.classList.add('scale-95');
                }
            }
        }

        // Gallery Lightbox Modal
        function openPhotoModal(index) {
            if (!expeditionData.gallery || expeditionData.gallery.length === 0) return;
            currentPhotoIndex = (index >= 0 && index < expeditionData.gallery.length) ? index : 0;
            updateLightboxContent();

            const modal = document.getElementById('galleryModal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
        }

        function closePhotoModal() {
            const modal = document.getElementById('galleryModal');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }

        function navigatePhoto(direction) {
            const len = expeditionData.gallery.length;
            currentPhotoIndex = (currentPhotoIndex + direction + len) % len;
            updateLightboxContent();
        }

        function updateLightboxContent() {
            const photo = expeditionData.gallery[currentPhotoIndex];
            if (photo) {
                document.getElementById('lightbox-image').src = photo.url;
                document.getElementById('lightbox-caption').innerText = photo.caption || expeditionData.title;
                document.getElementById('lightbox-counter').innerText =
                    `${currentPhotoIndex + 1} / ${expeditionData.gallery.length}`;
            }
        }

        // Modal backdrop click and Escape key listeners
        document.addEventListener('DOMContentLoaded', () => {
            if (window.bookingModalConfig && !window.bookingModalConfig.hasOpenSchedule) {
                if (window.bookingModalConfig.hasPrivateTrip) {
                    setPackageType('private');
                } else {
                    updatePackageUI('open');
                }
            }
            const bookingModal = document.getElementById('bookingModal');
            if (bookingModal) {
                bookingModal.addEventListener('click', (e) => {
                    if (e.target === bookingModal) {
                        closeBookingModal();
                    }
                });
            }

            const galleryModal = document.getElementById('galleryModal');
            if (galleryModal) {
                galleryModal.addEventListener('click', (e) => {
                    if (e.target === galleryModal) {
                        closePhotoModal();
                    }
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeBookingModal();
                    closePhotoModal();
                }
            });
        });
    </script>
</body>

</html>
