<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pemesanan - MiddleTrip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col justify-between bg-canvas text-ink antialiased selection:bg-primary selection:text-white font-sans">

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

        <!-- Stepper (Stage 3 Active: Price Lock) -->
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

                <div class="w-14 sm:w-16 h-[2px] bg-emerald-500 -mt-3.5"></div>

                <!-- Step 2: RESERVASI (Done) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-[9px] uppercase tracking-wider text-emerald-600 font-bold mt-1">Reservasi</span>
                </div>

                <div class="w-14 sm:w-16 h-[2px] {{ $booking->status === 'price_locked' ? 'bg-emerald-800' : 'border-t-2 border-dotted border-gray-300' }} -mt-3.5"></div>

                <!-- Step 3: PRICE LOCK (Active) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full {{ $booking->status === 'price_locked' ? 'bg-emerald-800 ring-2 ring-emerald-200' : 'border border-gray-300 bg-white' }} flex items-center justify-center text-white">
                        @if($booking->status === 'price_locked')
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                        @endif
                    </div>
                    <span class="text-[9px] uppercase tracking-wider {{ $booking->status === 'price_locked' ? 'text-emerald-900 font-bold' : 'text-muted-soft font-medium' }} mt-1">Price Lock</span>
                </div>

                <div class="w-14 sm:w-16 border-t-2 border-dotted border-gray-300 -mt-3.5"></div>

                <!-- Step 4: PAY (Dotted) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full border border-gray-300 bg-transparent"></div>
                    <span class="text-[9px] uppercase tracking-wider text-muted-soft font-medium mt-1">Pay</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">

            <!-- LEFT COLUMN: Status & Banner -->
            <div class="lg:col-span-7 space-y-4">

                <!-- Card 1: Banner Booking Berhasil -->
                <div class="relative overflow-hidden bg-white rounded-2xl border border-hairline shadow-xs">
                    <div class="relative bg-gradient-to-b from-emerald-50/70 via-gray-50 to-white p-5 sm:p-6 pb-5">
                        <div class="mb-2">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 tracking-wide uppercase">
                                {{ $booking->trip_type }} TRIP
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold text-ink-heading tracking-tight">
                            {{ $booking->expedition->mountain->name }} Expedition
                        </h1>
                        <p class="text-xs text-muted font-medium mt-0.5 mb-4">
                            {{ $booking->expedition->departure_date->format('d M Y') }} • {{ $booking->pax_count }} Peserta
                        </p>

                        <div class="pt-3 border-t border-hairline">
                            <div class="flex items-center gap-2 mb-1.5">
                                <div class="w-4 h-4 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-ink-heading">Booking Berhasil</span>
                            </div>
                            <p class="text-xs text-muted leading-relaxed">
                                Slot Anda telah diamankan dengan biaya DP sebesar Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }}.
                            </p>
                            @if($booking->status === 'reserved')
                                <p class="text-xs text-muted leading-relaxed">
                                    Harga akhir akan dikunci pada H-{{ $booking->expedition->mountain->price_lock_days_before_departure }} sebelum keberangkatan.
                                </p>
                            @elseif($booking->status === 'price_locked')
                                <div class="mt-2 p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs">
                                    <span class="font-bold">Harga Final Telah Dikunci:</span> Rp {{ number_format($booking->locked_price_per_pax, 0, ',', '.') }}/orang.
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
                            <p class="text-body-strong font-semibold mt-0.5">{{ $booking->expedition->mountain->name }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[11px] font-medium">Rute</p>
                            <p class="text-body-strong font-semibold mt-0.5">{{ $booking->route->name }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[11px] font-medium">Meeting Point</p>
                            <p class="text-body-strong font-semibold mt-0.5">{{ $booking->meetingPoint?->name ?? 'Basecamp Pendakian' }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[11px] font-medium">Kode Booking</p>
                            <p class="text-primary font-mono font-bold mt-0.5">{{ $booking->booking_code }}</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Actions / Settlement -->
            <div class="lg:col-span-5">
                <div class="sticky top-20 bg-white border border-hairline rounded-2xl p-5 sm:p-6 shadow-sm space-y-5">
                    @if($booking->status === 'reserved')
                        <div class="text-center py-4 space-y-3">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-ink-heading">Menunggu Price Lock</h3>
                            <p class="text-xs text-muted leading-relaxed">
                                Pendaftaran batch masih dibuka. Semakin banyak peserta yang bergabung, semakin murah harga akhir Anda!
                            </p>
                            <div class="p-3 bg-surface-subtle border border-hairline rounded-xl text-[11px] text-muted text-left">
                                DP Lunas: <span class="font-bold text-ink-heading">Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @elseif($booking->status === 'price_locked')
                        <form action="{{ route('checkout.settle', $booking->booking_code) }}" method="POST" class="space-y-4">
                            @csrf
                            <h3 class="text-sm font-bold text-ink-heading uppercase tracking-wider">Rincian Pelunasan</h3>

                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-muted">Harga Final ({{ $booking->pax_count }}x)</span>
                                    <span class="font-bold text-ink-heading">Rp {{ number_format($booking->locked_price_per_pax * $booking->pax_count, 0, ',', '.') }}</span>
                                </div>
                                @if($booking->shuttle_fee_total > 0)
                                    <div class="flex items-center justify-between">
                                        <span class="text-muted">Biaya Shuttle</span>
                                        <span class="font-semibold text-body-strong">Rp {{ number_format($booking->shuttle_fee_total, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                @if($booking->addons_fee_total > 0)
                                    <div class="flex items-center justify-between">
                                        <span class="text-muted">Sewa Add-ons</span>
                                        <span class="font-semibold text-body-strong">Rp {{ number_format($booking->addons_fee_total, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between text-emerald-600">
                                    <span>DP Telah Dibayar</span>
                                    <span class="font-bold">-Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }}</span>
                                </div>
                                <div class="pt-2 border-t border-hairline flex items-center justify-between">
                                    <span class="text-xs font-bold text-ink-heading">Sisa Pelunasan Wajib</span>
                                    <span class="text-base font-extrabold text-primary">Rp {{ number_format($booking->remaining_payment_total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-primary hover:bg-primary-hover active:scale-[0.99] text-white py-3 px-4 rounded-xl font-bold text-xs sm:text-sm shadow-md transition-all text-center cursor-pointer">
                                Bayar Pelunasan Sekarang (Rp {{ number_format($booking->remaining_payment_total, 0, ',', '.') }})
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </main>

</body>
</html>
