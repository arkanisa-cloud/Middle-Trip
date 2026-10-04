<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Masuk ke Akun - MiddleTrip</title>
    <meta name="description"
        content="Masuk ke akun pendaki MiddleTrip Anda untuk mengelola jadwal ekspedisi, tiket SIMAKSI, dan reservasi pendakian gunung.">

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Styles / Scripts -->
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-canvas text-ink antialiased selection:bg-primary selection:text-white font-sans h-screen overflow-hidden flex">

    <div class="flex-1 flex flex-col lg:flex-row h-screen w-full overflow-hidden">

        <!-- =====================================================================
             1. LEFT PANEL: EDITORIAL EXPEDITION SHOWCASE (Desktop)
        ===================================================================== -->
        <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 h-full relative bg-cover bg-center flex-col justify-between p-8 xl:p-12 overflow-hidden"
            style="background-image: linear-gradient(180deg, rgba(7, 26, 22, 0.15) 0%, rgba(7, 26, 22, 0.55) 100%), url('{{ asset('storage/mountains/login.jpg') }}');">

            <!-- Decorative Topographic Accent Overlay -->
            <div class="absolute inset-0 topo-pattern pointer-events-none opacity-20"></div>

            <!-- Top Header: Back to Home & Brand Indicator -->
            <div class="relative z-10 flex items-center justify-between">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-md border border-white/20 transition-all duration-200 hover:scale-[1.02] cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>

            <!-- Center Showcase: Atmosphere & Quote -->
            <div class="relative z-10 my-auto py-8 max-w-lg">
                <h2
                    class="text-3xl xl:text-4xl font-extrabold text-white leading-tight tracking-tight mb-4 drop-shadow-md">
                    Jalan Tengah Menuju Puncak yang Sesungguhnya.
                </h2>
                <p class="text-white/90 text-sm leading-relaxed mb-6 font-normal drop-shadow-sm">
                    MiddleTrip menghubungkan pendaki pemula hingga penjelajah berpengalaman dengan sistem panduan
                    terstandarisasi, izin SIMAKSI legal, dan perlengkapan siap ekspedisi.
                </p>

                <!-- Value Highlights Grid -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                </div>
            </div>

            <!-- Bottom Note -->
            <div
                class="relative z-10 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-white/60">
                <span class="text-[11px] font-mono">EDISI 2026</span>
            </div>
        </div>

        <!-- =====================================================================
             2. RIGHT PANEL: MODERN LOGIN FORM
        ===================================================================== -->
        <div
            class="w-full lg:w-7/12 xl:w-1/2 h-full flex flex-col justify-between p-4 sm:p-6 lg:p-8 xl:p-10 bg-canvas overflow-y-auto lg:overflow-hidden">

            <!-- Mobile Top Bar: Back Link & Logo -->
            <div class="flex items-center justify-between w-full mb-4 lg:mb-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <svg class="w-6 h-6 text-primary group-hover:scale-105 transition-transform" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                    </svg>
                    <span
                        class="text-xl font-extrabold tracking-tight text-ink-heading group-hover:text-primary transition-colors">
                        MiddleTrip
                    </span>
                </a>

                <a href="{{ route('home') }}"
                    class="lg:hidden inline-flex items-center gap-1.5 text-xs font-semibold text-muted hover:text-ink-heading px-3 py-1.5 rounded-full bg-white border border-hairline shadow-2xs transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Beranda</span>
                </a>
            </div>

            <!-- Main Form Card Area -->
            <div class="w-full max-w-md mx-auto my-auto py-2">

                <!-- Header Typography -->
                <div class="mb-5 text-left">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-heading tracking-tight mb-1.5">
                        Selamat Datang Kembali
                    </h1>
                    <p class="text-xs sm:text-sm text-muted leading-relaxed">
                        Masuk ke akun MiddleTrip Anda untuk memantau status pesanan, pembayaran, dan jadwal pendakian gunung.
                    </p>
                </div>

                <!-- Session Status Success Banner -->
                @if (session('status'))
                    <div
                        class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs flex items-start gap-3 shadow-2xs">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-semibold">{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Validation Errors Banner -->
                @if ($errors->any())
                    <div
                        class="mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs flex items-start gap-3 shadow-2xs">
                        <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <span class="font-bold block mb-1">Gagal masuk ke akun:</span>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Form Card -->
                <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-7 shadow-xs">
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email Address Input -->
                        <div>
                            <label for="email"
                                class="block text-[11px] font-bold text-ink-heading uppercase tracking-wider mb-1.5">
                                Alamat Email
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    autofocus autocomplete="username" placeholder="nama@email.com"
                                    class="w-full pl-10 pr-4 py-2.5 border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-hairline bg-white' }} rounded-xl text-xs sm:text-sm text-ink outline-none focus:border-primary focus:ring-1 focus:ring-primary transition shadow-2xs placeholder-muted-soft">
                            </div>
                            @error('email')
                                <p class="text-xs text-rose-600 mt-1 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password"
                                    class="block text-[11px] font-bold text-ink-heading uppercase tracking-wider">
                                    Kata Sandi
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-xs font-semibold text-primary hover:text-primary-hover transition-colors">
                                        Lupa kata sandi?
                                    </a>
                                @endif
                            </div>

                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2"
                                            ry="2" />
                                        <path d="M7 11V7a5 5 0 0110 0v4" />
                                    </svg>
                                </div>
                                <input type="password" id="password" name="password" required
                                    autocomplete="current-password" placeholder="••••••••"
                                    class="w-full pl-10 pr-11 py-2.5 border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-hairline bg-white' }} rounded-xl text-xs sm:text-sm text-ink outline-none focus:border-primary focus:ring-1 focus:ring-primary transition shadow-2xs placeholder-muted-soft">

                                <!-- Password Toggle Show/Hide Button -->
                                <button type="button" id="toggle-password-btn"
                                    aria-label="Tampilkan atau sembunyikan kata sandi"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-muted hover:text-ink-heading transition-colors cursor-pointer">
                                    <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="eye-off-icon" class="w-4 h-4 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-rose-600 mt-1 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="flex items-center justify-between pt-0.5">
                            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer group">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="w-4 h-4 rounded border-hairline text-primary focus:ring-primary focus:ring-offset-0 transition cursor-pointer">
                                <span
                                    class="text-xs font-medium text-body-strong group-hover:text-ink-heading transition-colors">
                                    Ingat saya di perangkat ini
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button: button-primary from DESIGN.md -->
                        <div class="pt-1">
                            <button type="submit"
                                class="w-full bg-primary hover:bg-primary-hover active:bg-primary-active active:scale-[0.99] text-white font-bold text-xs sm:text-sm py-2.5 sm:py-3 px-6 rounded-full shadow-sm hover:shadow transition-all duration-200 cursor-pointer flex items-center justify-center gap-2 tracking-wide">
                                <span>Masuk ke Akun</span>
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>

                    <!-- Google Social Login Button -->
                    <div class="mt-3">
                        <a href="{{ route('auth.google') }}"
                            class="w-full inline-flex items-center justify-center gap-3 px-4 py-2 sm:py-2.5 rounded-full border border-hairline bg-white hover:bg-canvas text-ink-heading hover:text-ink text-xs sm:text-sm font-semibold transition-all duration-200 shadow-2xs hover:shadow-xs cursor-pointer group">
                            <!-- Official Google "G" SVG Icon -->
                            <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 shrink-0" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z" />
                                <path fill="#34A853"
                                    d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.34 24 12 24z" />
                                <path fill="#FBBC05"
                                    d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z" />
                                <path fill="#EA4335"
                                    d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.34 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z" />
                            </svg>
                            <span>Masuk dengan Google</span>
                        </a>
                    </div>
                </div>

                <!-- Registration Footer Callout -->
                <div class="mt-4 text-center">
                    <p class="text-xs text-muted">
                        Belum memiliki akun pendaki?
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="font-bold text-ink-heading hover:text-primary transition-colors underline underline-offset-4 decoration-primary/40 hover:decoration-primary ml-1">
                                Buat Akun Baru Sekarang
                            </a>
                        @endif
                    </p>
                </div>

            </div>

            <!-- Footer Bottom Copy -->
            <div class="w-full text-center py-2 text-[11px] text-muted-soft">
                &copy; {{ date('Y') }} MiddleTrip Expedition Platform. Seluruh hak cipta dilindungi.
            </div>

        </div>

    </div>

    <!-- Toggle Password Visibility Vanilla Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('toggle-password-btn');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function() {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

                    if (isPassword) {
                        eyeIcon.classList.add('hidden');
                        eyeOffIcon.classList.remove('hidden');
                    } else {
                        eyeIcon.classList.remove('hidden');
                        eyeOffIcon.classList.add('hidden');
                    }
                });
            }
        });
    </script>

</body>

</html>

