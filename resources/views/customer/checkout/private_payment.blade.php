<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Private Trip - MiddleTrip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col justify-between bg-canvas text-ink antialiased selection:bg-primary selection:text-white font-sans" x-data="{ expandedAccordion: null, selectedPayment: 'BCA Virtual Account' }">

    <!-- Header -->
    <header class="w-full bg-white border-b border-hairline px-4 sm:px-8 py-3.5">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-1.5 text-ink-heading font-extrabold text-lg tracking-tight">
                <svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m8 3 4 8 5-5 5 15H2L8 3z"/>
                </svg>
                <span>Middle<span class="text-primary">Trip</span></span>
            </a>

            <div class="flex items-center gap-1.5 text-xs text-muted font-medium">
                <svg class="w-3.5 h-3.5 text-muted-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Pembayaran Aman &amp; Terenkripsi</span>
            </div>
        </div>
    </header>

    <main class="w-full max-w-6xl mx-auto px-4 sm:px-8 py-6 flex-1">
        <div class="mb-4">
            <a href="{{ route('ekspedisi.show', $booking->expedition->mountain->slug) }}" class="inline-flex items-center text-xs text-muted hover:text-ink transition">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Detail Expedition
            </a>
        </div>

        <!-- Stepper (Private Trip: Data -> Bayar Langsung 100% -> Sukses) -->
        <div class="max-w-md mx-auto mb-8">
            <div class="flex items-center justify-center">
                <!-- Step 1: DATA (Done) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-[9px] uppercase tracking-wider text-emerald-600 font-bold mt-1">Data</span>
                </div>

                <div class="w-16 sm:w-20 h-[2px] bg-primary -mt-3.5"></div>

                <!-- Step 2: PEMBAYARAN LANGSUNG 100% (Active) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full bg-primary ring-2 ring-primary/20 flex items-center justify-center text-white">
                        <span class="text-[10px] font-bold">2</span>
                    </div>
                    <span class="text-[9px] uppercase tracking-wider text-primary font-bold mt-1">Bayar 100%</span>
                </div>

                <div class="w-16 sm:w-20 h-[2px] border-t-2 border-dotted border-gray-300 -mt-3.5"></div>

                <!-- Step 3: SELESAI -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full bg-neutral-200 flex items-center justify-center text-neutral-400">
                        <span class="text-[10px] font-bold">3</span>
                    </div>
                    <span class="text-[9px] uppercase tracking-wider text-neutral-400 font-bold mt-1">Selesai</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">

            <!-- Left Column (7 cols) -->
            <div class="lg:col-span-7 space-y-4">

                <!-- Card: Banner Private Trip -->
                <div class="relative overflow-hidden bg-white rounded-2xl border border-hairline shadow-sm">
                    <div class="relative bg-gradient-to-b from-primary-50/60 via-surface-card to-white p-5 sm:p-6 pb-5">
                        <svg class="absolute right-0 bottom-0 w-72 h-36 opacity-10 pointer-events-none text-primary" viewBox="0 0 400 200" fill="currentColor">
                            <polygon points="50,200 170,60 220,130 300,30 420,200" />
                        </svg>

                        <div class="mb-2 flex items-center gap-2">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-primary text-white tracking-wide uppercase">
                                PRIVATE TRIP
                            </span>
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                Tanpa DP • Langsung Lunas
                            </span>
                            <span class="text-[11px] font-mono font-medium text-muted">#{{ $booking->booking_code }}</span>
                        </div>

                        <h1 class="text-xl sm:text-2xl font-extrabold text-ink-heading tracking-tight">
                            {{ $booking->expedition->mountain->name }}
                        </h1>
                        <p class="text-xs text-muted font-medium mt-0.5 mb-4">
                            {{ $booking->expedition->mountain->duration_days }}D{{ $booking->expedition->mountain->duration_nights }}N • Keberangkatan: {{ \Carbon\Carbon::parse($booking->expedition->departure_date)->translatedFormat('d F Y') }}
                        </p>

                        <div class="pt-3 border-t border-hairline">
                            <div class="flex items-center gap-2 mb-1.5">
                                <div class="w-4 h-4 rounded-full bg-primary flex items-center justify-center text-white shrink-0">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-ink-heading">Pembayaran Private Trip (Full Payment)</span>
                            </div>
                            <p class="text-xs text-muted leading-relaxed">
                                Rombongan ini bersifat privat dan tertutup khusus kelompok Anda ({{ $booking->pax_count }} pax). Tanpa sistem DP, pembayaran langsung 100% penuh untuk pengamanan tanggal dan pemandu resmi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card: Informasi Trip -->
                <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="text-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-ink-heading">Informasi Trip</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-y-3.5 text-xs">
                        <div>
                            <p class="text-muted text-[11px] font-medium">Destinasi</p>
                            <p class="text-ink font-semibold mt-0.5">{{ $booking->expedition->mountain->name }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[11px] font-medium">Rute</p>
                            <p class="text-ink font-semibold mt-0.5">Via {{ $booking->route->name ?? 'Standar' }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[11px] font-medium">Meeting Point</p>
                            <p class="text-ink font-semibold mt-0.5">{{ $booking->meetingPoint->name ?? 'Basecamp Resmi' }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[11px] font-medium">Keberangkatan</p>
                            <p class="text-ink font-semibold mt-0.5">{{ \Carbon\Carbon::parse($booking->expedition->departure_date)->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card: Data Pemesan (Ketua) -->
                <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="text-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-ink-heading">Data Pemesan (Ketua Rombongan)</h2>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <span class="block text-muted text-[11px]">Nama Lengkap</span>
                                <span class="font-semibold text-ink">{{ $booking->customer_name }}</span>
                            </div>
                            <div>
                                <span class="block text-muted text-[11px]">Nomor WhatsApp</span>
                                <span class="font-semibold text-ink">{{ $booking->customer_phone }}</span>
                            </div>
                            <div>
                                <span class="block text-muted text-[11px]">Email</span>
                                <span class="font-semibold text-ink">{{ $booking->customer_email }}</span>
                            </div>
                            <div>
                                <span class="block text-muted text-[11px]">NIK</span>
                                <span class="font-semibold text-ink font-mono">{{ $booking->customer_nik }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Data Seluruh Peserta Rombongan -->
                <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-bold text-ink-heading">Daftar Anggota Rombongan ({{ $booking->pax_count }} Orang)</h2>
                        </div>
                    </div>

                    <div class="space-y-2">
                        @foreach($booking->participants as $index => $participant)
                            <div class="border border-hairline rounded-xl overflow-hidden text-xs">
                                <button type="button" @click="expandedAccordion = (expandedAccordion === {{ $index }} ? null : {{ $index }})" class="w-full flex items-center justify-between px-4 py-3 bg-neutral-50/50 hover:bg-neutral-50 text-left font-semibold text-ink transition">
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full {{ $participant->is_leader ? 'bg-primary text-white' : 'bg-neutral-200 text-neutral-600' }} flex items-center justify-center text-[10px] font-bold">
                                            {{ $index + 1 }}
                                        </span>
                                        <span>{{ $participant->name }} {{ $participant->is_leader ? '(Ketua)' : '' }}</span>
                                    </div>
                                    <svg class="w-4 h-4 text-muted transition-transform" :class="expandedAccordion === {{ $index }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="expandedAccordion === {{ $index }}" x-cloak class="p-4 bg-white border-t border-hairline space-y-2">
                                    <div>
                                        <span class="text-muted text-[11px]">NIK Resmi SIMAKSI:</span>
                                        <p class="font-mono font-medium text-ink">{{ $participant->nik }}</p>
                                    </div>
                                    @if($participant->phone)
                                        <div>
                                            <span class="text-muted text-[11px]">Telepon:</span>
                                            <p class="font-medium text-ink">{{ $participant->phone }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right Column (5 cols) -->
            <div class="lg:col-span-5">
                <div class="sticky top-6">
                    <div class="bg-white rounded-2xl border border-hairline p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-bold text-ink-heading">Pembayaran Penuh (100%)</h2>
                            <span class="text-[10px] font-bold text-primary bg-primary-50 px-2 py-0.5 rounded-full">Private Trip</span>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-2.5 text-xs pb-4 border-b border-hairline">
                            <div class="flex justify-between text-muted">
                                <span>Tiket Trip ({{ $booking->pax_count }} pax @ Rp {{ number_format($booking->locked_price_per_pax ?? ($booking->grand_total / max(1, $booking->pax_count)), 0, ',', '.') }})</span>
                                <span class="font-semibold text-ink">Rp {{ number_format(($booking->locked_price_per_pax ?? ($booking->grand_total / max(1, $booking->pax_count))) * $booking->pax_count, 0, ',', '.') }}</span>
                            </div>

                            @if(($booking->shuttle_fee_total ?? 0) > 0)
                                <div class="flex justify-between text-muted">
                                    <span>Shuttle Meeting Point</span>
                                    <span class="font-semibold text-ink">Rp {{ number_format($booking->shuttle_fee_total, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            @if(($booking->addons_fee_total ?? 0) > 0)
                                <div class="flex justify-between text-muted">
                                    <span>Sewa Alat &amp; Add-on</span>
                                    <span class="font-semibold text-ink">Rp {{ number_format($booking->addons_fee_total, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between text-sm font-bold text-ink-heading pt-2 border-t border-hairline">
                                <span>Total Pembayaran Langsung</span>
                                <span class="text-primary font-extrabold text-base">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        <form action="{{ route('checkout.pay_private', $booking->booking_code) }}" method="POST" class="mt-5 space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-ink-heading mb-2">Pilih Metode Pembayaran</label>
                                <div class="space-y-2 text-xs">
                                    <!-- BCA VA -->
                                    <label class="flex items-center justify-between p-3 rounded-xl border cursor-pointer transition" :class="selectedPayment === 'BCA Virtual Account' ? 'border-primary bg-primary-50/20 ring-1 ring-primary' : 'border-hairline hover:bg-neutral-50'">
                                        <div class="flex items-center gap-2.5">
                                            <input type="radio" name="payment_method" value="BCA Virtual Account" x-model="selectedPayment" class="text-primary focus:ring-primary">
                                            <span class="font-semibold text-ink">BCA Virtual Account</span>
                                        </div>
                                        <span class="text-[10px] font-mono text-muted bg-neutral-100 px-2 py-0.5 rounded">Otomatis</span>
                                    </label>

                                    <!-- Mandiri VA -->
                                    <label class="flex items-center justify-between p-3 rounded-xl border cursor-pointer transition" :class="selectedPayment === 'Mandiri Virtual Account' ? 'border-primary bg-primary-50/20 ring-1 ring-primary' : 'border-hairline hover:bg-neutral-50'">
                                        <div class="flex items-center gap-2.5">
                                            <input type="radio" name="payment_method" value="Mandiri Virtual Account" x-model="selectedPayment" class="text-primary focus:ring-primary">
                                            <span class="font-semibold text-ink">Mandiri Virtual Account</span>
                                        </div>
                                        <span class="text-[10px] font-mono text-muted bg-neutral-100 px-2 py-0.5 rounded">Otomatis</span>
                                    </label>

                                    <!-- QRIS -->
                                    <label class="flex items-center justify-between p-3 rounded-xl border cursor-pointer transition" :class="selectedPayment === 'QRIS' ? 'border-primary bg-primary-50/20 ring-1 ring-primary' : 'border-hairline hover:bg-neutral-50'">
                                        <div class="flex items-center gap-2.5">
                                            <input type="radio" name="payment_method" value="QRIS" x-model="selectedPayment" class="text-primary focus:ring-primary">
                                            <span class="font-semibold text-ink">QRIS (Gopay, OVO, ShopeePay)</span>
                                        </div>
                                        <span class="text-[10px] font-mono text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-bold">Instan</span>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3 px-4 bg-primary hover:bg-primary-hover active:bg-primary-active text-white text-xs font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                                <span>Bayar Penuh Sekarang (Rp {{ number_format($booking->grand_total, 0, ',', '.') }})</span>
                                <span>→</span>
                            </button>
                        </form>

                        <p class="text-[10px] text-center text-muted mt-4 leading-relaxed">
                            Setelah pembayaran selesai, slot private trip Anda akan langsung berstatus <strong>Lunas</strong> dan Anda akan mendapatkan tautan WhatsApp grup koordinasi rombongan.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-hairline mt-12 py-5 text-xs text-muted">
        <div class="max-w-6xl mx-auto px-4 sm:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div class="font-semibold text-ink-heading">
                MiddleTrip
            </div>
            <div class="text-[11px]">
                &copy; {{ date('Y') }} MiddleTrip Expedition Co. All rights reserved.
            </div>
            <div class="flex items-center gap-4 text-[11px]">
                <a href="#" class="hover:text-ink transition">Bantuan</a>
                <a href="#" class="hover:text-ink transition">Privacy</a>
            </div>
        </div>
    </footer>
</body>
</html>
