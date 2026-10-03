@props([
    'active' => 'home',
    'hero' => false,
])

@php
    $initialInner = $hero
        ? 'w-full max-w-6xl mx-auto px-4 sm:px-6 pt-5 sm:pt-7 pb-3 sm:pb-4 bg-transparent border-transparent shadow-none rounded-none text-white'
        : 'w-full max-w-6xl mx-auto px-4 sm:px-6 py-3.5 sm:py-4 bg-transparent border-transparent shadow-none rounded-none text-ink-heading';

    $initialLogo = $hero ? 'text-white' : 'text-ink-heading';
    $initialSearchBtn = $hero
        ? 'text-white/85 hover:text-white bg-white/15 hover:bg-white/25 border-white/20'
        : 'text-slate-600 hover:text-ink-heading bg-slate-100 hover:bg-slate-200/80 border-slate-200/60';
    $initialBurger = $hero
        ? 'text-white/90 hover:text-white bg-white/15 hover:bg-white/25 border border-white/20 shadow-2xs backdrop-blur-xs'
        : 'text-slate-700 hover:text-ink-heading bg-slate-100 hover:bg-slate-200/80 border border-slate-200/60 shadow-2xs backdrop-blur-xs';
    $initialLogin = $hero ? 'text-white hover:text-white/80' : 'text-body-strong hover:text-ink-heading';
    $initialRegister = $hero ? 'bg-white text-gray-900 hover:bg-white/90' : 'bg-surface-dark hover:bg-black text-white';
@endphp

