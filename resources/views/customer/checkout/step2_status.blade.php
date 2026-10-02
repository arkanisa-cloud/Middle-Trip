<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pemesanan - MiddleTrip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>

<body
    class="min-h-screen flex flex-col justify-between bg-canvas text-ink antialiased selection:bg-primary selection:text-white font-sans">

    <!-- Header -->
    <header class="w-full bg-white border-b border-hairline px-4 sm:px-8 py-3.5">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}"
                class="flex items-center gap-1.5 text-ink-heading font-extrabold text-lg tracking-tight">
                <svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                </svg>
                <span>Middle<span class="text-primary">Trip</span></span>
            </a>

            <div class="flex items-center gap-1.5 text-xs text-muted font-medium">
                <svg class="w-3.5 h-3.5 text-muted-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>Pembayaran Aman &amp; Terenkripsi</span>
            </div>
        </div>
    </header>

    <main class="w-full max-w-6xl mx-auto px-4 sm:px-8 py-6 flex-1">
        <div class="mb-4">
            <a href="{{ route('ekspedisi.show', $booking->expedition->mountain->slug) }}"
                class="inline-flex items-center text-xs text-muted hover:text-ink transition">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Detail Expedition
            </a>
        </div>

        <!-- Stepper (Stage 3 Active: Price Lock) -->
        <div class="max-w-md mx-auto mb-8">
            <div class="flex items-center justify-center">
                <!-- Step 1: DATA (Done) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="text-[9px] uppercase tracking-wider text-emerald-600 font-bold mt-1">Data</span>
                </div>

                <div class="w-14 sm:w-16 h-[2px] bg-emerald-500 -mt-3.5"></div>

                <!-- Step 2: RESERVASI (Done) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="text-[9px] uppercase tracking-wider text-emerald-600 font-bold mt-1">Reservasi</span>
                </div>

                <div
                    class="w-14 sm:w-16 h-[2px] {{ $booking->status === 'price_locked' ? 'bg-emerald-800' : 'border-t-2 border-dotted border-gray-300' }} -mt-3.5">
                </div>

                <!-- Step 3: PRICE LOCK (Active) -->
                <div class="flex flex-col items-center">
                    <div
                        class="w-5 h-5 rounded-full {{ $booking->status === 'price_locked' ? 'bg-emerald-800 ring-2 ring-emerald-200' : 'border border-gray-300 bg-white' }} flex items-center justify-center text-white">
                        @if ($booking->status === 'price_locked')
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                        @endif
                    </div>
                    <span
                        class="text-[9px] uppercase tracking-wider {{ $booking->status === 'price_locked' ? 'text-emerald-900 font-bold' : 'text-muted-soft font-medium' }} mt-1">Price
                        Lock</span>
                </div>

                <div class="w-14 sm:w-16 border-t-2 border-dotted border-gray-300 -mt-3.5"></div>

                <!-- Step 4: PAY (Dotted) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full border border-gray-300 bg-transparent"></div>
                    <span class="text-[9px] uppercase tracking-wider text-muted-soft font-medium mt-1">Pay</span>
                </div>
            </div>
        </div>

        @if (session('warning'))
            <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-3 text-amber-800">
                <svg class="w-5 h-5 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="text-xs sm:text-sm font-medium">
                    {{ session('warning') }}
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 flex items-start gap-3 text-rose-800">
                <svg class="w-5 h-5 text-rose-600 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-xs sm:text-sm font-medium">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if ($booking->status === 'price_locked')
            <form action="{{ route('checkout.settle', $booking->booking_code) }}" method="POST" id="settle-form">
                @csrf
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 relative">

            <!-- LEFT COLUMN: Status & Banner -->
            <div class="lg:col-span-7 space-y-4">

                <!-- Card 1: Banner Booking Berhasil -->
                <div class="relative overflow-hidden bg-white rounded-2xl border border-hairline shadow-xs">
                    <div class="relative bg-gradient-to-b from-emerald-50/70 via-gray-50 to-white p-5 sm:p-6 pb-5">
                        <div class="mb-2">
                            <span
                                class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 tracking-wide uppercase">
                                {{ $booking->trip_type }} TRIP
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold text-ink-heading tracking-tight">
                            {{ $booking->expedition->mountain->name }} Expedition
                        </h1>
                        <p class="text-xs text-muted font-medium mt-0.5 mb-4">
                            {{ ($booking->departure_date ?? $booking->expedition->departure_date)->format('d M Y') }} •
                            {{ $booking->pax_count }} Peserta
                        </p>

                        <div class="pt-3 border-t border-hairline">
                            <div class="flex items-center gap-2 mb-1.5">
                                <div
                                    class="w-4 h-4 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-ink-heading">Booking Berhasil</span>
                            </div>
                            <p class="text-xs text-muted leading-relaxed">
                                Slot Anda telah diamankan dengan biaya DP sebesar Rp
                                {{ number_format($booking->total_booking_fee, 0, ',', '.') }}.
                            </p>
                            @if ($booking->status === 'reserved')
                                <p class="text-xs text-muted leading-relaxed">
                                    Harga akhir akan dikunci pada
                                    H-{{ $booking->expedition->mountain->price_lock_days_before_departure }} sebelum
                                    keberangkatan.
                                </p>
                            @elseif($booking->status === 'price_locked')
                                <div
                                    class="mt-2 p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs">
                                    <span class="font-bold">Harga Final Telah Dikunci:</span> Rp
                                    {{ number_format($booking->locked_price_per_pax, 0, ',', '.') }}/orang.
                                    Silakan lakukan pelunasan sisa tagihan dalam waktu 48 jam.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card 2: Informasi Trip -->
                <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs">
                    <h2 class="text-sm font-bold text-ink-heading mb-4">Informasi Trip</h2>
                    <div class="grid grid-cols-2 gap-y-3.5 text-xs">
                        <div>
                            <p class="text-muted text-[11px] font-medium">Destinasi</p>
                            <p class="text-body-strong font-semibold mt-0.5">
                                {{ $booking->expedition->mountain->name }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[11px] font-medium">Rute</p>
                            <p class="text-body-strong font-semibold mt-0.5">{{ $booking->route->name }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[11px] font-medium">Meeting Point</p>
                            <p class="text-body-strong font-semibold mt-0.5">
                                {{ $booking->meetingPoint?->name ?? 'Basecamp Pendakian' }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[11px] font-medium">Kode Booking</p>
                            <p class="text-primary font-mono font-bold mt-0.5">{{ $booking->booking_code }}</p>
                        </div>
                    </div>
                </div>

                @if ($booking->status === 'price_locked')
                    <!-- Card 3: Pilih Metode Pembayaran Pelunasan -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <h2 class="text-sm font-bold text-ink-heading">Pilih Metode Pembayaran</h2>
                        </div>

                        <div class="space-y-2.5">
                            <!-- Virtual Account (Expanded by default) -->
                            <div class="border border-[#ECEAE4] rounded-xl overflow-hidden shadow-2xs">
                                <button type="button"
                                    class="w-full flex items-center justify-between px-4 py-3 bg-[#F9F8F6] text-xs font-semibold text-neutral-800 text-left transition-colors cursor-pointer"
                                    onclick="toggleAccordion('content-va', 'icon-va')">
                                    <span class="font-bold">Virtual Account (Transfer Bank)</span>
                                    <svg id="icon-va"
                                        class="w-4 h-4 text-neutral-500 transform transition-transform duration-200 rotate-180"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div id="content-va"
                                    class="p-3 divide-y divide-neutral-100 text-xs bg-white border-t border-[#ECEAE4]">
                                    <!-- Bank BCA -->
                                    <label
                                        class="flex items-center justify-between py-2.5 px-2 hover:bg-neutral-50/80 rounded-lg cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="bca" checked
                                                class="w-4 h-4 text-primary accent-primary focus:ring-primary cursor-pointer">
                                            <span class="text-neutral-800 font-medium">Bank BCA</span>
                                        </div>
                                        <div class="h-6 flex items-center">
                                            <img src="{{ asset('storage/payment/BCA.png') }}" alt="Bank BCA"
                                                class="h-5 max-h-5 w-auto object-contain">
                                        </div>
                                    </label>

                                    <!-- Bank BNI -->
                                    <label
                                        class="flex items-center justify-between py-2.5 px-2 hover:bg-neutral-50/80 rounded-lg cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="bni"
                                                class="w-4 h-4 text-primary accent-primary focus:ring-primary cursor-pointer">
                                            <span class="text-neutral-800 font-medium">Bank BNI</span>
                                        </div>
                                        <div class="h-6 flex items-center">
                                            <img src="{{ asset('storage/payment/BNI.png') }}" alt="Bank BNI"
                                                class="h-5 max-h-5 w-auto object-contain">
                                        </div>
                                    </label>

                                    <!-- Bank BRI -->
                                    <label
                                        class="flex items-center justify-between py-2.5 px-2 hover:bg-neutral-50/80 rounded-lg cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="bri"
                                                class="w-4 h-4 text-primary accent-primary focus:ring-primary cursor-pointer">
                                            <span class="text-neutral-800 font-medium">Bank BRI</span>
                                        </div>
                                        <div class="h-6 flex items-center">
                                            <img src="{{ asset('storage/payment/BRI.png') }}" alt="Bank BRI"
                                                class="h-5 max-h-5 w-auto object-contain">
                                        </div>
                                    </label>

                                    <!-- Bank Mandiri -->
                                    <label
                                        class="flex items-center justify-between py-2.5 px-2 hover:bg-neutral-50/80 rounded-lg cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="mandiri"
                                                class="w-4 h-4 text-primary accent-primary focus:ring-primary cursor-pointer">
                                            <span class="text-neutral-800 font-medium">Bank Mandiri</span>
                                        </div>
                                        <div class="h-6 flex items-center">
                                            <img src="{{ asset('storage/payment/Mandiri.png') }}" alt="Bank Mandiri"
                                                class="h-5 max-h-5 w-auto object-contain">
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- E-Wallet / QRIS -->
                            <div class="border border-[#ECEAE4] rounded-xl overflow-hidden shadow-2xs">
                                <button type="button"
                                    class="w-full flex items-center justify-between px-4 py-3 bg-white text-xs font-semibold text-neutral-800 text-left hover:bg-neutral-50 transition-colors cursor-pointer"
                                    onclick="toggleAccordion('content-ewallet', 'icon-ewallet')">
                                    <span class="font-bold">E-Wallet &amp; QRIS</span>
                                    <svg id="icon-ewallet"
                                        class="w-4 h-4 text-neutral-500 transform transition-transform duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="content-ewallet"
                                    class="hidden p-3 divide-y divide-neutral-100 text-xs bg-white border-t border-[#ECEAE4]">
                                    <label
                                        class="flex items-center justify-between py-2.5 px-2 hover:bg-neutral-50/80 rounded-lg cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="qris"
                                                class="w-4 h-4 text-primary accent-primary focus:ring-primary cursor-pointer">
                                            <div>
                                                <span class="text-neutral-800 font-medium block">QRIS (GoPay, OVO,
                                                    ShopeePay, Dana)</span>
                                                <span class="text-[10px] text-muted leading-tight">Scan instan via
                                                    seluruh aplikasi mobile banking &amp; e-wallet</span>
                                            </div>
                                        </div>
                                        <span
                                            class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Instan</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Credit Card -->
                            <div class="border border-[#ECEAE4] rounded-xl overflow-hidden shadow-2xs">
                                <button type="button"
                                    class="w-full flex items-center justify-between px-4 py-3 bg-white text-xs font-semibold text-neutral-800 text-left hover:bg-neutral-50 transition-colors cursor-pointer"
                                    onclick="toggleAccordion('content-cc', 'icon-cc')">
                                    <span class="font-bold">Credit / Debit Card</span>
                                    <svg id="icon-cc"
                                        class="w-4 h-4 text-neutral-500 transform transition-transform duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="content-cc" class="hidden p-3 text-xs bg-white border-t border-[#ECEAE4]">
                                    <label
                                        class="flex items-center justify-between py-2.5 px-2 hover:bg-neutral-50/80 rounded-lg cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="credit_card"
                                                class="w-4 h-4 text-primary accent-primary focus:ring-primary cursor-pointer">
                                            <div>
                                                <span class="text-neutral-800 font-medium block">Kartu Visa /
                                                    Mastercard / JCB</span>
                                                <span class="text-[10px] text-muted">Didukung proteksi 3D Secure
                                                    Midtrans</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <img src="{{ asset('storage/payment/Visa.jpeg') }}" alt="Visa"
                                                class="h-3.5 max-h-4 w-auto object-contain">
                                            <span
                                                class="text-[10px] font-bold text-rose-700 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">Mastercard</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Pay Later -->
                            <div class="border border-[#ECEAE4] rounded-xl overflow-hidden shadow-2xs">
                                <button type="button"
                                    class="w-full flex items-center justify-between px-4 py-3 bg-white text-xs font-semibold text-neutral-800 text-left hover:bg-neutral-50 transition-colors cursor-pointer"
                                    onclick="toggleAccordion('content-paylater', 'icon-paylater')">
                                    <span class="font-bold">Pay Later</span>
                                    <svg id="icon-paylater"
                                        class="w-4 h-4 text-neutral-500 transform transition-transform duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="content-paylater"
                                    class="hidden p-3 text-xs bg-white border-t border-[#ECEAE4]">
                                    <label
                                        class="flex items-center justify-between py-2.5 px-2 hover:bg-neutral-50/80 rounded-lg cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="paylater"
                                                class="w-4 h-4 text-primary accent-primary focus:ring-primary cursor-pointer">
                                            <div>
                                                <span class="text-neutral-800 font-medium block">Kredivo /
                                                    Akulaku</span>
                                                <span class="text-[10px] text-muted">Cicilan fleksibel 30 hari hingga
                                                    12 bulan</span>
                                            </div>
                                        </div>
                                        <span
                                            class="text-[10px] text-neutral-500 font-medium bg-neutral-100 px-2 py-0.5 rounded">Cicilan</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <!-- RIGHT COLUMN: Actions / Settlement -->
            <aside class="lg:col-span-5 w-full">
                <div
                    class="sticky top-24 z-20 bg-white border border-hairline rounded-2xl p-5 sm:p-6 shadow-sm space-y-5">
                    @if ($booking->status === 'reserved')
                        <div class="flex items-center gap-2 pb-3 border-b border-hairline">
                            <div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div>
                            <h3 class="text-xs font-bold text-ink-heading uppercase tracking-wider">DP Lunas • Menunggu
                                Price Lock</h3>
                        </div>

                        <div class="text-center py-4 space-y-3">
                            <div
                                class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-ink-heading">Menunggu Price Lock</h3>
                            <p class="text-xs text-muted leading-relaxed">
                                Pendaftaran batch masih dibuka. Semakin banyak peserta yang bergabung, semakin murah
                                harga akhir Anda!
                            </p>
                            <div
                                class="p-3 bg-surface-subtle border border-hairline rounded-xl text-[11px] text-muted text-left">
                                DP Lunas: <span class="font-bold text-ink-heading">Rp
                                    {{ number_format($booking->total_booking_fee, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @elseif($booking->status === 'price_locked')
                        <div class="flex items-center gap-2 pb-3 border-b border-hairline">
                            <div class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></div>
                            <h3 class="text-xs font-bold text-ink-heading uppercase tracking-wider">Menunggu Pelunasan
                            </h3>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-ink-heading uppercase tracking-wider">Rincian Pelunasan
                            </h3>

                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-muted">Harga Final ({{ $booking->pax_count }}x)</span>
                                    <span class="font-bold text-ink-heading">Rp
                                        {{ number_format($booking->locked_price_per_pax * $booking->pax_count, 0, ',', '.') }}</span>
                                </div>
                                @if ($booking->shuttle_fee_total > 0)
                                    <div class="flex items-center justify-between">
                                        <span class="text-muted">Biaya Shuttle</span>
                                        <span class="font-semibold text-body-strong">Rp
                                            {{ number_format($booking->shuttle_fee_total, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                @if ($booking->addons_fee_total > 0)
                                    <div class="flex items-center justify-between">
                                        <span class="text-muted">Sewa Add-ons</span>
                                        <span class="font-semibold text-body-strong">Rp
                                            {{ number_format($booking->addons_fee_total, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between text-emerald-600">
                                    <span>DP Telah Dibayar</span>
                                    <span class="font-bold">-Rp
                                        {{ number_format($booking->total_booking_fee, 0, ',', '.') }}</span>
                                </div>
                                <div class="pt-2 border-t border-hairline flex items-center justify-between">
                                    <span class="text-xs font-bold text-ink-heading">Sisa Pelunasan Wajib</span>
                                    <span class="text-base font-extrabold text-primary">Rp
                                        {{ number_format($booking->remaining_payment_total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Info Saluran Pembayaran Midtrans Snap -->
                            <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl space-y-1.5 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-slate-800">Pembayaran Midtrans</span>
                                    <span
                                        class="text-[9px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Instant</span>
                                </div>
                                <p class="text-[10px] text-muted leading-tight">
                                    QRIS, Virtual Account (BCA, Mandiri, BNI, BRI), Kartu Kredit, dll.
                                </p>
                            </div>

                            <!-- Checkbox Persetujuan Pelunasan -->
                            <label class="flex items-start gap-2.5 pt-1 cursor-pointer select-none">
                                <input type="checkbox" required checked
                                    class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary focus:ring-offset-0 accent-primary cursor-pointer mt-0.5 transition-colors">
                                <span class="text-[11px] text-muted leading-tight">Saya mengonfirmasi pelunasan sisa
                                    tagihan ekspedisi MiddleTrip.</span>
                            </label>

                            <button type="submit" id="settle-button"
                                class="w-full bg-primary hover:bg-primary-hover active:scale-[0.99] text-white py-3 px-4 rounded-xl font-bold text-xs sm:text-sm shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <svg id="settle-spinner" class="hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span id="settle-text">Bayar Pelunasan Sekarang (Rp
                                    {{ number_format($booking->remaining_payment_total, 0, ',', '.') }})</span>
                            </button>
                        </div>
                    @endif
                </div>
            </aside>

        </div>
        @if ($booking->status === 'price_locked')
            </form>
        @endif
    </main>

    <script>
        function toggleAccordion(contentId, iconId) {
            const content = document.getElementById(contentId);
            const icon = document.getElementById(iconId);
            if (!content || !icon) return;

            const isHidden = content.classList.contains('hidden');
            if (isHidden) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('settle-form');
            const settleBtn = document.getElementById('settle-button');
            const settleText = document.getElementById('settle-text');
            const settleSpinner = document.getElementById('settle-spinner');

            if (!form || !settleBtn) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                settleBtn.disabled = true;
                settleBtn.classList.add('opacity-75', 'cursor-not-allowed');
                if (settleSpinner) settleSpinner.classList.remove('hidden');
                if (settleText) settleText.textContent = 'Menyiapkan Pembayaran...';

                try {
                    const formData = new FormData(form);
                    const response = await fetch(
                        "{{ route('checkout.settle', $booking->booking_code) }}", {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                    const data = await response.json();

                    if (data.status === 'already_paid') {
                        window.location.href = data.redirect_url;
                        return;
                    }

                    if (data.snap_token) {
                        if (typeof window.snap === 'undefined') {
                            alert(
                                'Gagal memuat modul pembayaran Midtrans. Mengalihkan ke halaman pembayaran...');
                            window.location.href = data.redirect_url;
                            return;
                        }

                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.href =
                                    "{{ route('checkout.success', $booking->booking_code) }}";
                            },
                            onPending: function(result) {
                                alert(
                                    'Tagihan pelunasan telah dibuat. Silakan selesaikan pembayaran sesuai petunjuk yang diberikan.');
                                resetSettleButton();
                            },
                            onError: function(result) {
                                alert(
                                    'Pembayaran gagal atau dibatalkan. Silakan coba kembali.');
                                resetSettleButton();
                            },
                            onClose: function() {
                                resetSettleButton();
                            }
                        });
                    } else {
                        alert(data.message || 'Gagal memproses tiket pelunasan.');
                        resetSettleButton();
                    }
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan jaringan atau server. Silakan coba kembali.');
                    resetSettleButton();
                }
            });

            function resetSettleButton() {
                settleBtn.disabled = false;
                settleBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                if (settleSpinner) settleSpinner.classList.add('hidden');
                if (settleText) settleText.textContent =
                    'Bayar Pelunasan Sekarang (Rp {{ number_format($booking->remaining_payment_total, 0, ',', '.') }})';
            }
        });
    </script>
</body>

</html>
