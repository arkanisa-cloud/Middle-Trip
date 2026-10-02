<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Kami - Basecamp Bantuan MiddleTrip</title>
    <meta name="description"
        content="Pusat komunikasi dan bantuan ekspedisi pendakian gunung Indonesia. Konsultasi trip, jadwal open trip, private trip, perizinan SIMAKSI, dan sewa perlengkapan.">

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
    <x-navbar active="kontak" :hero="false" />

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 md:pt-28 pb-20 w-full flex-1">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs font-medium text-muted mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="text-muted-soft">&gt;</span>
            <span class="text-body-strong font-semibold">Kontak & Pusat Bantuan</span>
        </nav>

        <!-- Page Header -->
        <div class="mb-10 max-w-3xl">
            <div
                class="inline-flex items-center gap-2 bg-primary-subtle text-primary border border-primary/20 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
                <svg class="w-3.5 h-3.5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span>Pusat Bantuan & Komunikasi</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-[44px] font-extrabold text-ink-heading tracking-tight mb-3">
                Ada Pertanyaan Seputar Ekspedisi?
            </h1>

            <p class="text-muted text-sm sm:text-base leading-relaxed">
                Ingin konsultasi rute pemula, cek kuota <span class="text-body-strong font-semibold">Open Trip</span>,
                request agenda <span class="text-body-strong font-semibold">Private Trip</span>, atau kendala
                pembayaran? Tim operasional kami siap memandu pendakian Anda dengan ramah dan cepat.
            </p>
        </div>

        <!-- Main Layout: 2 Columns (Direct Channels Left, Interactive Form Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- LEFT COLUMN: Saluran Kontak Cepat & Catatan Darurat (5 cols) -->
            <div class="lg:col-span-5 space-y-5">

                <!-- 1. WhatsApp Priority Card -->
                <div
                    class="bg-surface-card border border-hairline hover:border-emerald-300 rounded-3xl p-6 shadow-sm transition-all duration-300 group">
                    <div class="flex items-center justify-between gap-3 mb-3.5">
                        <div
                            class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm.01 1.67c2.2 0 4.26.86 5.82 2.42a8.19 8.19 0 012.41 5.82c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.19 8.19 0 01-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24zm4.52 11.64c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.4-1.75-.15-.25-.02-.39.11-.51.11-.11.25-.29.38-.44.13-.14.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.34-.76-1.84-.2-.49-.4-.42-.56-.43h-.47c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.78 2.72 4.31 3.81.6.26 1.07.42 1.44.53.61.19 1.16.16 1.6.1.49-.07 1.47-.6 1.68-1.18.21-.59.21-1.09.15-1.18-.06-.1-.23-.16-.48-.28z" />
                            </svg>
                        </div>
                        <span
                            class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2.5 py-1 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Online (08.00–21.00 WIB)
                        </span>
                    </div>

                    <h3 class="text-base font-bold text-ink-heading mb-1">WhatsApp CS & Konsultasi</h3>
                    <p class="text-muted text-xs leading-relaxed mb-4">
                        Jalur tercepat untuk respon langsung dari tim operator. Cocok untuk konfirmasi kuota H-X atau
                        request rombongan. Rata-rata respon &lt; 15 menit.
                    </p>

                    <a href="https://wa.me/6281234567890?text=Halo%20MiddleTrip%2C%20saya%20ingin%20bertanya%20seputar%20trip%20pendakian"
                        target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center justify-between w-full px-4 py-2.5 bg-emerald-50/70 hover:bg-emerald-100 text-emerald-900 border border-emerald-200/80 rounded-2xl font-bold text-xs transition-colors">
                        <span class="flex items-center gap-2">
                            <span>+62 812-3456-7890</span>
                        </span>
                        <span class="text-emerald-700 group-hover:translate-x-0.5 transition-transform">Buka WhatsApp →</span>
                    </a>
                </div>

                <!-- 2. Email Official Card -->
                <div
                    class="bg-surface-card border border-hairline hover:border-primary/40 rounded-3xl p-6 shadow-sm transition-all duration-300 group">
                    <div class="flex items-center gap-3 mb-3.5">
                        <div
                            class="w-10 h-10 rounded-2xl bg-primary-subtle text-primary flex items-center justify-center shrink-0 border border-primary/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-ink-heading">Email Resmi</h3>
                            <span class="text-[11px] text-muted font-medium">Administrasi & Kerjasama</span>
                        </div>
                    </div>

                    <p class="text-muted text-xs leading-relaxed mb-4">
                        Untuk penawaran kemitraan operator lokal, invoice perusahaan, perizinan institusi, atau
                        pengajuan sponsorship.
                    </p>

                    <a href="mailto:halo@middletrip.id"
                        class="inline-flex items-center justify-between w-full px-4 py-2.5 bg-canvas-alt hover:bg-gray-100 text-body-strong border border-hairline rounded-2xl font-bold text-xs transition-colors">
                        <span>halo@middletrip.id</span>
                        <span class="text-primary group-hover:translate-x-0.5 transition-transform">Kirim Email →</span>
                    </a>
                </div>

                <!-- 3. Forest Dark Emergency Notice (SAR & In-Trip Support) -->
                <div
                    class="bg-surface-forest border border-surface-forest-border rounded-3xl p-6 text-white shadow-xl relative overflow-hidden topo-pattern">
                    <!-- Subtle Glow Accent -->
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-primary/20 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span
                            class="inline-flex items-center gap-1.5 text-[10.5px] font-bold text-rose-300 bg-rose-950/80 border border-rose-800/60 px-2.5 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Hotline Lapangan 24 Jam
                        </span>
                        <svg class="w-4 h-4 text-emerald-400 opacity-80" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.2">
                            <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                        </svg>
                    </div>

                    <h4 class="text-sm font-extrabold text-white mb-1.5 tracking-tight">
                        Khusus Peserta yang Sedang di Jalur
                    </h4>

                    <p class="text-on-dark-soft text-xs leading-relaxed mb-4">
                        Bagi keluarga atau peserta trip aktif yang membutuhkan koordinasi darurat cuaca puncak, logistik,
                        maupun evakuasi medis lapangan, tim koordinator kami siaga 24 jam nonstop.
                    </p>

                    <div
                        class="flex items-center justify-between p-3 rounded-2xl bg-surface-forest-card border border-surface-forest-border text-xs font-mono">
                        <span class="text-muted-soft">Emergency Hotline:</span>
                        <span class="text-rose-300 font-bold tracking-wider">+62 811-9988-7766</span>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Formulir Interaktif Langsung WhatsApp (7 cols) -->
            <div class="lg:col-span-7">
                <div class="bg-surface-card border border-hairline rounded-3xl p-6 sm:p-8 shadow-sm">

                    <!-- Header Form -->
                    <div class="flex items-center gap-3 mb-6 pb-5 border-b border-hairline/80">
                        <div
                            class="w-11 h-11 rounded-2xl bg-primary-subtle text-primary flex items-center justify-center shrink-0 border border-primary/20">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-ink-heading">Formulir Kirim Pesan</h2>
                            <p class="text-muted text-xs">Pesan Anda akan otomatis terformat rapi dan terhubung ke WhatsApp CS kami.</p>
                        </div>
                    </div>

                    <!-- Interactive Form -->
                    <form id="contact-form" onsubmit="handleContactSubmit(event)" class="space-y-4 sm:space-y-5">

                        <!-- 1. Nama Lengkap -->
                        <div class="space-y-1.5">
                            <label for="contact-name" class="block text-xs font-bold text-ink-heading">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="contact-name" name="name" required
                                placeholder="Contoh: Pratama Wijaya"
                                class="w-full bg-canvas/60 border border-hairline hover:border-gray-300 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-ink-heading placeholder:text-muted-soft focus:outline-none transition shadow-2xs" />
                        </div>

                        <!-- 2. Nomor WhatsApp -->
                        <div class="space-y-1.5">
                            <label for="contact-phone" class="block text-xs font-bold text-ink-heading">
                                Nomor WhatsApp Aktif <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-xs text-muted font-bold pointer-events-none">
                                    +62
                                </span>
                                <input type="tel" id="contact-phone" name="phone" required
                                    placeholder="81234567890"
                                    class="w-full bg-canvas/60 border border-hairline hover:border-gray-300 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl pl-12 pr-3.5 py-2.5 text-xs sm:text-sm text-ink-heading placeholder:text-muted-soft focus:outline-none transition shadow-2xs" />
                            </div>
                            <p class="text-[10.5px] text-muted-soft">Nomor Anda digunakan untuk follow up konfirmasi.</p>
                        </div>

                        <!-- 3. Topik Pertanyaan -->
                        <div class="space-y-1.5">
                            <label for="contact-topic" class="block text-xs font-bold text-ink-heading">
                                Topik Kebutuhan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="contact-topic" name="topic" required
                                    class="w-full bg-canvas/60 border border-hairline hover:border-gray-300 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-ink-heading focus:outline-none transition shadow-2xs appearance-none cursor-pointer">
                                    <option value="Konsultasi Custom Private Trip">Konsultasi Custom Private Trip (Rombongan)</option>
                                    <option value="Informasi Jadwal & Kuota Open Trip">Informasi Jadwal & Kuota Open Trip</option>
                                    <option value="Status Pembayaran & Price Lock">Status Pembayaran & Price Lock</option>
                                    <option value="Perizinan SIMAKSI & Asuransi">Perizinan SIMAKSI & Asuransi</option>
                                    <option value="Sewa Alat Outdoor & Logistik">Sewa Alat Outdoor & Logistik</option>
                                    <option value="Kerjasama Operator & Komunitas">Kerjasama Operator & Komunitas</option>
                                    <option value="Pertanyaan Lainnya">Pertanyaan Lainnya</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-muted">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Isi Pesan -->
                        <div class="space-y-1.5">
                            <label for="contact-message" class="block text-xs font-bold text-ink-heading">
                                Pesan / Detail Pertanyaan <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="contact-message" name="message" rows="4" required
                                placeholder="Ceritakan detail pertanyaan atau rencana ekspedisi Anda (misal: rencana mendaki Mt. Merbabu bersama 5 orang pada akhir bulan, butuh shuttle Solo)..."
                                class="w-full bg-canvas/60 border border-hairline hover:border-gray-300 focus:border-primary focus:ring-1 focus:ring-primary rounded-2xl p-3.5 text-xs sm:text-sm text-ink-heading placeholder:text-muted-soft focus:outline-none transition shadow-2xs leading-relaxed"></textarea>
                        </div>

                        <!-- 5. Submit CTA Button -->
                        <div class="pt-2">
                            <button type="submit" id="btn-submit-contact"
                                class="w-full bg-primary hover:bg-primary-hover active:bg-primary-active active:scale-[0.99] text-white font-bold py-3.5 px-6 rounded-full shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2.5 cursor-pointer text-sm">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm.01 1.67c2.2 0 4.26.86 5.82 2.42a8.19 8.19 0 012.41 5.82c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.19 8.19 0 01-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24zm4.52 11.64c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.4-1.75-.15-.25-.02-.39.11-.51.11-.11.25-.29.38-.44.13-.14.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.34-.76-1.84-.2-.49-.4-.42-.56-.43h-.47c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.78 2.72 4.31 3.81.6.26 1.07.42 1.44.53.61.19 1.16.16 1.6.1.49-.07 1.47-.6 1.68-1.18.21-.59.21-1.09.15-1.18-.06-.1-.23-.16-.48-.28z" />
                                </svg>
                                <span>Kirim via WhatsApp Sekarang</span>
                                <span>→</span>
                            </button>
                        </div>

                        <!-- Footer Trust Assurance -->
                        <div class="flex items-center justify-center gap-1.5 text-[11px] text-muted text-center pt-2">
                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Pesan langsung terkirim ke WhatsApp resmi operasional MiddleTrip tanpa perantara.</span>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </main>

    <!-- Footer Component -->
    <x-footer />

    <!-- Floating Contact Action Button (FAB) -->
    <x-fab-contact />

    <!-- WhatsApp Redirect Form Script -->
    <script>
        function handleContactSubmit(event) {
            event.preventDefault();

            const name = document.getElementById('contact-name').value.trim();
            let phone = document.getElementById('contact-phone').value.trim();
            const topic = document.getElementById('contact-topic').value;
            const message = document.getElementById('contact-message').value.trim();

            if (!name || !message) {
                alert('Silakan lengkapi nama dan pesan Anda terlebih dahulu.');
                return;
            }

            // Normalisasi nomor telepon
            if (phone.startsWith('0')) {
                phone = phone.substring(1);
            }
            if (phone.startsWith('+62')) {
                phone = phone.substring(3);
            } else if (phone.startsWith('62')) {
                phone = phone.substring(2);
            }

            // Format Pesan WhatsApp yang Rapi & Sopan
            const waText = 
`Halo MiddleTrip Expedition! 👋
Saya ingin berkonsultasi seputar ekspedisi:

👤 *Nama:* ${name}
📱 *No. WhatsApp:* +62${phone}
📌 *Topik:* ${topic}

💬 *Pesan / Kebutuhan:*
${message}

Mohon informasinya ya min, terima kasih! 🏔️`;

            const adminWhatsAppNumber = '6281234567890';
            const encodedUrl = `https://wa.me/${adminWhatsAppNumber}?text=${encodeURIComponent(waText)}`;

            // Buka WhatsApp di tab baru
            window.open(encodedUrl, '_blank');
        }
    </script>

</body>

</html>
