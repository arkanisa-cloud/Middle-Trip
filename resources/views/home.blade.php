<x-public-layout title="MiddleTrip - Jalan Tengah Menuju Puncak yang Sesungguhnya" active="home" :hero="true"
    body-class="bg-canvas-alt text-ink antialiased selection:bg-primary selection:text-white font-sans overflow-x-hidden">

    {{-- =====================================================================
         1. HERO HEADER & FLOATING EXPRESS SEARCH BAR
    ===================================================================== --}}
    <header
        class="relative w-full min-h-[360px] sm:min-h-[440px] md:h-screen md:min-h-[660px] bg-cover bg-center flex flex-col justify-between"
        style="background-image: linear-gradient(180deg, rgba(16, 24, 40, 0.45) 0%, rgba(16, 24, 40, 0.15) 40%, rgba(0,0,0,0.35) 100%), url('{{ asset('storage/mountains/hero.webp') }}');">

        <!-- Hero Title Center -->
        <div
            class="flex-1 flex flex-col items-center justify-center text-center px-4 pt-20 sm:pt-24 md:pt-20 pb-10 sm:pb-16 z-10">
            <h1
                class="text-3xl sm:text-4xl md:text-5xl lg:text-[54px] font-extrabold text-white leading-tight max-w-3xl drop-shadow-md">
                Jalan Tengah Menuju<br class="hidden sm:inline"> Puncak yang Sesungguhnya
            </h1>
        </div>

        <!-- Floating Search Filter Bar (Desktop only, mobile uses Navbar Quick Search) -->
        <div class="hidden md:block w-full max-w-4xl mx-auto px-4 translate-y-1/2 z-20">
            <form id="hero-search-form" onsubmit="handleHeroSearch(event)"
                class="bg-white rounded-full p-2.5 sm:p-3 shadow-xl border border-gray-100 flex items-center gap-2">

                <!-- Hidden inputs untuk state pencarian -->
                <input type="hidden" id="selected-mountain-slug" name="mountain_slug" value="" />
                <input type="hidden" id="selected-route-slug" name="route_slug" value="" />
                <input type="hidden" id="selected-grade-val" name="grade_val" value="" />

                <!-- Filter 1: Lokasi Gunung (Searchable Combobox Dropdown) -->
                <div class="relative w-full md:w-1/3">
                    <div id="mountain-select-trigger"
                        class="flex items-center gap-3 w-full px-3.5 sm:px-4 py-2.5 sm:py-2 bg-gray-50/80 md:bg-transparent hover:bg-gray-100/80 md:hover:bg-gray-50 rounded-xl md:rounded-full border border-gray-100 md:border-none cursor-pointer transition">
                        <svg class="w-5 h-5 text-muted-soft shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div class="flex-1 text-left min-w-0">
                            <input type="text" id="mountain-search-input" placeholder="Pilih Gunung"
                                autocomplete="off"
                                class="w-full bg-transparent border-none focus:border-none focus:ring-0 focus:outline-none text-[13px] font-semibold text-body-strong placeholder-muted cursor-pointer p-0 shadow-none truncate" />
                        </div>
                        <svg id="mountain-chevron" class="w-4 h-4 text-muted transition-transform duration-200 shrink-0"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>

                    <!-- Dropdown List Hasil Pencarian Gunung -->
                    <div id="mountain-dropdown-list"
                        class="hidden absolute left-0 right-0 md:left-0 md:right-auto md:w-72 mt-2 bg-white border border-hairline rounded-2xl shadow-2xl py-2 z-50 max-h-56 overflow-y-auto text-xs">
                        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider">
                            Pilih Gunung
                        </div>
                        <button type="button" onclick="selectMountain('', 'Semua Gunung')"
                            class="mountain-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                            <span>Semua Gunung</span>
                        </button>
                        @foreach ($mountains as $mountain)
                            <button type="button"
                                onclick="selectMountain('{{ $mountain->slug }}', '{{ $mountain->name }}')"
                                class="mountain-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                                <span class="font-semibold text-slate-800">{{ $mountain->name }}</span>
                                <span
                                    class="text-[10px] text-muted font-normal font-liberation">{{ $mountain->formatted_elevation }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="hidden md:block w-px h-7 bg-hairline"></div>

                <!-- Filter 2: Pilih Jalur (Dependent Custom Dropdown) -->
                <div class="relative w-full md:w-1/3">
                    <div id="jalur-select-trigger"
                        class="flex items-center gap-3 w-full px-3.5 sm:px-4 py-2.5 sm:py-2 bg-gray-50/80 md:bg-transparent hover:bg-gray-100/80 md:hover:bg-gray-50 rounded-xl md:rounded-full border border-gray-100 md:border-none cursor-pointer transition select-none">
                        <svg class="w-5 h-5 text-muted-soft shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        <div class="flex-1 text-left min-w-0">
                            <span id="jalur-display-label"
                                class="text-[13px] font-semibold text-body-strong block truncate">Pilih Jalur</span>
                        </div>
                        <svg id="jalur-chevron" class="w-4 h-4 text-muted transition-transform duration-200 shrink-0"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>

                    <!-- Dropdown List Jalur (Dinamis sesuai Gunung) -->
                    <div id="jalur-dropdown-list"
                        class="hidden absolute left-0 right-0 md:left-0 md:right-auto md:w-72 mt-2 bg-white border border-hairline rounded-2xl shadow-2xl py-2 z-50 max-h-56 overflow-y-auto no-scrollbar text-xs">
                        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider">
                            Pilih Jalur
                        </div>
                        <div id="jalur-options-container">
                            <div class="px-4 py-3 text-muted text-xs italic">
                                Pilih gunung terlebih dahulu
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden md:block w-px h-7 bg-hairline"></div>

                <!-- Filter 3: Tingkat Kesulitan (Otomatis / Terkunci sesuai Jalur) -->
                <div class="relative w-full md:w-1/3">
                    <div id="grade-hero-trigger"
                        class="flex items-center gap-3 w-full px-3.5 sm:px-4 py-2.5 sm:py-2 bg-gray-50/80 md:bg-transparent hover:bg-gray-100/80 md:hover:bg-gray-50 rounded-xl md:rounded-full border border-gray-100 md:border-none cursor-pointer transition select-none">
                        <svg class="w-5 h-5 text-muted-soft shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <div class="flex-1 text-left min-w-0">
                            <span id="grade-hero-display-label"
                                class="text-[13px] font-semibold text-body-strong block truncate">Semua Level</span>
                            <span id="grade-hero-status"
                                class="text-[10px] text-muted font-medium hidden block truncate">Terkunci (sesuai
                                jalur)</span>
                        </div>
                        <svg id="grade-hero-chevron"
                            class="w-4 h-4 text-muted transition-transform duration-200 shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                        <svg id="grade-hero-lock" class="w-4 h-4 text-muted-soft hidden shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                            title="Terkunci sesuai jalur yang dipilih">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>

                    <!-- Dropdown List Tingkat Kesulitan -->
                    <div id="grade-hero-dropdown-list"
                        class="hidden absolute left-0 right-0 md:left-0 md:right-auto md:w-72 mt-2 bg-white border border-hairline rounded-2xl shadow-2xl py-2 z-50 max-h-56 overflow-y-auto text-xs">
                        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider">
                            Tingkat Kesulitan
                        </div>
                        <button type="button" onclick="selectHeroGrade('', 'Semua Level', true)"
                            class="grade-hero-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                            <span>Semua Level</span>
                        </button>
                        <button type="button" onclick="selectHeroGrade('Grade A', 'Grade A – Pemula', true)"
                            class="grade-hero-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                            <span class="text-grade-a-text">Grade A – Pemula</span>
                            <span class="w-2 h-2 rounded-full bg-grade-a-dot"></span>
                        </button>
                        <button type="button" onclick="selectHeroGrade('Grade B', 'Grade B – Menengah', true)"
                            class="grade-hero-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                            <span class="text-grade-b-text">Grade B – Menengah</span>
                            <span class="w-2 h-2 rounded-full bg-grade-b-dot"></span>
                        </button>
                        <button type="button" onclick="selectHeroGrade('Grade C', 'Grade C – Ahli', true)"
                            class="grade-hero-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                            <span class="text-grade-c-text">Grade C – Ahli</span>
                            <span class="w-2 h-2 rounded-full bg-grade-c-dot"></span>
                        </button>
                    </div>
                </div>

                <!-- Tombol Cari -->
                <button type="submit"
                    class="w-full md:w-auto text-center bg-primary hover:bg-primary-hover active:bg-primary-active text-white text-[14px] font-semibold px-8 py-3.5 rounded-xl md:rounded-full transition duration-200 shrink-0 shadow-md cursor-pointer">
                    Cari Jalur
                </button>
            </form>
        </div>
    </header>

    {{-- =====================================================================
         2. MAIN SECTION: PILIHAN PUNCAK (BENTO GRID)
    ===================================================================== --}}
    <main id="ekspedisi" class="w-full max-w-6xl mx-auto px-4 pt-10 sm:pt-14 md:pt-28 pb-16">
        <!-- Section Title Bar -->
        <div class="flex items-center sm:items-end justify-between gap-3 mb-6 sm:mb-7">
            <div>
                <span class="text-[11px] uppercase tracking-wider font-bold text-primary block mb-0.5 sm:mb-1">
                    TOP PILIHAN KAMI
                </span>
                <h2
                    class="text-xl sm:text-2xl md:text-[28px] font-extrabold text-ink-heading tracking-tight leading-tight">
                    Puncak Telah Menanti!
                </h2>
            </div>
            <a href="{{ route('ekspedisi.index') }}"
                class="group inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 bg-primary-subtle text-primary hover:bg-primary hover:text-white border border-primary/20 rounded-full text-xs sm:text-[13px] font-bold transition-all duration-200 shadow-2xs hover:shadow-sm shrink-0">
                <span>Lihat Semua</span>
                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

        <!-- Bento Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

            @if ($featuredHero)
                <!-- Big Hero Card: Featured Order 1 (Span 7) -->
                <a href="{{ route('ekspedisi.show', $featuredHero->slug) }}"
                    class="lg:col-span-7 relative min-h-[340px] sm:min-h-[400px] md:min-h-[440px] lg:h-full lg:min-h-0 rounded-2xl overflow-hidden group shadow-sm bg-gray-900 cursor-pointer block">
                    <img src="{{ $featuredHero->cover_image }}" alt="{{ $featuredHero->name }}" fetchpriority="high"
                        decoding="async"
                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-black/30"></div>

                    <!-- Badges Top -->
                    <div class="absolute top-4 left-4 right-4 flex items-center justify-between pointer-events-none">
                        <span
                            class="{{ $featuredHero->default_grade?->badgeClasses() ?? 'bg-grade-a-bg text-grade-a-text' }} text-[11px] font-bold px-3 py-1 rounded-full shadow-sm">
                            {{ $featuredHero->default_grade?->label() ?? 'Grade A – Pemula' }}
                        </span>
                        <span
                            class="bg-black/40 backdrop-blur-sm text-white text-[11px] font-medium px-3 py-1 rounded-full flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-white/90" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                            </svg>
                            {{ $featuredHero->formatted_elevation }}
                        </span>
                    </div>

                    <!-- Content Bottom -->
                    <div
                        class="absolute bottom-4 sm:bottom-5 left-4 sm:left-5 right-4 sm:right-5 flex flex-col sm:flex-row sm:items-end justify-between text-white gap-3 sm:gap-4">
                        <div class="max-w-full sm:max-w-[70%]">
                            <h3 class="text-xl sm:text-2xl md:text-3xl font-bold tracking-tight">
                                {{ $featuredHero->name }}</h3>
                            <p class="text-xs md:text-sm text-white/80 mt-1 line-clamp-2 leading-relaxed">
                                {{ $featuredHero->description }}
                            </p>
                        </div>
                        <div class="text-left sm:text-right shrink-0">
                            <p class="text-[11px] text-white/75 font-normal">Mulai dari</p>
                            <p class="text-base sm:text-lg md:text-xl font-bold text-white whitespace-nowrap">
                                {{ $featuredHero->formatted_short_price }} <span
                                    class="text-xs font-normal text-white/80 font-sans">/ pax</span></p>
                        </div>
                    </div>
                </a>
            @endif

            <!-- Right Column: 2 Stacked Cards (Span 5) -->
            <div class="lg:col-span-5 flex flex-col gap-4">
                @foreach ($featuredCards as $card)
                    <a href="{{ route('ekspedisi.show', $card->slug) }}"
                        class="flex-1 bg-surface-card rounded-2xl p-3.5 border border-gray-100 shadow-sm hover:shadow-md transition duration-200 cursor-pointer group flex flex-col justify-between block">
                        <div class="relative h-28 rounded-xl overflow-hidden mb-3 bg-gray-200">
                            <img src="{{ $card->cover_image }}" alt="{{ $card->name }}" loading="lazy"
                                decoding="async"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                            <div class="absolute inset-0 bg-black/20"></div>
                            <div class="absolute top-2.5 left-2.5 right-2.5 flex items-center justify-between">
                                <span
                                    class="{{ $card->default_grade?->badgeClasses() ?? 'bg-grade-b-bg text-grade-b-text' }} text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                                    {{ $card->default_grade?->shortLabel() ?? 'Grade B' }}
                                </span>
                                <span
                                    class="bg-black/50 backdrop-blur-sm text-white text-[10px] font-medium font-liberation px-2 py-0.5 rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3 text-white/90" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                                    </svg>
                                    {{ $card->formatted_elevation }}
                                </span>
                            </div>
                        </div>

                        <div class="px-1">
                            <h4 class="text-base font-bold text-ink-heading group-hover:text-primary transition">
                                {{ $card->name }}</h4>
                            <p class="text-[11px] text-muted mt-1 line-clamp-1">
                                {{ $card->description }}
                            </p>
                            <div class="flex items-center justify-between mt-3 pt-2 border-t border-hairline-soft">
                                <span class="text-[13px] font-bold text-primary">{{ $card->formatted_short_price }}
                                    <span class="text-[11px] font-normal text-muted font-sans">/ pax</span></span>
                                <span
                                    class="text-muted-soft group-hover:text-primary group-hover:translate-x-0.5 transition text-sm">→</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </main>

    {{-- =====================================================================
         3. SECTION: KLASIFIKASI JALUR (ALPINE FOREST TOPOGRAPHIC SURFACE)
    ===================================================================== --}}
    <section id="klasifikasi" class="w-full bg-surface-forest text-white py-20 px-4 topo-pattern relative">
        <div class="max-w-5xl mx-auto">

            <!-- Section Header -->
            <div class="text-center mb-9">
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Klasifikasi Jalur</h2>
                <p class="text-xs md:text-sm text-gray-300/80 mt-2 max-w-lg mx-auto">
                    Pastikan mendaki sesuai dengan batas kemampuan fisik dan pengalaman Anda
                </p>
            </div>

            <!-- Segmented Grade Switcher -->
            <div class="flex justify-center mb-8">
                <div class="inline-flex bg-black/30 p-1 rounded-full border border-white/10 backdrop-blur-sm">
                    <button id="tab-grade-a" onclick="switchGrade('A')" type="button"
                        class="px-6 py-2 rounded-full text-xs font-semibold bg-primary text-white shadow-sm transition">
                        Grade A
                    </button>
                    <button id="tab-grade-b" onclick="switchGrade('B')" type="button"
                        class="px-6 py-2 rounded-full text-xs font-semibold text-gray-300 hover:text-white transition">
                        Grade B
                    </button>
                    <button id="tab-grade-c" onclick="switchGrade('C')" type="button"
                        class="px-6 py-2 rounded-full text-xs font-semibold text-gray-300 hover:text-white transition">
                        Grade C
                    </button>
                </div>
            </div>

            <!-- Grade Info Card -->
            <div class="bg-surface-forest-card border border-surface-forest-border rounded-3xl p-6 md:p-10 shadow-2xl">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                    <!-- Left Specs -->
                    <div class="lg:col-span-7 flex flex-col justify-between h-full">
                        <div>
                            <h3 id="grade-title" class="text-2xl font-bold tracking-tight text-white mb-2">Kelas A -
                                Pemula</h3>
                            <p id="grade-desc" class="text-xs md:text-sm text-gray-300 leading-relaxed max-w-md">
                                Sangat direkomendasikan bagi Anda yang baru pertama kali ingin mencicipi dinginnya
                                udara puncak gunung.
                            </p>
                        </div>

                        <!-- Metric Boxes -->
                        <div class="grid grid-cols-2 gap-3 my-6">

                            <!-- Duration Box -->
                            <div class="bg-black/25 border border-white/5 rounded-2xl p-4">
                                <div class="flex items-center gap-2 text-primary mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Durasi
                                        Trek</span>
                                </div>
                                <p id="grade-duration" class="text-sm md:text-base font-bold text-white">2 – 5 Jam /
                                    Hari</p>
                            </div>

                            <!-- Effort Box -->
                            <div class="bg-black/25 border border-white/5 rounded-2xl p-4">
                                <div class="flex items-center gap-2 text-primary mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span
                                        class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Kebutuhan
                                        Fisik</span>
                                </div>
                                <p id="grade-effort" class="text-sm md:text-base font-bold text-white">Jogging Ringan
                                </p>
                            </div>

                        </div>

                        <!-- Recommendations -->
                        <div>
                            <span class="text-[11px] text-gray-400 block mb-2 font-medium">Rekomendasi Gunung:</span>
                            <div id="grade-tags" class="flex flex-wrap gap-2">
                                <span
                                    class="text-xs bg-surface-forest-tag border border-white/10 px-3.5 py-1.5 rounded-full text-gray-200">Mt.
                                    Merbabu</span>
                                <span
                                    class="text-xs bg-surface-forest-tag border border-white/10 px-3.5 py-1.5 rounded-full text-gray-200">Mt.
                                    Prau</span>
                            </div>
                        </div>

                    </div>

                    <!-- Right Image Card -->
                    <div class="lg:col-span-5">
                        <div
                            class="relative w-full aspect-[4/3] rounded-2xl overflow-hidden shadow-lg border border-white/10">
                            <img id="grade-image" src="/storage/mountains/merbabu.webp" alt="Jalur Pemula"
                                loading="lazy" decoding="async" class="w-full h-full object-cover" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    {{-- =====================================================================
         4. SECTION: PERTANYAAN UMUM (FAQ ACCORDION)
    ===================================================================== --}}
    <section id="faq" class="w-full max-w-4xl mx-auto px-4 py-20">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-[28px] font-extrabold text-ink-heading tracking-tight">
                Pertanyaan Umum
            </h2>
        </div>

        <div class="space-y-3.5 max-w-2xl mx-auto">

            <!-- FAQ 1 (Open by default) -->
            <div
                class="border border-hairline bg-surface-card rounded-2xl overflow-hidden transition-all duration-200 shadow-sm">
                <button onclick="toggleFaq(1)" type="button"
                    class="w-full text-left px-6 py-4 flex items-center justify-between gap-4 font-semibold text-ink text-xs md:text-[13px] hover:text-primary transition">
                    <span>Apa saja yang perlu saya bawa untuk ikut Open Trip?</span>
                    <svg id="faq-icon-1"
                        class="w-4 h-4 text-muted shrink-0 transform rotate-180 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div id="faq-content-1"
                    class="px-6 pb-4 pt-1 text-body text-xs md:text-[13px] leading-relaxed border-t border-hairline-soft">
                    Anda hanya perlu membawa perlengkapan pribadi (jaket gunung, sepatu mendaki, headlamp, dan
                    obat-obatan pribadi). Kami menyediakan tenda premium, alat masak, porter, guide serta logistik
                    makan.
                </div>
            </div>

            <!-- FAQ 2 (Collapsed) -->
            <div
                class="border border-hairline bg-surface-card rounded-2xl overflow-hidden transition-all duration-200 shadow-sm">
                <button onclick="toggleFaq(2)" type="button"
                    class="w-full text-left px-6 py-4 flex items-center justify-between gap-4 font-semibold text-ink text-xs md:text-[13px] hover:text-primary transition">
                    <span>Bagaimana jika cuaca buruk saat hari pendakian?</span>
                    <svg id="faq-icon-2"
                        class="w-4 h-4 text-muted shrink-0 transform transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div id="faq-content-2"
                    class="hidden px-6 pb-4 pt-1 text-body text-xs md:text-[13px] leading-relaxed border-t border-hairline-soft">
                    Keselamatan adalah prioritas utama kami. Tim leader berhak menunda atau menyesuaikan jalur demi
                    keselamatan, serta menyediakan opsi reschedule jadwal jika jalur ditutup resmi oleh pengelola taman
                    nasional.
                </div>
            </div>

            <!-- FAQ 3 (Collapsed) -->
            <div
                class="border border-hairline bg-surface-card rounded-2xl overflow-hidden transition-all duration-200 shadow-sm">
                <button onclick="toggleFaq(3)" type="button"
                    class="w-full text-left px-6 py-4 flex items-center justify-between gap-4 font-semibold text-ink text-xs md:text-[13px] hover:text-primary transition">
                    <span>Apakah pemula tanpa pengalaman boleh langsung ikut Grade B?</span>
                    <svg id="faq-icon-3"
                        class="w-4 h-4 text-muted shrink-0 transform transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div id="faq-content-3"
                    class="hidden px-6 pb-4 pt-1 text-body text-xs md:text-[13px] leading-relaxed border-t border-hairline-soft">
                    Kami sangat menyarankan pemula untuk memulai dari Grade A (seperti Merbabu via Selo atau Prau).
                    Namun jika Anda memiliki rutinitas kardio aktif dan didampingi guide privat kami, Grade B tetap
                    dapat dipertimbangkan setelah konsultasi awal.
                </div>
            </div>

        </div>
    </section>

    {{-- =====================================================================
         5. CLIENT-SIDE INTERACTIVE SCRIPTS
    ===================================================================== --}}
    @push('scripts')
        <script>
            // Data Grade switcher
            const gradeData = {
                'A': {
                    title: 'Kelas A - Pemula',
                    desc: 'Sangat direkomendasikan bagi Anda yang baru pertama kali ingin mencicipi dinginnya udara puncak gunung.',
                    duration: '2 – 5 Jam / Hari',
                    effort: 'Jogging Ringan',
                    tags: ['Mt. Merbabu', 'Mt. Prau'],
                    img: '/storage/mountains/merbabu.webp'
                },
                'B': {
                    title: 'Kelas B - Menengah',
                    desc: 'Untuk pendaki yang sudah memiliki pengalaman dan ketahanan fisik cukup pada tanjakan curam berkepanjangan.',
                    duration: '6 – 8 Jam / Hari',
                    effort: 'Latihan Kardio Rutin',
                    tags: ['Mt. Sumbing', 'Mt. Sindoro', 'Mt. Lawu'],
                    img: '/storage/mountains/kembang.webp'
                },
                'C': {
                    title: 'Kelas C - Ahli & Ekstrem',
                    desc: 'Medan teknis, cuaca tak menentu, dan elevasi tinggi. Membutuhkan navigasi mandiri dan fisik prima.',
                    duration: '8 – 12 Jam / Hari',
                    effort: 'Ketahanan Tinggi & Endurance',
                    tags: ['Mt. Slamet', 'Mt. Rinjani'],
                    img: '/storage/mountains/rinjani.webp'
                }
            };

            function switchGrade(grade) {
                const data = gradeData[grade];
                if (!data) return;

                document.getElementById('grade-title').textContent = data.title;
                document.getElementById('grade-desc').textContent = data.desc;
                document.getElementById('grade-duration').textContent = data.duration;
                document.getElementById('grade-effort').textContent = data.effort;
                document.getElementById('grade-image').src = data.img;

                const tagsContainer = document.getElementById('grade-tags');
                tagsContainer.innerHTML = data.tags.map(t =>
                    `<span class="text-xs bg-surface-forest-tag border border-white/10 px-3.5 py-1.5 rounded-full text-gray-200">${t}</span>`
                ).join('');

                ['A', 'B', 'C'].forEach(g => {
                    const btn = document.getElementById(`tab-grade-${g.toLowerCase()}`);
                    if (g === grade) {
                        btn.className =
                            'px-6 py-2 rounded-full text-xs font-semibold bg-primary text-white shadow-sm transition';
                    } else {
                        btn.className =
                            'px-6 py-2 rounded-full text-xs font-semibold text-gray-300 hover:text-white transition';
                    }
                });
            }

            function toggleFaq(id) {
                const content = document.getElementById(`faq-content-${id}`);
                const icon = document.getElementById(`faq-icon-${id}`);

                const isHidden = content.classList.contains('hidden');
                if (isHidden) {
                    content.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                } else {
                    content.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            }

            // Data dinamis gunung & jalur dari backend
            const mountainsData = @json($mountains);

            // Search Bar Filters Logic
            const mountainInput = document.getElementById('mountain-search-input');
            const mountainDropdown = document.getElementById('mountain-dropdown-list');
            const mountainChevron = document.getElementById('mountain-chevron');
            const mountainTrigger = document.getElementById('mountain-select-trigger');
            const selectedMountainSlug = document.getElementById('selected-mountain-slug');

            const jalurTrigger = document.getElementById('jalur-select-trigger');
            const jalurDropdown = document.getElementById('jalur-dropdown-list');
            const jalurChevron = document.getElementById('jalur-chevron');
            const jalurDisplay = document.getElementById('jalur-display-label');
            const jalurOptionsContainer = document.getElementById('jalur-options-container');
            const selectedRouteSlug = document.getElementById('selected-route-slug');

            const gradeHeroTrigger = document.getElementById('grade-hero-trigger');
            const gradeHeroDropdown = document.getElementById('grade-hero-dropdown-list');
            const gradeHeroChevron = document.getElementById('grade-hero-chevron');
            const gradeHeroLock = document.getElementById('grade-hero-lock');
            const gradeHeroDisplay = document.getElementById('grade-hero-display-label');
            const gradeHeroStatus = document.getElementById('grade-hero-status');
            const selectedGradeVal = document.getElementById('selected-grade-val');

            let isGradeLocked = false;

            function setGradeLocked(locked, reason = 'Terkunci (sesuai jalur)') {
                isGradeLocked = locked;
                if (!gradeHeroTrigger) return;

                if (locked) {
                    closeGradeHeroDropdown();
                    gradeHeroTrigger.classList.remove('cursor-pointer', 'hover:bg-gray-50');
                    gradeHeroTrigger.classList.add('cursor-default', 'bg-gray-50/50');
                    gradeHeroChevron?.classList.add('hidden');
                    gradeHeroLock?.classList.remove('hidden');
                    if (gradeHeroStatus) {
                        gradeHeroStatus.textContent = reason;
                        gradeHeroStatus.classList.remove('hidden');
                    }
                } else {
                    gradeHeroTrigger.classList.add('cursor-pointer', 'hover:bg-gray-50');
                    gradeHeroTrigger.classList.remove('cursor-default', 'bg-gray-50/50');
                    gradeHeroChevron?.classList.remove('hidden');
                    gradeHeroLock?.classList.add('hidden');
                    if (gradeHeroStatus) {
                        gradeHeroStatus.classList.add('hidden');
                    }
                }
            }

            function openMountainDropdown() {
                closeJalurDropdown();
                closeGradeHeroDropdown();
                if (mountainDropdown) {
                    const options = mountainDropdown.querySelectorAll('.mountain-option');
                    options.forEach(opt => opt.classList.remove('hidden'));
                    mountainDropdown.classList.remove('hidden');
                    mountainChevron?.classList.add('rotate-180');
                }
            }

            function closeMountainDropdown() {
                if (mountainDropdown) {
                    mountainDropdown.classList.add('hidden');
                    mountainChevron?.classList.remove('rotate-180');
                }
            }

            function selectMountain(slug, name) {
                if (mountainInput) mountainInput.value = name;
                if (selectedMountainSlug) selectedMountainSlug.value = slug;

                if (selectedRouteSlug) selectedRouteSlug.value = '';
                if (jalurDisplay) jalurDisplay.textContent = 'Pilih Jalur';

                updateJalurDropdown(slug);
                closeMountainDropdown();
            }

            function updateJalurDropdown(mountainSlug) {
                if (!jalurOptionsContainer) return;

                if (!mountainSlug) {
                    jalurOptionsContainer.innerHTML = `
                        <div class="px-4 py-3 text-muted text-xs italic">
                            Pilih gunung terlebih dahulu
                        </div>
                    `;
                    setGradeLocked(false);
                    selectHeroGrade('', 'Semua Level');
                    return;
                }

                const mountain = mountainsData.find(m => m.slug === mountainSlug);
                if (!mountain || !mountain.routes || mountain.routes.length === 0) {
                    jalurOptionsContainer.innerHTML = `
                        <div class="px-4 py-3 text-muted text-xs italic">
                            Tidak ada jalur terdaftar
                        </div>
                    `;
                    setGradeLocked(false);
                    return;
                }

                let html = `
                    <button type="button" onclick="selectJalur('', 'Semua Jalur', '')"
                        class="jalur-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                        <span>Semua Jalur</span>
                    </button>
                `;

                mountain.routes.forEach(route => {
                    const gradeLabel = route.grade || 'Grade A';
                    const isPrimary = route.is_primary ?
                        '<span class="text-[9px] bg-primary-subtle text-primary font-bold px-1.5 py-0.5 rounded-full ml-1.5">Utama</span>' :
                        '';
                    html += `
                        <button type="button" onclick="selectJalur('${route.slug}', '${route.name}', '${route.grade}')"
                            class="jalur-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                            <span class="flex items-center">${route.name} ${isPrimary}</span>
                            <span class="text-[10px] text-muted font-normal">${route.duration_hours || gradeLabel}</span>
                        </button>
                    `;
                });

                jalurOptionsContainer.innerHTML = html;

                const primaryRoute = mountain.routes.find(r => r.is_primary) || mountain.routes[0];
                if (primaryRoute) {
                    selectJalur(primaryRoute.slug, primaryRoute.name, primaryRoute.grade);
                } else {
                    setGradeLocked(false);
                }
            }

            function toggleJalurDropdown() {
                if (jalurDropdown?.classList.contains('hidden')) {
                    closeMountainDropdown();
                    closeGradeHeroDropdown();
                    jalurDropdown.classList.remove('hidden');
                    jalurChevron?.classList.add('rotate-180');
                } else {
                    closeJalurDropdown();
                }
            }

            function closeJalurDropdown() {
                if (jalurDropdown) {
                    jalurDropdown.classList.add('hidden');
                    jalurChevron?.classList.remove('rotate-180');
                }
            }

            function selectJalur(val, label, grade) {
                if (selectedRouteSlug) selectedRouteSlug.value = val;
                if (jalurDisplay) jalurDisplay.textContent = label;

                if (val && grade) {
                    const gradeLabels = {
                        'Grade A': 'Grade A – Pemula',
                        'Grade B': 'Grade B – Menengah',
                        'Grade C': 'Grade C – Ahli',
                    };
                    selectHeroGrade(grade, gradeLabels[grade] || grade);
                    setGradeLocked(true);
                } else {
                    setGradeLocked(false);
                }

                closeJalurDropdown();
            }

            function toggleGradeHeroDropdown() {
                if (isGradeLocked) return;

                if (gradeHeroDropdown?.classList.contains('hidden')) {
                    closeMountainDropdown();
                    closeJalurDropdown();
                    gradeHeroDropdown.classList.remove('hidden');
                    gradeHeroChevron?.classList.add('rotate-180');
                } else {
                    closeGradeHeroDropdown();
                }
            }

            function closeGradeHeroDropdown() {
                if (gradeHeroDropdown) {
                    gradeHeroDropdown.classList.add('hidden');
                    gradeHeroChevron?.classList.remove('rotate-180');
                }
            }

            function selectHeroGrade(val, label, isManual = false) {
                if (isManual && isGradeLocked) return;

                if (selectedGradeVal) selectedGradeVal.value = val;
                if (gradeHeroDisplay) {
                    const dots = {
                        'Grade A': '<span class="inline-block w-2 h-2 rounded-full bg-grade-a-dot mr-1.5"></span>',
                        'Grade B': '<span class="inline-block w-2 h-2 rounded-full bg-grade-b-dot mr-1.5"></span>',
                        'Grade C': '<span class="inline-block w-2 h-2 rounded-full bg-grade-c-dot mr-1.5"></span>',
                    };
                    const dot = dots[val] || '';
                    gradeHeroDisplay.innerHTML = `${dot}${label}`;
                }
                closeGradeHeroDropdown();
            }

            function handleHeroSearch(e) {
                e.preventDefault();
                const mSlug = selectedMountainSlug ? selectedMountainSlug.value.trim() : '';
                const rSlug = selectedRouteSlug ? selectedRouteSlug.value.trim() : '';

                if (mSlug) {
                    let targetUrl = `{{ url('/ekspedisi') }}/${mSlug}`;
                    if (rSlug) {
                        targetUrl += `?jalur=${encodeURIComponent(rSlug)}`;
                    }
                    window.location.href = targetUrl;
                } else {
                    const gradeVal = selectedGradeVal ? selectedGradeVal.value.trim() : '';
                    let catalogUrl = `{{ route('ekspedisi.index') }}`;
                    if (gradeVal) {
                        catalogUrl += `?grade=${encodeURIComponent(gradeVal)}`;
                    }
                    window.location.href = catalogUrl;
                }
            }

            // Expose globally
            window.selectMountain = selectMountain;
            window.selectJalur = selectJalur;
            window.selectHeroGrade = selectHeroGrade;
            window.toggleJalurDropdown = toggleJalurDropdown;
            window.toggleGradeHeroDropdown = toggleGradeHeroDropdown;
            window.handleHeroSearch = handleHeroSearch;
            window.switchGrade = switchGrade;
            window.toggleFaq = toggleFaq;

            if (mountainInput && mountainDropdown) {
                mountainInput.addEventListener('focus', openMountainDropdown);

                mountainTrigger?.addEventListener('click', (e) => {
                    if (e.target !== mountainInput) {
                        if (mountainDropdown.classList.contains('hidden')) {
                            openMountainDropdown();
                            mountainInput.focus();
                        } else {
                            closeMountainDropdown();
                        }
                    }
                });

                mountainInput.addEventListener('input', (e) => {
                    const filter = e.target.value.toLowerCase().trim();
                    if (mountainDropdown.classList.contains('hidden')) {
                        openMountainDropdown();
                    }
                    const options = mountainDropdown.querySelectorAll('.mountain-option');
                    options.forEach(opt => {
                        const text = opt.textContent.toLowerCase();
                        if (text.includes(filter)) {
                            opt.classList.remove('hidden');
                        } else {
                            opt.classList.add('hidden');
                        }
                    });
                });
            }

            jalurTrigger?.addEventListener('click', toggleJalurDropdown);
            gradeHeroTrigger?.addEventListener('click', toggleGradeHeroDropdown);

            document.addEventListener('click', (e) => {
                const isInsideMountain = mountainTrigger?.contains(e.target) || mountainDropdown?.contains(e.target);
                const isInsideJalur = jalurTrigger?.contains(e.target) || jalurDropdown?.contains(e.target);
                const isInsideGrade = gradeHeroTrigger?.contains(e.target) || gradeHeroDropdown?.contains(e.target);

                if (!isInsideMountain) closeMountainDropdown();
                if (!isInsideJalur) closeJalurDropdown();
                if (!isInsideGrade) closeGradeHeroDropdown();
            });
        </script>
    @endpush

</x-public-layout>
