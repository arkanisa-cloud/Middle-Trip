<!-- Footer Component -->
<footer id="contact" class="w-full bg-canvas-alt border-t border-hairline/80 text-muted pt-14 pb-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Main Footer Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10 pb-12">

            <!-- Col 1: Brand & Bio (Span 4) -->
            <div class="lg:col-span-4 flex flex-col gap-4">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 font-extrabold text-ink-heading text-lg group">
                    <svg class="w-5 h-5 text-primary group-hover:scale-105 transition-transform shrink-0"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                    </svg>
                    <span>MiddleTrip</span>
                </a>

                <p class="text-xs text-body leading-relaxed max-w-sm">
                    Jalan Tengah Menuju Puncak yang Sesungguhnya. Platform ekspedisi pendakian gunung digital di
                    Indonesia dengan standarisasi grade jalur, kepastian keberangkatan, dan pemanduan profesional.
                </p>

                <!-- Quick Contact Info -->
                <div class="flex flex-col gap-2 pt-1 text-xs">
                    <a href="mailto:info@middletrip.id"
                        class="inline-flex items-center gap-2 text-muted hover:text-primary transition-colors">
                        <svg class="w-4 h-4 text-muted-soft shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>middletrip@gmail.com</span>
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 text-muted hover:text-primary transition-colors">
                        <svg class="w-4 h-4 text-muted-soft shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>+62 812-3456-7890 (Customer Service)</span>
                    </a>
                </div>
            </div>

            <!-- Col 2: Layanan & Ekspedisi (Span 2) -->
            <div class="lg:col-span-2 flex flex-col gap-3">
                <h4 class="text-xs font-bold text-ink-heading uppercase tracking-wider">Ekspedisi</h4>
                <ul class="flex flex-col gap-2.5 text-xs text-muted font-medium">
                    <li><a href="{{ route('ekspedisi.index') }}" class="hover:text-primary transition-colors">Katalog
                            Gunung</a></li>
                    <li><a href="{{ route('ekspedisi.index') }}?type=open"
                            class="hover:text-primary transition-colors">Open Trip Batch</a></li>
                    <li><a href="{{ route('ekspedisi.index') }}?type=private"
                            class="hover:text-primary transition-colors">Private Trip Eksklusif</a></li>
                    <li><a href="{{ route('home') }}#grade-section" class="hover:text-primary transition-colors">Standar
                            Grade Jalur</a></li>
                </ul>
            </div>

            <!-- Col 3: Bantuan & Ketentuan (Span 2) -->
            <div class="lg:col-span-2 flex flex-col gap-3">
                <h4 class="text-xs font-bold text-ink-heading uppercase tracking-wider">Pusat Bantuan</h4>
                <ul class="flex flex-col gap-2.5 text-xs text-muted font-medium">
                    <li><a href="{{ route('contact') }}" class="hover:text-primary transition-colors">Kontak Kami</a>
                    </li>
                    <li><a href="{{ route('home') }}#faq" class="hover:text-primary transition-colors">FAQ &amp;
                            SIMAKSI</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Syarat &amp; Ketentuan</a></li>
                </ul>
            </div>

            <!-- Col 4: Official Event & Partners JHIC 2.0 (Span 4) -->
            <div class="lg:col-span-4 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold text-ink-heading uppercase tracking-wider">Official Partners</h4>
                    <span
                        class="text-[10.5px] font-semibold text-muted-soft bg-gray-100/90 px-2 py-0.5 rounded-full">JHIC
                        2.0</span>
                </div>

                <p class="text-[11.5px] text-muted leading-relaxed">
                    Didukung penuh oleh ekosistem teknologi dan inovasi digital Indonesia:
                </p>

                <!-- Sponsor Logos (Clean, without bulky cards, optically balanced) -->
                <div class="flex flex-wrap items-center gap-x-6 gap-y-4 pt-2">
                    <img src="{{ asset('storage/jhic/1. LOGO JHIC 2.0.png') }}" alt="JHIC 2.0"
                        title="Jagoan Hosting Indonesia Competition 2.0"
                        class="h-7 w-auto object-contain opacity-85 hover:opacity-100 transition-opacity"
                        loading="lazy">

                    <img src="{{ asset('storage/jhic/2. Logo Jagoan Hosting.png') }}" alt="Jagoan Hosting"
                        title="Jagoan Hosting"
                        class="h-5 w-auto object-contain opacity-85 hover:opacity-100 transition-opacity"
                        loading="lazy">

                    <img src="{{ asset('storage/jhic/3. KOMDIGI.png') }}" alt="KOMDIGI"
                        title="Kementerian Komunikasi dan Digital RI"
                        class="h-7 w-auto object-contain opacity-85 hover:opacity-100 transition-opacity"
                        loading="lazy">

                    <img src="{{ asset('storage/jhic/4. Garuda Spark Full Color.png') }}" alt="Garuda Spark"
                        title="Garuda Spark"
                        class="h-6 w-auto object-contain opacity-85 hover:opacity-100 transition-opacity"
                        loading="lazy">

                    <img src="{{ asset('storage/jhic/5. LOGO NGALUP.png') }}" alt="Ngalup.co" title="Ngalup.co"
                        class="h-4 w-auto object-contain opacity-85 hover:opacity-100 transition-opacity"
                        loading="lazy">
                </div>
            </div>

        </div>

        <!-- Bottom Copyright Bar -->
        <div
            class="border-t border-hairline/80 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <p class="text-[11.5px] text-muted-soft">
                &copy; {{ date('Y') }} MiddleTrip Expedition Co. All rights reserved.
            </p>
        </div>

    </div>
</footer>
