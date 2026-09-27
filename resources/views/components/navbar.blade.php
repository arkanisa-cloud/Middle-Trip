@props([
    'active' => 'home',
    'hero' => false,
])

@php
    $initialInner = $hero
        ? 'w-full max-w-6xl mx-auto px-5 pt-7 pb-4 bg-transparent border-transparent shadow-none rounded-none text-white'
        : 'w-full max-w-6xl mx-auto px-5 py-4 bg-transparent border-transparent shadow-none rounded-none text-ink-heading';

    $initialLogo = $hero ? 'text-white' : 'text-ink-heading';
    $initialLogin = $hero ? 'text-white hover:text-white/80' : 'text-body-strong hover:text-ink-heading';
    $initialRegister = $hero ? 'bg-white text-gray-900 hover:bg-white/90' : 'bg-surface-dark hover:bg-black text-white';
@endphp

<div id="navbar-wrapper" class="fixed top-0 left-0 right-0 z-50 pt-0 px-0">
    <!-- Background datar atas untuk halaman katalog (menghilangkan bug garis hitam saat scroll) -->
    @if(!$hero)
        <div id="navbar-flat-bg" class="absolute inset-x-0 top-0 h-[68px] bg-white border-b border-hairline/80 pointer-events-none transition-opacity duration-200 opacity-100"></div>
    @endif

    <nav id="navbar-inner" class="relative z-10 {{ $initialInner }} flex items-center justify-between">
        
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-lg tracking-tight group">
            <svg id="navbar-logo-icon" class="w-6 h-6 {{ $initialLogo }} group-hover:text-primary transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m8 3 4 8 5-5 5 15H2L8 3z"/>
            </svg>
            <span id="navbar-logo-text" class="tracking-tight text-xl font-extrabold {{ $initialLogo }} transition-colors">MiddleTrip</span>
        </a>

        <!-- Center Menu: Hanya 3 Menu (Home, Ekspedisi, Kontak) -->
        <div class="hidden md:flex items-center gap-8 text-[14px] font-medium">
            <!-- 1. Home -->
            <a href="{{ route('home') }}" 
               id="nav-link-home" 
               class="nav-link-item {{ $active === 'home' ? ($hero ? 'text-white font-semibold relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-white' : 'text-ink-heading font-bold relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-ink-heading') : ($hero ? 'text-white/80 hover:text-white font-medium transition-colors duration-200 relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-white/90 after:transition-all after:duration-300' : 'text-muted hover:text-ink-heading font-medium transition-colors duration-200 relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-ink-heading after:transition-all after:duration-300') }}">
                Home
            </a>

            <!-- 2. Ekspedisi -->
            <a href="{{ route('ekspedisi.index') }}" 
               id="nav-link-ekspedisi" 
               class="nav-link-item {{ $active === 'ekspedisi' ? ($hero ? 'text-white font-semibold relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-white' : 'text-ink-heading font-bold relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-ink-heading') : ($hero ? 'text-white/80 hover:text-white font-medium transition-colors duration-200 relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-white/90 after:transition-all after:duration-300' : 'text-muted hover:text-ink-heading font-medium transition-colors duration-200 relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-ink-heading after:transition-all after:duration-300') }}">
                Ekspedisi
            </a>

            <!-- 3. Kontak -->
            <a href="{{ route('home') }}#contact" 
               id="nav-link-kontak" 
               class="nav-link-item {{ $active === 'kontak' ? ($hero ? 'text-white font-semibold relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-white' : 'text-ink-heading font-bold relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-ink-heading') : ($hero ? 'text-white/80 hover:text-white font-medium transition-colors duration-200 relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-white/90 after:transition-all after:duration-300' : 'text-muted hover:text-ink-heading font-medium transition-colors duration-200 relative after:content-[\'\'] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-ink-heading after:transition-all after:duration-300') }}">
                Kontak
            </a>
        </div>

        <!-- Auth Buttons -->
        <div class="flex items-center gap-4 text-[13.5px] font-medium">
            <a href="#login" id="nav-login-btn" class="{{ $initialLogin }} px-2 py-1 transition-colors">
                Login
            </a>
            <a href="#daftar" id="nav-register-btn" class="{{ $initialRegister }} font-semibold px-5 py-2 rounded-full shadow-sm transition-colors">
                Daftar
            </a>
        </div>
    </nav>
</div>

