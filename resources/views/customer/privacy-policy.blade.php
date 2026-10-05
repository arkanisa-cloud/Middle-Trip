<x-public-layout 
    title="Kebijakan Privasi (Privacy Policy) - MiddleTrip" 
    description="Kebijakan privasi resmi MiddleTrip mengenai pengumpulan data, penggunaan Google OAuth, keamanan informasi manifes pendaki, dan perlindungan data pribadi."
    active="privasi"
    :hero="false"
>

    <!-- Main Content Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 sm:pt-24 md:pt-28 pb-16 sm:pb-20 w-full flex-1 font-sans">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs font-medium text-muted mb-4 overflow-x-auto whitespace-nowrap no-scrollbar"
            aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="text-muted-soft">&gt;</span>
            <span class="text-body-strong font-semibold">Kebijakan Privasi</span>
        </nav>

        <!-- Page Header -->
        <header class="mb-8 sm:mb-12 border-b border-hairline pb-6 sm:pb-8">
            <div
                class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200/80 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Standar Perlindungan Data Pribadi</span>
            </div>

            <h1 class="text-2xl sm:text-4xl lg:text-4xl font-extrabold text-ink-heading tracking-tight mb-3">
                Kebijakan Privasi MiddleTrip
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
                <span>Kepatuhan UU Pelindungan Data Pribadi (UU PDP No. 27/2022)</span>
            </div>
        </header>

        <!-- Google OAuth Transparency & Limited Use Banner Card -->
        <div class="mb-10 bg-surface-card border border-emerald-200/90 rounded-2xl p-5 sm:p-6 shadow-xs relative overflow-hidden space-y-4">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-100/50 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex items-start gap-3.5 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 shadow-2xs flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#EA4335"
                            d="M12 5c1.54 0 2.94.55 4.04 1.46l3.03-3.03C17.24 1.8 14.81 1 12 1 7.37 1 3.42 3.63 1.5 7.42l3.66 2.84C6.04 7.26 8.77 5 12 5z" />
                        <path fill="#4285F4"
                            d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58l3.66 2.84c2.14-1.98 3.76-4.9 3.76-8.66z" />
                        <path fill="#FBBC05"
                            d="M5.16 14.74c-.23-.69-.36-1.42-.36-2.18s.13-1.49.36-2.18L1.5 7.42C.54 9.32 0 11.44 0 12s.54 2.68 1.5 4.58l3.66-2.84z" />
                        <path fill="#34A853"
                            d="M12 23c3.24 0 5.95-1.08 7.93-2.91l-3.66-2.84c-1.07.72-2.45 1.16-4.27 1.16-3.23 0-5.96-2.26-6.84-5.26L1.5 16.58C3.42 20.37 7.37 23 12 23z" />
                    </svg>
                </div>
                <div class="space-y-1.5 text-xs">
                    <h2 class="text-sm font-bold text-ink-heading">Transparansi Penggunaan Google OAuth & Kepatuhan Google API</h2>
                    <p class="text-slate-600 leading-relaxed">
                        MiddleTrip menyediakan fitur <strong>"Lanjutkan dengan Google" (Google OAuth 2.0)</strong> untuk mempermudah pendaftaran dan proses masuk (login) akun pendaki. Kami <strong>hanya meminta izin akses profil dasar</strong>: nama lengkap, alamat email, Google ID unik, dan foto profil publik Anda.
                    </p>
                    <p class="text-slate-600 leading-relaxed">
                        Kami <strong>TIDAK PERNAH</strong> meminta atau mengakses kata sandi akun Google, kontak, isi pesan Gmail, file Google Drive, maupun data sensitif lainnya.
                    </p>
                </div>
            </div>

            <!-- Google Limited Use Policy Compliance Statement (Required for Google OAuth Verification) -->
            <div class="relative z-10 p-3.5 bg-emerald-50/80 border border-emerald-200 rounded-xl text-[11.5px] text-emerald-950 leading-relaxed space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-emerald-900">
                    <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Pernyataan Kepatuhan Kebijakan Data Pengguna Google API (Limited Use):</span>
                </div>
                <p>
                    Penggunaan dan transfer informasi yang diterima MiddleTrip dari Google APIs ke aplikasi lain akan sepenuhnya mematuhi <a href="https://developers.google.com/terms/api-services-user-data-policy" target="_blank" rel="noopener noreferrer" class="font-bold text-emerald-800 underline hover:text-emerald-950">Google API Services User Data Policy</a>, termasuk persyaratan <strong>Limited Use</strong>. Data pengguna dari Google tidak akan pernah dialihkan ke pihak ketiga selain untuk keperluan penyediaan layanan inti ekspedisi, tidak digunakan untuk penayangan iklan, dan tidak digunakan untuk melatih model kecerdasan buatan (AI/ML).
                </p>
            </div>
        </div>

        <!-- Privacy Content Sections -->
        <div class="space-y-8 text-xs sm:text-sm text-slate-700 leading-relaxed">

            <!-- Section 1 -->
            <section id="pengantar" class="bg-white rounded-2xl border border-hairline p-5 sm:p-7 shadow-2xs space-y-3">
                <div class="flex items-center gap-2.5 text-primary font-bold text-sm sm:text-base border-b border-hairline pb-2.5">
                    <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xs font-extrabold">1</span>
                    <h2 class="text-ink-heading">Pendahuluan & Komitmen Kami</h2>
                </div>
                <p>
                    Selamat datang di <strong>MiddleTrip</strong> (<a href="{{ route('home') }}" class="text-primary hover:underline font-medium">middletrip.id</a>). MiddleTrip merupakan platform digital ekspedisi pendakian gunung di Indonesia yang memfasilitasi reservasi open trip, private trip, perizinan SIMAKSI, serta pemanduan profesional.
                </p>
                <p>
                    Kami berkomitmen penuh untuk melindungi privasi dan keamanan data pribadi setiap pendaki, pelanggan, dan pengunjung website kami. Kebijakan Privasi ini menjelaskan jenis data yang kami kumpulkan, alasan pengumpulan, cara penyimpanan, serta hak-hak Anda atas data tersebut sesuai dengan <strong>Undang-Undang Republik Indonesia Nomor 27 Tahun 2022 tentang Pelindungan Data Pribadi (UU PDP)</strong>.
                </p>
            </section>

            <!-- Section 2 -->
            <section id="data-dikumpulkan" class="bg-white rounded-2xl border border-hairline p-5 sm:p-7 shadow-2xs space-y-4">
                <div class="flex items-center gap-2.5 text-primary font-bold text-sm sm:text-base border-b border-hairline pb-2.5">
                    <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xs font-extrabold">2</span>
                    <h2 class="text-ink-heading">Data Pribadi yang Kami Kumpulkan</h2>
                </div>
                
                <p>
                    Kami mengumpulkan beberapa kategori data saat Anda menggunakan layanan MiddleTrip:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                    <!-- Data 1: Google OAuth -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-ink-heading text-xs sm:text-sm">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span>A. Data Otentikasi Akun (Google OAuth)</span>
                        </div>
                        <p class="text-slate-600 text-xs leading-relaxed">
                            Saat Anda masuk atau mendaftar menggunakan Akun Google, kami menerima data profil dasar:
                        </p>
                        <ul class="list-disc list-inside text-xs text-slate-600 space-y-1 pl-1">
                            <li><strong>Nama Lengkap:</strong> Untuk menyapa dan mencocokkan akun Anda.</li>
                            <li><strong>Alamat Email:</strong> Sebagai identitas akun unik & tujuan pengiriman e-tiket.</li>
                            <li><strong>Google ID Unik:</strong> Kunci pengenal aman untuk login tanpa kata sandi.</li>
                            <li><strong>Foto Profil (Avatar):</strong> Ditampilkan pada header navigasi akun Anda.</li>
                        </ul>
                    </div>

                    <!-- Data 2: Data Manifes Pendakian -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-ink-heading text-xs sm:text-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>B. Data Manifes Peserta Pendakian</span>
                        </div>
                        <p class="text-slate-600 text-xs leading-relaxed">
                            Saat memesan tiket open/private trip, Anda wajib mengisi data manifes:
                        </p>
                        <ul class="list-disc list-inside text-xs text-slate-600 space-y-1 pl-1">
                            <li><strong>Nama Lengkap (sesuai KTP):</strong> Untuk verifikasi registrasi basecamp.</li>
                            <li><strong>NIK (16 Digit):</strong> Wajib untuk perizinan SIMAKSI & asuransi jiwa resmi.</li>
                            <li><strong>Nomor WhatsApp/Telepon:</strong> Untuk koordinasi briefing dan logistik trip.</li>
                            <li><strong>Kontak Darurat:</strong> Keluarga yang dapat dihubungi saat situasi darurat.</li>
                        </ul>
                    </div>

                    <!-- Data 3: Data Pembayaran -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-ink-heading text-xs sm:text-sm">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span>C. Data Transaksi & Pembayaran</span>
                        </div>
                        <p class="text-slate-600 text-xs leading-relaxed">
                            Informasi kode pemesanan, nominal tagihan, dan metode pembayaran yang Anda pilih. Seluruh pemrosesan kartu kredit/transfer diproses langsung oleh payment gateway resmi <strong>Midtrans</strong> (berlisensi Bank Indonesia & bersertifikasi PCI-DSS). MiddleTrip tidak menyimpan nomor kartu atau CVV Anda.
                        </p>
                    </div>

                    <!-- Data 4: Data Teknis & Log -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-ink-heading text-xs sm:text-sm">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            <span>D. Data Teknis & Cookies</span>
                        </div>
                        <p class="text-slate-600 text-xs leading-relaxed">
                            Alamat protokol internet (IP), tipe peramban web, preferensi rute, dan cookie sesi yang diperlukan untuk menjaga sesi login Anda tetap aktif dan aman selama bernavigasi.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Section 3 -->
            <section id="tujuan-penggunaan" class="bg-white rounded-2xl border border-hairline p-5 sm:p-7 shadow-2xs space-y-3">
                <div class="flex items-center gap-2.5 text-primary font-bold text-sm sm:text-base border-b border-hairline pb-2.5">
                    <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xs font-extrabold">3</span>
                    <h2 class="text-ink-heading">Tujuan Penggunaan Data Pribadi</h2>
                </div>
                <p>
                    Kami hanya memproses data pribadi Anda untuk keperluan yang sah dan transparan, antara lain:
                </p>
                <div class="space-y-2.5 pt-1">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">✓</div>
                        <p><strong>Memproses Pemesanan & Pembayaran:</strong> Mengonfirmasi slot kuota ekspedisi, menerbitkan kode booking, dan memverifikasi pelunasan tiket.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">✓</div>
                        <p><strong>Perizinan SIMAKSI & Asuransi Resmi:</strong> Mendaftarkan data identitas manifes ke Balai Taman Nasional terkait dan mengaktifkan polis asuransi pendakian resmi.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">✓</div>
                        <p><strong>Komunikasi & Koordinasi Lapangan:</strong> Mengirimkan instruksi meeting point, panduan logistik pendakian, serta memasukkan Anda ke grup WhatsApp rombongan.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">✓</div>
                        <p><strong>Keamanan & Tanggap Darurat:</strong> Memastikan tim pemandu dan tim SAR memiliki data akurat apabila terjadi situasi evakuasi atau pertolongan pertama di gunung.</p>
                    </div>
                </div>
            </section>

            <!-- Section 4 -->
            <section id="pembagian-data" class="bg-white rounded-2xl border border-hairline p-5 sm:p-7 shadow-2xs space-y-3">
                <div class="flex items-center gap-2.5 text-primary font-bold text-sm sm:text-base border-b border-hairline pb-2.5">
                    <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xs font-extrabold">4</span>
                    <h2 class="text-ink-heading">Berbagi Data dengan Pihak Ketiga</h2>
                </div>
                <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-900 font-medium text-xs leading-relaxed">
                    <strong>Jaminan Privasi:</strong> MiddleTrip <strong>TIDAK PERNAH</strong> menjual, menyewakan, meminjamkan, atau memperdagangkan data pribadi Anda kepada pengiklan atau pihak ketiga mana pun untuk kepentingan komersial mereka.
                </div>
                <p>
                    Data pribadi Anda hanya diteruskan kepada pihak-pihak resmi yang terlibat langsung dalam penyelenggaraan ekspedisi:
                </p>
                <ol class="list-decimal list-inside space-y-2 pl-1 text-slate-600">
                    <li><strong>Balai Taman Nasional & Pengelola Jalur:</strong> Untuk penerbitan Surat Izin Masuk Kawasan Konservasi (SIMAKSI) yang sah menurut hukum kehutanan RI.</li>
                    <li><strong>Perusahaan Asuransi Resmi:</strong> Untuk pendaftaran pertanggungan asuransi jiwa dan kecelakaan selama kegiatan pendakian berlangsung.</li>
                    <li><strong>Payment Gateway Midtrans:</strong> Untuk verifikasi dan penyelesaian transaksi pembayaran yang aman.</li>
                    <li><strong>Penyedia Otentikasi Google:</strong> Untuk keperluan login aman melalui protokol Google OAuth.</li>
                </ol>
            </section>

            <!-- Section 5 -->
            <section id="keamanan-data" class="bg-white rounded-2xl border border-hairline p-5 sm:p-7 shadow-2xs space-y-3">
                <div class="flex items-center gap-2.5 text-primary font-bold text-sm sm:text-base border-b border-hairline pb-2.5">
                    <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xs font-extrabold">5</span>
                    <h2 class="text-ink-heading">Keamanan & Penyimpanan Data</h2>
                </div>
                <p>
                    Kami menerapkan standar keamanan teknis dan organisasional yang ketat untuk mencegah akses, pengubahan, pengungkapan, atau perusakan data yang tidak sah:
                </p>
                <ul class="list-disc list-inside space-y-1.5 pl-1 text-slate-600">
                    <li>Seluruh pertukaran data antara browser Anda dan server kami dienkripsi menggunakan protokol <strong>HTTPS / TLS (Transport Layer Security)</strong>.</li>
                    <li>Kata sandi pengguna (jika mendaftar via email) di-hash menggunakan algoritma <strong>Bcrypt</strong> yang tidak dapat dibaca kembali.</li>
                    <li>Akses ke basis data manifes dibatasi secara ketat hanya untuk staf operasional yang berwenang.</li>
                </ul>
            </section>

            <!-- Section 6 -->
            <section id="hak-pengguna" class="bg-white rounded-2xl border border-hairline p-5 sm:p-7 shadow-2xs space-y-3">
                <div class="flex items-center gap-2.5 text-primary font-bold text-sm sm:text-base border-b border-hairline pb-2.5">
                    <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xs font-extrabold">6</span>
                    <h2 class="text-ink-heading">Hak Anda sebagai Pemilik Data</h2>
                </div>
                <p>
                    Sesuai dengan ketentuan perlindungan data pribadi, Anda memiliki hak-hak berikut:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <h4 class="font-bold text-ink-heading text-xs mb-1">Hak Akses & Pembaruan</h4>
                        <p class="text-xs text-slate-600">Anda dapat melihat dan memperbarui profil Anda kapan saja melalui menu Pengaturan Akun di website MiddleTrip.</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <h4 class="font-bold text-ink-heading text-xs mb-1">Pencabutan Akses Google OAuth</h4>
                        <p class="text-xs text-slate-600">Anda dapat mencabut izin integrasi MiddleTrip sewaktu-waktu di halaman <a href="https://myaccount.google.com/permissions" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline font-semibold">Izin Akun Google Anda</a>.</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <h4 class="font-bold text-ink-heading text-xs mb-1">Hak Penghapusan Data (Right to be Forgotten)</h4>
                        <p class="text-xs text-slate-600">Anda berhak meminta penonaktifan akun dan penghapusan data riwayat Anda dengan menghubungi tim dukungan kami.</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <h4 class="font-bold text-ink-heading text-xs mb-1">Hak Penarikan Persetujuan</h4>
                        <p class="text-xs text-slate-600">Anda dapat membatalkan persetujuan penggunaan data kontak untuk buletin atau penawaran promosi trip.</p>
                    </div>
                </div>
            </section>

            <!-- Section 7 -->
            <section id="kontak-privasi" class="bg-surface-forest text-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-md space-y-4">
                <div class="flex items-center gap-2.5 font-bold text-base border-b border-surface-forest-border pb-3">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h2 class="text-white">Kontak Petugas Privasi & Bantuan</h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-200 leading-relaxed">
                    Apabila Anda memiliki pertanyaan, klarifikasi, atau permohonan terkait pengelolaan data pribadi Anda di MiddleTrip, silakan hubungi saluran resmi kami:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-1">
                    <div class="p-3.5 rounded-xl bg-white/10 border border-white/15 space-y-1">
                        <span class="text-[10px] uppercase tracking-wider text-emerald-300 font-bold block">Email Resmi Privasi</span>
                        <a href="mailto:privacy@middletrip.com" class="text-white font-semibold hover:text-emerald-300 transition">privacy@middletrip.com</a>
                        <p class="text-[11px] text-slate-300">Respons dalam 1x24 jam kerja</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white/10 border border-white/15 space-y-1">
                        <span class="text-[10px] uppercase tracking-wider text-emerald-300 font-bold block">WhatsApp Operasional</span>
                        <a href="https://wa.me/6285725780424" target="_blank" rel="noopener noreferrer" class="text-white font-semibold hover:text-emerald-300 transition">+62 857-2578-0424</a>
                        <p class="text-[11px] text-slate-300">Setiap hari (08.00–21.00 WIB)</p>
                    </div>
                </div>
            </section>

        </div>

    </main>

</x-public-layout>
