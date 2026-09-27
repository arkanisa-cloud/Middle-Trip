<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Open Trip - MiddleTrip</title>
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

        <!-- Stepper (Stage 2 Active: Reservasi DP) -->
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

                <!-- Step 2: RESERVASI (Active) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full bg-emerald-800 ring-2 ring-emerald-200 flex items-center justify-center text-white">
                        <div class="w-2 h-2 rounded-full bg-white"></div>
                    </div>
                    <span class="text-[9px] uppercase tracking-wider text-emerald-900 font-bold mt-1">Reservasi</span>
                </div>

                <div class="w-14 sm:w-16 border-t-2 border-dotted border-gray-300 -mt-3.5"></div>

                <!-- Step 3: PRICE LOCK (Dotted) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full border border-gray-300 bg-transparent"></div>
                    <span class="text-[9px] uppercase tracking-wider text-muted-soft font-medium mt-1">Price Lock</span>
                </div>

                <div class="w-14 sm:w-16 border-t-2 border-dotted border-gray-300 -mt-3.5"></div>

                <!-- Step 4: PAY (Dotted) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full border border-gray-300 bg-transparent"></div>
                    <span class="text-[9px] uppercase tracking-wider text-muted-soft font-medium mt-1">Pay</span>
                </div>
            </div>
        </div>

        <form action="{{ route('checkout.pay_dp', $booking->booking_code) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">

                <!-- LEFT COLUMN: Informasi & Data -->
                <div class="lg:col-span-7 space-y-4">

                    <!-- Card 1: Informasi Trip -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs">
                        <div class="flex items-center gap-2 mb-4 text-primary font-bold text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <h2 class="text-sm font-bold text-ink-heading">Informasi Trip</h2>
                        </div>
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
                                <p class="text-muted text-[11px] font-medium">Keberangkatan</p>
                                <p class="text-body-strong font-semibold mt-0.5">{{ $booking->expedition->departure_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Harga Saat Ini -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs">
                        <div class="flex items-center gap-2 mb-2 text-primary font-bold text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <h2 class="text-sm font-bold text-ink-heading">Harga Saat Ini</h2>
                        </div>
                        <div class="mb-4">
                            <span class="text-primary font-extrabold text-base">Rp {{ number_format($booking->expedition->mountain->base_price, 0, ',', '.') }}</span>
                            <span class="text-primary text-xs font-semibold">/Orang</span>
                        </div>
                        <div class="divide-y divide-gray-100 border border-hairline rounded-xl overflow-hidden text-xs">
                            <div class="flex items-center justify-between px-4 py-2.5 bg-white">
                                <span class="text-muted">Peserta saat ini</span>
                                <span class="text-body-strong font-semibold">{{ $booking->expedition->quota_booked }} / {{ $booking->expedition->quota_max }} peserta</span>
                            </div>
                            <div class="flex items-center justify-between px-4 py-2.5 bg-white">
                                <span class="text-muted">Booking Fee (DP)</span>
                                <span class="text-body-strong font-semibold">Rp {{ number_format($booking->booking_fee_per_pax, 0, ',', '.') }}/orang</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Data Pemesan -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs">
                        <h2 class="text-sm font-bold text-ink-heading mb-4">Data Pemesan</h2>
                        <div class="space-y-3.5 text-xs">
                            <div>
                                <label class="block text-muted font-medium mb-1">Nama Lengkap</label>
                                <input type="text" value="{{ $booking->customer_name }}" readonly class="w-full px-3 py-2 border border-hairline rounded-lg bg-surface-subtle text-body-strong font-medium">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block text-muted font-medium mb-1">Nomor WhatsApp Aktif</label>
                                    <input type="text" value="{{ $booking->customer_phone }}" readonly class="w-full px-3 py-2 border border-hairline rounded-lg bg-surface-subtle text-body-strong font-medium">
                                </div>
                                <div>
                                    <label class="block text-muted font-medium mb-1">NIK</label>
                                    <input type="text" value="{{ $booking->customer_nik }}" readonly class="w-full px-3 py-2 border border-hairline rounded-lg bg-surface-subtle text-body-strong font-medium">
                                </div>
                            </div>
                            <div>
                                <label class="block text-muted font-medium mb-1">Email</label>
                                <input type="email" value="{{ $booking->customer_email }}" readonly class="w-full sm:w-1/2 px-3 py-2 border border-hairline rounded-lg bg-surface-subtle text-body-strong font-medium">
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Detail Peserta -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs">
                        <h2 class="text-sm font-bold text-ink-heading mb-4">Data Detail Peserta ({{ $booking->pax_count }} Orang)</h2>
                        <div class="space-y-2.5 text-xs">
                            @foreach($booking->participants as $index => $participant)
                                <div class="border border-hairline rounded-xl p-3 bg-surface-subtle flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-ink-heading">{{ $participant->full_name }} {{ $participant->is_leader ? '(Ketua)' : '' }}</p>
                                        <p class="text-muted text-[11px]">NIK: {{ $participant->nik }}</p>
                                    </div>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $participant->is_leader ? 'bg-primary-subtle text-primary' : 'bg-gray-200 text-gray-700' }}">
                                        Peserta {{ $index + 1 }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Sticky Payment Action -->
                <div class="lg:col-span-5">
                    <div class="sticky top-20 bg-white border border-hairline rounded-2xl p-5 sm:p-6 shadow-sm space-y-5">
                        <h3 class="text-sm font-bold text-ink-heading uppercase tracking-wider">Rincian Pembayaran</h3>

                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-muted">Jumlah Tiket</span>
                                <span class="font-bold text-ink-heading">{{ $booking->pax_count }} Orang</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-muted">Booking Fee (DP) per Orang</span>
                                <span class="font-semibold text-body-strong">Rp {{ number_format($booking->booking_fee_per_pax, 0, ',', '.') }}</span>
                            </div>
                            <div class="pt-2 border-t border-hairline flex items-center justify-between">
                                <span class="text-xs font-bold text-ink-heading">Total Booking Fee Wajib</span>
                                <span class="text-base font-extrabold text-primary">Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Pilihan Metode Pembayaran -->
                        <div class="space-y-2.5 pt-2 border-t border-hairline text-xs">
                            <label class="block font-bold text-ink-heading">Metode Pembayaran</label>
                            <label class="flex items-center justify-between p-3 border border-hairline rounded-xl cursor-pointer hover:border-primary">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="payment_method" value="BCA Virtual Account" checked class="accent-primary">
                                    <span class="font-medium">BCA Virtual Account</span>
                                </div>
                                <span class="text-[10px] font-bold text-muted bg-gray-100 px-2 py-0.5 rounded">Otomatis</span>
                            </label>
                            <label class="flex items-center justify-between p-3 border border-hairline rounded-xl cursor-pointer hover:border-primary">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="payment_method" value="QRIS" class="accent-primary">
                                    <span class="font-medium">QRIS (Gopay / OVO / Dana)</span>
                                </div>
                                <span class="text-[10px] font-bold text-muted bg-gray-100 px-2 py-0.5 rounded">Instan</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-primary hover:bg-primary-hover active:scale-[0.99] text-white py-3.5 px-4 rounded-xl font-bold text-xs sm:text-sm shadow-md transition-all text-center cursor-pointer">
                            Bayar Booking Fee Sekarang (Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }})
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </main>

</body>
</html>