<script>
    (function() {
        const isHero = @json($hero);
        const activeItem = @json($active);

        const wrapper = document.getElementById('navbar-wrapper');
        const inner = document.getElementById('navbar-inner');
        const flatBg = document.getElementById('navbar-flat-bg');
        const logoIcon = document.getElementById('navbar-logo-icon');
        const logoText = document.getElementById('navbar-logo-text');
        const linkHome = document.getElementById('nav-link-home');
        const linkEkspedisi = document.getElementById('nav-link-ekspedisi');
        const linkKontak = document.getElementById('nav-link-kontak');
        const loginBtn = document.getElementById('nav-login-btn');
        const registerBtn = document.getElementById('nav-register-btn');

        if (!wrapper || !inner) return;

        let isCurrentlyScrolled = null;

        function updateNavbar(animated = true) {
            const shouldBeScrolled = window.scrollY > 20;
            if (shouldBeScrolled === isCurrentlyScrolled) return;
            isCurrentlyScrolled = shouldBeScrolled;

            // Aktifkan transisi halus hanya setelah user mulai scroll (bukan saat awal masuk halaman)
            if (animated) {
                wrapper.classList.add('transition-all', 'duration-300', 'ease-out');
                inner.classList.add('transition-all', 'duration-300', 'ease-out');
            }

            if (shouldBeScrolled) {
                // State Saat Scroll: Mengecil halus membentuk kapsul mengambang
                wrapper.className = 'fixed top-0 left-0 right-0 z-50 pt-3 px-4 md:px-6 transition-all duration-300 ease-out';
                inner.className = 'relative z-10 w-full max-w-5xl mx-auto bg-white/95 backdrop-blur-md rounded-full px-5 md:px-7 py-2.5 md:py-3 border border-hairline/80 shadow-md flex items-center justify-between text-ink-heading transition-all duration-300 ease-out';
                
                if (flatBg) {
                    flatBg.classList.remove('opacity-100');
                    flatBg.classList.add('opacity-0');
                }

                logoIcon.className = 'w-5 h-5 md:w-6 md:h-6 text-ink-heading group-hover:text-primary transition-colors';
                logoText.className = 'tracking-tight text-lg md:text-xl font-extrabold text-ink-heading transition-colors';

                setLinksTheme(false);

                loginBtn.className = 'text-body-strong hover:text-ink-heading px-2 py-1 transition-colors';
                registerBtn.className = 'bg-surface-dark hover:bg-black text-white font-semibold px-5 py-2 rounded-full shadow-sm transition-colors';
            } else {
                // State Mentok di Atas: Tampilan normal datar (tanpa garis hitam tertinggal)
                wrapper.className = 'fixed top-0 left-0 right-0 z-50 pt-0 px-0 transition-all duration-300 ease-out';

                if (flatBg) {
                    flatBg.classList.remove('opacity-0');
                    flatBg.classList.add('opacity-100');
                }

                if (isHero) {
                    inner.className = 'relative z-10 w-full max-w-6xl mx-auto px-5 pt-7 pb-4 bg-transparent border-transparent shadow-none rounded-none flex items-center justify-between text-white transition-all duration-300 ease-out';
                    
                    logoIcon.className = 'w-6 h-6 text-white group-hover:text-primary-subtle transition-colors';
                    logoText.className = 'tracking-tight text-xl font-extrabold text-white transition-colors';

                    setLinksTheme(true);

                    loginBtn.className = 'text-white hover:text-white/80 px-2 py-1 transition-colors';
                    registerBtn.className = 'bg-white text-gray-900 hover:bg-white/90 font-semibold px-5 py-2 rounded-full shadow-sm transition-colors';
                } else {
                    inner.className = 'relative z-10 w-full max-w-6xl mx-auto px-5 py-4 bg-transparent border-transparent shadow-none rounded-none flex items-center justify-between text-ink-heading transition-all duration-300 ease-out';
                    
                    logoIcon.className = 'w-6 h-6 text-ink-heading group-hover:text-primary transition-colors';
                    logoText.className = 'tracking-tight text-xl font-extrabold text-ink-heading transition-colors';

                    setLinksTheme(false);

                    loginBtn.className = 'text-body-strong hover:text-ink-heading px-2 py-1 transition-colors';
                    registerBtn.className = 'bg-surface-dark hover:bg-black text-white font-semibold px-5 py-2 rounded-full shadow-sm transition-colors';
                }
            }
        }

        function setLinksTheme(onHero) {
            const links = [
                { el: linkHome, name: 'home' },
                { el: linkEkspedisi, name: 'ekspedisi' },
                { el: linkKontak, name: 'kontak' }
            ];

            links.forEach(item => {
                if (!item.el) return;
                const isActive = item.name === activeItem;

                if (onHero) {
                    if (isActive) {
                        item.el.className = "nav-link-item text-white font-semibold relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-white";
                    } else {
                        item.el.className = "nav-link-item text-white/80 hover:text-white font-medium transition-colors duration-200 relative after:content-[''] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-white/90 after:transition-all after:duration-300";
                    }
                } else {
                    if (isActive) {
                        item.el.className = "nav-link-item text-ink-heading font-bold relative after:content-[''] after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-ink-heading";
                    } else {
                        item.el.className = "nav-link-item text-muted hover:text-ink-heading font-medium transition-colors duration-200 relative after:content-[''] after:absolute after:bottom-[-4px] after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-full after:h-[2px] after:bg-ink-heading after:transition-all after:duration-300";
                    }
                }
            });
        }

        // Jalankan evaluasi awal tanpa animasi saat pertama load
        if (window.scrollY > 20) {
            updateNavbar(false);
        }

        // Listener scroll halus
        window.addEventListener('scroll', () => updateNavbar(true), { passive: true });
    })();
</script>
