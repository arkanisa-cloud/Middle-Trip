<x-public-layout 
    title="Syarat & Ketentuan (Terms of Service) - MiddleTrip" 
    description="Syarat dan ketentuan resmi ekspedisi pendakian gunung, pemesanan open trip, private trip, kebijakan pembatalan, dan keselamatan di MiddleTrip."
    active="terms"
    :hero="false"
>

    <!-- Main Content Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 sm:pt-24 md:pt-28 pb-16 sm:pb-20 w-full flex-1 font-sans">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs font-medium text-muted mb-4 overflow-x-auto whitespace-nowrap no-scrollbar"
            aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="text-muted-soft">&gt;</span>
            <span class="text-body-strong font-semibold">Syarat & Ketentuan</span>
        </nav>

        <!-- Page Header -->
        <header class="mb-8 sm:mb-12 border-b border-hairline pb-6 sm:pb-8">
            <div
                class="inline-flex items-center gap-2 bg-primary-subtle text-primary border border-primary/20 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Perjanjian Layanan & Regulasi Ekspedisi</span>
            </div>

            <h1 class="text-2xl sm:text-4xl lg:text-4xl font-extrabold text-ink-heading tracking-tight mb-3">
                Syarat & Ketentuan MiddleTrip
            </h1>

            <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-xs text-muted">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-muted-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Terakhir diperbarui: <strong>5 Oktober 2026</strong></span>
                </span>
                <span class="hidden sm:inline text-muted-soft">•</span>
                <span>Standar Operasional Prosedur & Keselamatan Pendakian Gunung</span>
            </div>
        </header>

        <!-- Content Sections -->
        <div class="space-y-8 text-xs sm:text-sm text-slate-700 leading-relaxed">

            <!-- Section 1 -->
            <section class="bg-white rounded-2xl border border-hairline p-5 sm:p-7 shadow-2xs space-y-3">
                <div class="flex items-center gap-2.5 text-primary font-bold text-sm sm:text-base border-b border-hairline pb-2.5">
                    <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xs font-extrabold">1</span>
                    <h2 class="text-ink-heading">Ketentuan Umum Akun & Registrasi</h2>
                </div>
                <p>
                    Dengan mendaftar, mengakses, atau memesan ekspedisi di <strong>MiddleTrip</strong> (baik menggunakan email maupun otentikasi Google OAuth), Anda menyatakan bahwa:
                </p>
                <ul class="list-disc list-inside space-y-1.5 pl-1 text-slate-600">
                    <li>Berusia minimal 17 tahun atau didampingi oleh orang tua/wali yang sah untuk peserta di bawah umur.</li>
                    <li>Memberikan informasi identitas yang akurat, sah, dan sesuai dengan dokumen kependudukan resmi (KTP/Paspor).</li>
                    <li>Bertanggung jawab penuh atas kerahasiaan dan aktivitas akun Anda.</li>
                </ul>
            </section>

            <!-- Section 2 -->
            <section class="bg-white rounded-2xl border border-hairline p-5 sm:p-7 shadow-2xs space-y-3">
                <div class="flex items-center gap-2.5 text-primary font-bold text-sm sm:text-base border-b border-hairline pb-2.5">
                    <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xs font-extrabold">2</span>
                    <h2 class="text-ink-heading">Pemesanan, Uang Muka (DP), & Pelunasan</h2>
                </div>
                <ul class="list-disc list-inside space-y-2 pl-1 text-slate-600">
                    <li><strong>Open Trip:</strong> Reservasi slot kuota diwajibkan membayar uang muka (DP Booking Fee). Harga final dihitung saat fase <em>Price Lock</em> berdasarkan total peserta yang terkumpul.</li>
                    <li><strong>Private Trip:</strong> Pembayaran dilakukan penuh (100%) untuk menjamin reservasi pemandu, perizinan khusus rombongan, dan logistik eksklusif.</li>
                    <li><strong>Ketentuan Non-Refundable:</strong> Uang muka (DP Booking Fee) yang telah dibayarkan bersifat <em>non-refundable</em> (hangus) apabila pembatalan dilakukan sepihak oleh pendaki.</li>
                </ul>
            </section>

            <!-- Section 3 -->
            <section class="bg-white rounded-2xl border border-hairline p-5 sm:p-7 shadow-2xs space-y-3">
                <div class="flex items-center gap-2.5 text-primary font-bold text-sm sm:text-base border-b border-hairline pb-2.5">
                    <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xs font-extrabold">3</span>
                    <h2 class="text-ink-heading">Kesehatan, Fisik, & Tanggung Jawab Pribadi</h2>
                </div>
                <p>
                    Aktivitas pendakian gunung merupakan olahraga petualangan alam terbuka yang memiliki risiko objektif:
                </p>
                <ul class="list-disc list-inside space-y-1.5 pl-1 text-slate-600">
                    <li>Setiap pendaki wajib dalam kondisi fisik yang sehat dan tidak memiliki riwayat penyakit kritis yang membahayakan di ketinggian (misal: penyakit jantung koroner berat, asma akut, epilepsi) tanpa persetujuan dokter.</li>
                    <li>Wajib menaati arahan <em>Leader Guide</em> MiddleTrip dan mematuhi etika konservasi alam <em>Leave No Trace</em> (tidak membuang sampah sembarangan dan tidak merusak flora/fauna).</li>
                </ul>
            </section>

            <!-- Section 4 -->
            <section class="bg-white rounded-2xl border border-hairline p-5 sm:p-7 shadow-2xs space-y-3">
                <div class="flex items-center gap-2.5 text-primary font-bold text-sm sm:text-base border-b border-hairline pb-2.5">
                    <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xs font-extrabold">4</span>
                    <h2 class="text-ink-heading">Force Majeure (Keadaan Memaksa)</h2>
                </div>
                <p class="text-slate-600">
                    Apabila terjadi penutupan jalur oleh Balai Taman Nasional akibat cuaca ekstrem, kebakaran hutan, bencana alam vulkanik, atau instruksi resmi pemerintah, MiddleTrip akan mengupayakan penjadwalan ulang (<em>reschedule</em>) atau konversi deposit sesuai kebijakan musyawarah mufakat demi keselamatan jiwa seluruh peserta.
                </p>
            </section>

        </div>

    </main>

</x-public-layout>