<div id="navbar-wrapper" class="fixed top-0 left-0 right-0 z-50 pt-0 px-0 transition-all duration-300 ease-out">
    <!-- Background datar atas untuk halaman non-hero / katalog -->
    @if (!$hero)
        <div id="navbar-flat-bg"
            class="absolute inset-x-0 top-0 h-[64px] sm:h-[68px] bg-white border-b border-hairline/80 pointer-events-none transition-opacity duration-200 opacity-100">
        </div>
    @endif

    <nav id="navbar-inner" class="relative z-10 {{ $initialInner }} flex items-center justify-between">

        <!-- Logo MiddleTrip -->
        <a href="{{ route('home') }}" id="navbar-logo-link"
            class="flex items-center gap-2 font-bold text-lg tracking-tight {{ $initialLogo }} group shrink-0">
            <svg id="navbar-logo-icon" class="w-6 h-6 group-hover:text-primary transition-colors" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
            </svg>
            <span id="navbar-logo-text"
                class="tracking-tight text-lg sm:text-xl font-extrabold group-hover:text-primary transition-colors">MiddleTrip</span>
        </a>

        <!-- Center Menu (Desktop Only: md:flex) -->
        <div class="hidden md:flex items-center gap-7 lg:gap-8 text-[14px] font-medium">
            <!-- 1. Home -->
            <a href="{{ route('home') }}" id="nav-link-home"
                class="nav-link-item {{ $active === 'home' ? ($hero ? 'text-white font-semibold relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-white' : 'text-ink-heading font-bold relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-ink-heading') : ($hero ? 'text-white/80 hover:text-white font-medium transition-colors duration-200 relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-white/90 after:transition-all after:duration-300' : 'text-muted hover:text-ink-heading font-medium transition-colors duration-200 relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-ink-heading after:transition-all after:duration-300') }}">
                Home
            </a>

            <!-- 2. Ekspedisi -->
            <a href="{{ route('ekspedisi.index') }}" id="nav-link-ekspedisi"
                class="nav-link-item {{ $active === 'ekspedisi' ? ($hero ? 'text-white font-semibold relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-white' : 'text-ink-heading font-bold relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-ink-heading') : ($hero ? 'text-white/80 hover:text-white font-medium transition-colors duration-200 relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-white/90 after:transition-all after:duration-300' : 'text-muted hover:text-ink-heading font-medium transition-colors duration-200 relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-ink-heading after:transition-all after:duration-300') }}">
                Ekspedisi
            </a>

            <!-- 3. Kontak -->
            <a href="{{ route('contact') }}" id="nav-link-kontak"
                class="nav-link-item {{ $active === 'kontak' ? ($hero ? 'text-white font-semibold relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-white' : 'text-ink-heading font-bold relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-ink-heading') : ($hero ? 'text-white/80 hover:text-white font-medium transition-colors duration-200 relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-white/90 after:transition-all after:duration-300' : 'text-muted hover:text-ink-heading font-medium transition-colors duration-200 relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-ink-heading after:transition-all after:duration-300') }}">
                Kontak
            </a>
        </div>

        <!-- Right Action Menu (Search, Auth, & Mobile Hamburger) -->
        <div class="flex items-center gap-2 sm:gap-3">

            <!-- Quick Search Trigger Button (Desktop & Mobile) -->
            <button type="button" onclick="openQuickSearchModal()" id="nav-search-btn" aria-label="Cari Gunung"
                class="flex items-center gap-2 px-3 py-1.5 rounded-full border text-xs font-medium transition-all duration-200 cursor-pointer {{ $initialSearchBtn }} shadow-2xs backdrop-blur-xs">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="hidden sm:inline text-[12px]">Cari gunung...</span>
            </button>

            <!-- Auth Buttons (Desktop Only: md:flex) -->
            <div class="hidden md:flex items-center gap-3 text-[13.5px] font-medium">
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" id="nav-admin-btn"
                            class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all inline-flex items-center gap-1.5 shadow-xs {{ $hero ? 'bg-white/20 text-white hover:bg-white hover:text-ink-heading border border-white/30' : 'bg-surface-forest text-white hover:bg-surface-forest-card border border-surface-forest-border' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <span>Panel Admin</span>
                        </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" id="nav-user-btn"
                        class="{{ $initialRegister }} font-semibold px-4 py-1.5 rounded-full shadow-sm transition-colors inline-flex items-center gap-2 max-w-[200px]">
                        @if (Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-5 h-5 rounded-full object-cover shrink-0 border border-white/40">
                        @endif
                        <span class="truncate">{{ Auth::user()->name }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" id="nav-login-btn"
                        class="{{ $initialLogin }} px-2 py-1 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" id="nav-register-btn"
                        class="{{ $initialRegister }} font-semibold px-5 py-2 rounded-full shadow-sm transition-colors">
                        Daftar
                    </a>
                @endauth
            </div>

            <!-- Hamburger Button (Mobile Only: md:hidden) -->
            <button type="button" id="nav-hamburger-btn" onclick="toggleMobileNav()" aria-label="Toggle Menu"
                aria-expanded="false"
                class="md:hidden relative flex flex-col items-center justify-center gap-1 w-9 h-9 rounded-full transition-all duration-300 {{ $initialBurger }} cursor-pointer">
                <span id="burger-line-1"
                    class="w-4 h-[1.8px] bg-current rounded-full transition-all duration-300 ease-in-out"></span>
                <span id="burger-line-2"
                    class="w-4 h-[1.8px] bg-current rounded-full transition-all duration-300 ease-in-out"></span>
                <span id="burger-line-3"
                    class="w-4 h-[1.8px] bg-current rounded-full transition-all duration-300 ease-in-out"></span>
            </button>
        </div>
    </nav>

    <!-- Mobile Navigation Drawer / Dropdown Panel -->
    <div id="mobile-nav-panel"
        class="hidden md:hidden mx-4 mt-2 bg-white/95 backdrop-blur-xl border border-hairline/80 rounded-2xl shadow-xl overflow-hidden transition-all duration-300 ease-out origin-top opacity-0 -translate-y-2 scale-98 pointer-events-none">
        <div class="p-4 space-y-3">
            <!-- Nav Links List -->
            <div class="space-y-1">
                <a href="{{ route('home') }}"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ $active === 'home' ? 'bg-primary/10 text-primary' : 'text-slate-700 hover:bg-slate-50' }} transition">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $active === 'home' ? 'text-primary' : 'text-slate-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Home</span>
                    </div>
                    @if ($active === 'home')
                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    @endif
                </a>

                <a href="{{ route('ekspedisi.index') }}"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ $active === 'ekspedisi' ? 'bg-primary/10 text-primary' : 'text-slate-700 hover:bg-slate-50' }} transition">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $active === 'ekspedisi' ? 'text-primary' : 'text-slate-400' }}"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                        </svg>
                        <span>Ekspedisi Gunung</span>
                    </div>
                    @if ($active === 'ekspedisi')
                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    @endif
                </a>

                <a href="{{ route('contact') }}"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold {{ $active === 'kontak' ? 'bg-primary/10 text-primary' : 'text-slate-700 hover:bg-slate-50' }} transition">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 {{ $active === 'kontak' ? 'text-primary' : 'text-slate-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Kontak Kami</span>
                    </div>
                    @if ($active === 'kontak')
                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    @endif
                </a>
            </div>

            <!-- Divider -->
            <div class="border-t border-slate-100 pt-3">
                @auth
                    <div class="space-y-2">
                        <div class="flex items-center gap-2.5 px-3.5 py-2 bg-slate-50 rounded-xl">
                            @if (Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover shrink-0 border border-slate-200">
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <span
                                class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">Member</span>
                        </div>

                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-surface-forest hover:bg-surface-forest-card text-white text-xs font-bold rounded-xl transition shadow-xs">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>Masuk Panel Admin</span>
                            </a>
                        @endif

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center justify-center px-3 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition">
                                Pengaturan Akun
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center justify-center px-3 py-2 border border-rose-200 bg-rose-50/50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-xl transition cursor-pointer">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}"
                            class="flex items-center justify-center px-4 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-800 text-xs font-bold rounded-xl transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="flex items-center justify-center px-4 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-xs transition">
                            Daftar Akun
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL QUICK SEARCH GUNUNG (GLOBAL EXPRESS SEARCH MODAL)
========================================================================= -->
<div id="quick-search-modal"
    class="fixed inset-0 z-50 hidden items-start justify-center p-4 sm:p-6 md:p-10 transition-opacity duration-200">
    <!-- Backdrop Backdrop Blur -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeQuickSearchModal()">
    </div>

    <!-- Modal Content Card -->
    <div
        class="relative w-full max-w-2xl bg-white rounded-2xl md:rounded-3xl shadow-2xl border border-slate-100 overflow-hidden z-10 flex flex-col max-h-[85vh] transition-all transform duration-200 mt-6 sm:mt-12">

        <!-- Header & Search Input Box -->
        <div class="p-4 sm:p-5 border-b border-slate-100 bg-white">
            <div class="relative flex items-center">
                <svg class="absolute left-3.5 w-5 h-5 text-slate-400 pointer-events-none" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="quick-search-input" autocomplete="off"
                    placeholder="Ketik nama gunung, lokasi, atau jalur (misal: Merbabu, Prau)..."
                    class="w-full pl-11 pr-20 py-3 text-sm font-outfit font-normal text-slate-800 placeholder-slate-400 bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition" />

                <div class="absolute right-2.5 flex items-center gap-1.5">
                    <button type="button" id="quick-search-clear-btn" onclick="clearQuickSearchInput()"
                        class="hidden p-1 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-200/60 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <button type="button" onclick="closeQuickSearchModal()"
                        class="p-1 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition cursor-pointer text-xs font-semibold px-2 py-1">
                        ESC
                    </button>
                </div>
            </div>

            <!-- Grade Filter Chips -->
            <div class="flex items-center gap-1.5 mt-3 overflow-x-auto no-scrollbar pt-1">
                <span class="text-[11px] font-semibold text-slate-400 shrink-0 mr-1">Filter:</span>
                <button type="button" onclick="setQuickSearchGrade('all', this)"
                    class="quick-grade-chip px-3 py-1 rounded-full text-xs font-semibold bg-primary text-white transition whitespace-nowrap cursor-pointer">
                    Semua
                </button>
                <button type="button" onclick="setQuickSearchGrade('Grade A', this)"
                    class="quick-grade-chip px-3 py-1 rounded-full text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition whitespace-nowrap cursor-pointer">
                    Grade A (Pemula)
                </button>
                <button type="button" onclick="setQuickSearchGrade('Grade B', this)"
                    class="quick-grade-chip px-3 py-1 rounded-full text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition whitespace-nowrap cursor-pointer">
                    Grade B (Menengah)
                </button>
                <button type="button" onclick="setQuickSearchGrade('Grade C', this)"
                    class="quick-grade-chip px-3 py-1 rounded-full text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition whitespace-nowrap cursor-pointer">
                    Grade C (Ahli)
                </button>
            </div>
        </div>

        <!-- Search Results List Container -->
        <div id="quick-search-results"
            class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-2.5 divide-y divide-slate-100">
            <!-- Loading Indicator (Hidden by default) -->
            <div id="quick-search-loading" class="hidden text-center py-10 text-slate-400 text-xs">
                <svg class="w-6 h-6 animate-spin mx-auto text-primary mb-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span>Mencari rute ekspedisi...</span>
            </div>

            <!-- List of Mountains Container -->
            <div id="quick-search-items-container" class="space-y-2">
                <!-- Dynamically populated via JS -->
            </div>

            <!-- Empty State -->
            <div id="quick-search-empty" class="hidden text-center py-12 px-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h4 class="text-sm font-bold text-slate-700">Gunung tidak ditemukan</h4>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Coba cari dengan kata kunci lain atau lihat
                    seluruh daftar di katalog kami.</p>
                <a href="{{ route('ekspedisi.index') }}"
                    class="inline-flex items-center gap-1 mt-3 text-xs font-semibold text-primary hover:underline">
                    <span>Buka Katalog Ekspedisi</span>
                    <span>→</span>
                </a>
            </div>
        </div>

        <!-- Footer Info -->
        <div
            class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
            <span>Pilih salah satu gunung untuk melihat detail jalur & jadwal trip</span>
            <span class="hidden sm:inline">Tekan <kbd
                    class="px-1.5 py-0.5 bg-white border border-slate-200 rounded font-semibold text-slate-600">Esc</kbd>
                untuk menutup</span>
        </div>
    </div>
</div>

<script>
    (function() {
        const isHero = @json($hero);
        const activeItem = @json($active);

        const wrapper = document.getElementById('navbar-wrapper');
        const inner = document.getElementById('navbar-inner');
        const flatBg = document.getElementById('navbar-flat-bg');
        const logoLink = document.getElementById('navbar-logo-link');
        const logoIcon = document.getElementById('navbar-logo-icon');
        const logoText = document.getElementById('navbar-logo-text');
        const linkHome = document.getElementById('nav-link-home');
        const linkEkspedisi = document.getElementById('nav-link-ekspedisi');
        const linkKontak = document.getElementById('nav-link-kontak');
        const searchBtn = document.getElementById('nav-search-btn');
        const burgerBtn = document.getElementById('nav-hamburger-btn');
        const adminBtn = document.getElementById('nav-admin-btn');
        const loginBtn = document.getElementById('nav-login-btn');
        const registerBtn = document.getElementById('nav-register-btn');
        const userBtn = document.getElementById('nav-user-btn');

        if (!wrapper || !inner) return;

        let isCurrentlyScrolled = null;

        function updateNavbar(animated = true) {
            const shouldBeScrolled = window.scrollY > 20;
            if (shouldBeScrolled === isCurrentlyScrolled) return;
            isCurrentlyScrolled = shouldBeScrolled;

            if (animated) {
                wrapper.classList.add('transition-all', 'duration-300', 'ease-out');
                inner.classList.add('transition-all', 'duration-300', 'ease-out');
            }

            if (shouldBeScrolled) {
                // State Saat Scroll: Mengambang bentuk kapsul
                wrapper.className =
                    'fixed top-0 left-0 right-0 z-50 pt-2.5 sm:pt-3 px-3 sm:px-6 transition-all duration-300 ease-out';
                inner.className =
                    'relative z-10 w-full max-w-5xl mx-auto bg-white/95 backdrop-blur-md rounded-full px-4 sm:px-7 py-2 sm:py-2.5 border border-hairline/80 shadow-md flex items-center justify-between text-ink-heading transition-all duration-300 ease-out';

                if (flatBg) {
                    flatBg.classList.remove('opacity-100');
                    flatBg.classList.add('opacity-0');
                }

                if (logoLink) {
                    logoLink.classList.remove('text-white');
                    logoLink.classList.add('text-ink-heading');
                }
                if (logoIcon) {
                    logoIcon.setAttribute('class', 'w-6 h-6 group-hover:text-primary transition-colors');
                }
                if (logoText) {
                    logoText.className =
                        'tracking-tight text-lg sm:text-xl font-extrabold group-hover:text-primary transition-colors';
                }

                setLinksTheme(false);

                if (searchBtn) {
                    searchBtn.className =
                        'flex items-center gap-2 px-3 py-1.5 rounded-full border text-xs font-medium transition-all duration-200 cursor-pointer text-slate-600 hover:text-ink-heading bg-slate-100 hover:bg-slate-200/80 border-slate-200/60 shadow-2xs backdrop-blur-xs';
                }
                if (burgerBtn) {
                    burgerBtn.className =
                        'md:hidden relative flex flex-col items-center justify-center gap-1 w-9 h-9 rounded-full transition-all duration-300 text-slate-700 hover:text-ink-heading bg-slate-100 hover:bg-slate-200/80 border border-slate-200/60 shadow-2xs backdrop-blur-xs cursor-pointer';
                }
                if (adminBtn) {
                    adminBtn.className =
                        'px-3.5 py-1.5 rounded-full text-xs font-bold transition-all inline-flex items-center gap-1.5 shadow-xs bg-surface-forest text-white hover:bg-surface-forest-card border border-surface-forest-border';
                }
                if (loginBtn) {
                    loginBtn.className =
                        'text-body-strong hover:text-ink-heading px-3 py-1.5 rounded-full transition-colors';
                }
                if (registerBtn) {
                    registerBtn.className =
                        'bg-surface-dark hover:bg-black text-white font-semibold px-5 py-2 rounded-full shadow-sm transition-colors';
                }
                if (userBtn) {
                    userBtn.className =
                        'bg-surface-dark hover:bg-black text-white font-semibold px-5 py-2 rounded-full shadow-sm transition-colors inline-flex items-center gap-2 max-w-[200px]';
                }
            } else {
                // State Mentok di Atas
                wrapper.className =
                    'fixed top-0 left-0 right-0 z-50 pt-0 px-0 transition-all duration-300 ease-out';

                if (flatBg) {
                    flatBg.classList.remove('opacity-0');
                    flatBg.classList.add('opacity-100');
                }

                if (isHero) {
                    inner.className =
                        'relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 pt-5 sm:pt-7 pb-3 sm:pb-4 bg-transparent border-transparent shadow-none rounded-none flex items-center justify-between text-white transition-all duration-300 ease-out';

                    if (logoLink) {
                        logoLink.classList.remove('text-ink-heading');
                        logoLink.classList.add('text-white');
                    }
                    if (logoIcon) {
                        logoIcon.setAttribute('class', 'w-6 h-6 group-hover:text-primary-subtle transition-colors');
                    }
                    if (logoText) {
                        logoText.className =
                            'tracking-tight text-lg sm:text-xl font-extrabold group-hover:text-primary-subtle transition-colors';
                    }

                    setLinksTheme(true);

                    if (searchBtn) {
                        searchBtn.className =
                            'flex items-center gap-2 px-3 py-1.5 rounded-full border text-xs font-medium transition-all duration-200 cursor-pointer text-white/85 hover:text-white bg-white/15 hover:bg-white/25 border-white/20 shadow-2xs backdrop-blur-xs';
                    }
                    if (burgerBtn) {
                        burgerBtn.className =
                            'md:hidden relative flex flex-col items-center justify-center gap-1 w-9 h-9 rounded-full transition-all duration-300 text-white/90 hover:text-white bg-white/15 hover:bg-white/25 border border-white/20 shadow-2xs backdrop-blur-xs cursor-pointer';
                    }
                    if (adminBtn) {
                        adminBtn.className =
                            'px-3.5 py-1.5 rounded-full text-xs font-bold transition-all inline-flex items-center gap-1.5 shadow-xs bg-white/20 text-white hover:bg-white hover:text-ink-heading border border-white/30';
                    }
                    if (loginBtn) {
                        loginBtn.className =
                            'text-white hover:text-white/80 px-3 py-1.5 rounded-full transition-colors';
                    }
                    if (registerBtn) {
                        registerBtn.className =
                            'bg-white text-gray-900 hover:bg-white/90 font-semibold px-5 py-2 rounded-full shadow-sm transition-colors';
                    }
                    if (userBtn) {
                        userBtn.className =
                            'bg-white text-gray-900 hover:bg-white/90 font-semibold px-5 py-2 rounded-full shadow-sm transition-colors inline-flex items-center gap-2 max-w-[200px]';
                    }
                } else {
                    inner.className =
                        'relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 py-3.5 sm:py-4 bg-transparent border-transparent shadow-none rounded-none flex items-center justify-between text-ink-heading transition-all duration-300 ease-out';

                    if (logoLink) {
                        logoLink.classList.remove('text-white');
                        logoLink.classList.add('text-ink-heading');
                    }
                    if (logoIcon) {
                        logoIcon.setAttribute('class', 'w-6 h-6 group-hover:text-primary transition-colors');
                    }
                    if (logoText) {
                        logoText.className =
                            'tracking-tight text-lg sm:text-xl font-extrabold group-hover:text-primary transition-colors';
                    }

                    setLinksTheme(false);

                    if (searchBtn) {
                        searchBtn.className =
                            'flex items-center gap-2 px-3 py-1.5 rounded-full border text-xs font-medium transition-all duration-200 cursor-pointer text-slate-600 hover:text-ink-heading bg-slate-100 hover:bg-slate-200/80 border-slate-200/60 shadow-2xs backdrop-blur-xs';
                    }
                    if (burgerBtn) {
                        burgerBtn.className =
                            'md:hidden relative flex flex-col items-center justify-center gap-1 w-9 h-9 rounded-full transition-all duration-300 text-slate-700 hover:text-ink-heading bg-slate-100 hover:bg-slate-200/80 border border-slate-200/60 shadow-2xs backdrop-blur-xs cursor-pointer';
                    }
                    if (loginBtn) {
                        loginBtn.className =
                            'text-body-strong hover:text-ink-heading px-3 py-1.5 rounded-full transition-colors';
                    }
                    if (registerBtn) {
                        registerBtn.className =
                            'bg-surface-dark hover:bg-black text-white font-semibold px-5 py-2 rounded-full shadow-sm transition-colors';
                    }
                    if (userBtn) {
                        userBtn.className =
                            'bg-surface-dark hover:bg-black text-white font-semibold px-5 py-2 rounded-full shadow-sm transition-colors inline-flex items-center gap-2 max-w-[200px]';
                    }
                }
            }
        }

        function setLinksTheme(onHero) {
            const links = [{
                    el: linkHome,
                    name: 'home'
                },
                {
                    el: linkEkspedisi,
                    name: 'ekspedisi'
                },
                {
                    el: linkKontak,
                    name: 'kontak'
                }
            ];

            links.forEach(item => {
                if (!item.el) return;
                const isActive = item.name === activeItem;

                if (onHero) {
                    if (isActive) {
                        item.el.className =
                            "nav-link-item text-white font-semibold relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-white";
                    } else {
                        item.el.className =
                            "nav-link-item text-white/80 hover:text-white font-medium transition-colors duration-200 relative after:content-[''] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-white/90 after:transition-all after:duration-300";
                    }
                } else {
                    if (isActive) {
                        item.el.className =
                            "nav-link-item text-ink-heading font-bold relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-ink-heading";
                    } else {
                        item.el.className =
                            "nav-link-item text-muted hover:text-ink-heading font-medium transition-colors duration-200 relative after:content-[''] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-ink-heading after:transition-all after:duration-300";
                    }
                }
            });
        }

        if (window.scrollY > 20) {
            updateNavbar(false);
        }

        window.addEventListener('scroll', () => updateNavbar(true), {
            passive: true
        });
    })();

    // ==========================================
    // MOBILE NAVIGATION TOGGLE LOGIC
    // ==========================================
    let isMobileNavOpen = false;

    function toggleMobileNav() {
        const panel = document.getElementById('mobile-nav-panel');
        const burgerBtn = document.getElementById('nav-hamburger-btn');
        const line1 = document.getElementById('burger-line-1');
        const line2 = document.getElementById('burger-line-2');
        const line3 = document.getElementById('burger-line-3');

        if (!panel) return;

        isMobileNavOpen = !isMobileNavOpen;
        if (isMobileNavOpen) {
            panel.classList.remove('hidden');
            requestAnimationFrame(() => {
                panel.classList.remove('opacity-0', '-translate-y-2', 'scale-98', 'pointer-events-none');
                panel.classList.add('opacity-100', 'translate-y-0', 'scale-100', 'pointer-events-auto');
            });

            // Smooth morphing to X
            line1?.classList.add('rotate-45', 'translate-y-[5.5px]');
            line2?.classList.add('opacity-0', 'scale-x-0');
            line3?.classList.add('-rotate-45', '-translate-y-[5.5px]');

            burgerBtn?.setAttribute('aria-expanded', 'true');
        } else {
            panel.classList.remove('opacity-100', 'translate-y-0', 'scale-100', 'pointer-events-auto');
            panel.classList.add('opacity-0', '-translate-y-2', 'scale-98', 'pointer-events-none');

            // Morph back to 3 lines
            line1?.classList.remove('rotate-45', 'translate-y-[5.5px]');
            line2?.classList.remove('opacity-0', 'scale-x-0');
            line3?.classList.remove('-rotate-45', '-translate-y-[5.5px]');

            setTimeout(() => {
                if (!isMobileNavOpen) {
                    panel.classList.add('hidden');
                }
            }, 300);

            burgerBtn?.setAttribute('aria-expanded', 'false');
        }
    }

    // ==========================================
    // QUICK SEARCH MODAL LOGIC
    // ==========================================
    let quickSearchData = [];
    let activeQuickGrade = 'all';
    let searchDebounceTimer = null;

    function openQuickSearchModal() {
        const modal = document.getElementById('quick-search-modal');
        const input = document.getElementById('quick-search-input');
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');

        // Fetch data awal jika belum ada
        if (quickSearchData.length === 0) {
            fetchQuickSearch();
        } else {
            renderQuickSearchResults();
        }

        setTimeout(() => {
            input?.focus();
        }, 50);
    }

    function closeQuickSearchModal() {
        const modal = document.getElementById('quick-search-modal');
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function clearQuickSearchInput() {
        const input = document.getElementById('quick-search-input');
        const clearBtn = document.getElementById('quick-search-clear-btn');
        if (input) {
            input.value = '';
            input.focus();
        }
        clearBtn?.classList.add('hidden');
        fetchQuickSearch();
    }

    function setQuickSearchGrade(grade, btnEl) {
        activeQuickGrade = grade;
        document.querySelectorAll('.quick-grade-chip').forEach(btn => {
            btn.className =
                'quick-grade-chip px-3 py-1 rounded-full text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition whitespace-nowrap cursor-pointer';
        });
        if (btnEl) {
            btnEl.className =
                'quick-grade-chip px-3 py-1 rounded-full text-xs font-semibold bg-primary text-white transition whitespace-nowrap cursor-pointer';
        }
        fetchQuickSearch();
    }

    function fetchQuickSearch() {
        const input = document.getElementById('quick-search-input');
        const keyword = input ? input.value.trim() : '';
        const clearBtn = document.getElementById('quick-search-clear-btn');
        const loading = document.getElementById('quick-search-loading');
        const container = document.getElementById('quick-search-items-container');
        const emptyState = document.getElementById('quick-search-empty');

        if (keyword.length > 0) {
            clearBtn?.classList.remove('hidden');
        } else {
            clearBtn?.classList.add('hidden');
        }

        loading?.classList.remove('hidden');
        container.innerHTML = '';
        emptyState?.classList.add('hidden');

        const params = new URLSearchParams();
        if (keyword) params.append('q', keyword);
        if (activeQuickGrade && activeQuickGrade !== 'all') params.append('grade', activeQuickGrade);

        fetch(`{{ route('api.mountains.search') }}?${params.toString()}`)
            .then(res => res.json())
            .then(data => {
                loading?.classList.add('hidden');
                if (data.status === 'success' && data.data.length > 0) {
                    quickSearchData = data.data;
                    renderQuickSearchResults();
                } else {
                    quickSearchData = [];
                    emptyState?.classList.remove('hidden');
                }
            })
            .catch(err => {
                console.error('Quick search error:', err);
                loading?.classList.add('hidden');
                emptyState?.classList.remove('hidden');
            });
    }

    function renderQuickSearchResults() {
        const container = document.getElementById('quick-search-items-container');
        const emptyState = document.getElementById('quick-search-empty');
        if (!container) return;

        if (quickSearchData.length === 0) {
            container.innerHTML = '';
            emptyState?.classList.remove('hidden');
            return;
        }

        emptyState?.classList.add('hidden');
        let html = '';

        quickSearchData.forEach(item => {
            html += `
                <a href="${item.url}"
                    class="group flex items-center justify-between p-2.5 sm:p-3 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200/80 transition cursor-pointer">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-lg overflow-hidden bg-slate-200 shrink-0 relative">
                            <img src="${item.cover_image}" alt="${item.name}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h4 class="text-sm font-bold text-slate-800 group-hover:text-primary transition truncate">${item.name}</h4>
                                <span class="${item.grade_badge} text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0">${item.grade}</span>
                            </div>
                            <p class="text-[11px] text-slate-500 truncate mt-0.5">${item.location || item.elevation} • <span class="font-liberation text-slate-600 font-semibold">${item.elevation}</span></p>
                        </div>
                    </div>
                    <div class="text-right shrink-0 pl-2">
                        <span class="text-xs sm:text-sm font-bold text-primary block">${item.price}</span>
                        <span class="text-[10px] text-slate-400 group-hover:text-primary group-hover:translate-x-0.5 transition inline-block">Detail →</span>
                    </div>
                </a>
            `;
        });

        container.innerHTML = html;
    }

    // Input Search Debounce Listener
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('quick-search-input');
        if (input) {
            input.addEventListener('input', () => {
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(fetchQuickSearch, 250);
            });
        }

        // Global Keyboard Shortcut (Cmd+K / Ctrl+K / Esc)
        window.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                openQuickSearchModal();
            }
            if (e.key === 'Escape') {
                closeQuickSearchModal();
            }
        });
    });
</script>
