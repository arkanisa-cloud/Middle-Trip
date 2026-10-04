<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Open Trip - MiddleTrip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <main class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 flex-1">
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

        <!-- Stepper (Stage 2 Active: Reservasi DP) -->
        <div class="w-full max-w-sm sm:max-w-md mx-auto mb-6 sm:mb-8 px-2">
            <div class="flex items-center justify-between sm:justify-center">
                <!-- Step 1: DATA (Done) -->
                <div class="flex flex-col items-center">
                    <div
                        class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span
                        class="text-[8px] sm:text-[9px] uppercase tracking-wider text-emerald-600 font-bold mt-1 text-center">Data</span>
                </div>

                <div class="w-6 sm:w-12 md:w-16 h-[2px] bg-emerald-500 -mt-3.5 mx-1 sm:mx-2"></div>

                <!-- Step 2: RESERVASI (Active) -->
                <div class="flex flex-col items-center">
                    <div
                        class="w-5 h-5 rounded-full bg-emerald-800 ring-2 ring-emerald-200 flex items-center justify-center text-white shrink-0">
                        <div class="w-2 h-2 rounded-full bg-white"></div>
                    </div>
                    <span
                        class="text-[8px] sm:text-[9px] uppercase tracking-wider text-emerald-900 font-bold mt-1 text-center">Reservasi</span>
                </div>

                <div class="w-6 sm:w-12 md:w-16 border-t-2 border-dotted border-gray-300 -mt-3.5 mx-1 sm:mx-2"></div>

                <!-- Step 3: PRICE LOCK (Dotted) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full border border-gray-300 bg-transparent shrink-0"></div>
                    <span
                        class="text-[8px] sm:text-[9px] uppercase tracking-wider text-muted-soft font-medium mt-1 text-center">Price
                        Lock</span>
                </div>

                <div class="w-6 sm:w-12 md:w-16 border-t-2 border-dotted border-gray-300 -mt-3.5 mx-1 sm:mx-2"></div>

                <!-- Step 4: PAY (Dotted) -->
                <div class="flex flex-col items-center">
                    <div class="w-5 h-5 rounded-full border border-gray-300 bg-transparent shrink-0"></div>
                    <span
                        class="text-[8px] sm:text-[9px] uppercase tracking-wider text-muted-soft font-medium mt-1 text-center">Pay</span>
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

        @php
            $mountain = $booking->expedition?->mountain;
            $currentBooked = $booking->expedition?->quota_booked ?? 1;
            $quotaMax = $booking->expedition?->quota_max ?? 10;
            $hikingType = $booking->hiking_type ?? 'camping';

            $currentPricePerPax = $mountain
                ? $mountain->getTierPriceForPax($currentBooked, $hikingType)
                : $mountain?->base_price ?? 500000;

            $nextTier = null;
            $nextTierPrice = null;
            $nextTierPax = null;
            if ($mountain && $mountain->priceTiers) {
                $nextTier = $mountain->priceTiers->where('min_pax', '>', $currentBooked)->sortBy('min_pax')->first();
                if ($nextTier) {
                    $nextTierPax = $nextTier->min_pax;
                    $nextTierPrice = $mountain->getTierPriceForPax($nextTierPax, $hikingType);
                }
            }
        @endphp

        <form action="{{ route('checkout.pay_dp', $booking->booking_code) }}" method="POST" id="payment-form">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 relative">

                <!-- LEFT COLUMN: Informasi & Data -->
                <div class="lg:col-span-7 space-y-4">

                    <!-- Card 1: Informasi Trip -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs">
                        <div class="flex items-center gap-2 mb-4 text-primary font-bold text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h2 class="text-sm font-bold text-ink-heading">Informasi Trip</h2>
                        </div>
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
                                <p class="text-muted text-[11px] font-medium">Keberangkatan</p>
                                <p class="text-body-strong font-semibold mt-0.5">
                                    {{ ($booking->departure_date ?? $booking->expedition->departure_date)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Harga Saat Ini & Skema Tier -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs">
                        <div class="flex items-center gap-2 mb-2 text-primary font-bold text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <h2 class="text-sm font-bold text-ink-heading">Harga Saat Ini</h2>
                        </div>
                        <div class="mb-4 flex items-baseline gap-1.5">
                            <span class="text-primary font-extrabold text-xl sm:text-2xl">Rp
                                {{ number_format($currentPricePerPax, 0, ',', '.') }}</span>
                            <span class="text-primary text-xs font-semibold">/Orang</span>
                        </div>
                        <div
                            class="divide-y divide-gray-100 border border-hairline rounded-xl overflow-hidden text-xs">
                            <div class="flex items-center justify-between px-4 py-2.5 bg-white">
                                <span class="text-muted">Peserta saat ini</span>
                                <span class="text-body-strong font-semibold">{{ $currentBooked }}
                                    / {{ $quotaMax }} peserta</span>
                            </div>
                            <div class="flex items-center justify-between px-4 py-2.5 bg-white">
                                <span class="text-muted">Harga berikutnya</span>
                                <span class="text-body-strong font-semibold">
                                    @if ($nextTier && $nextTierPrice)
                                        {{ $nextTierPax }} peserta = Rp
                                        {{ number_format($nextTierPrice, 0, ',', '.') }}/orang
                                    @else
                                        <span class="text-emerald-600 font-semibold">Tier termurah telah
                                            aktif</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center justify-between px-4 py-2.5 bg-white">
                                <span class="text-muted">Booking Fee (DP)</span>
                                <span class="text-body-strong font-semibold">Rp
                                    {{ number_format($booking->booking_fee_per_pax, 0, ',', '.') }}/orang</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-muted mt-2.5 pt-3 flex items-start gap-1.5">
                            <svg class="w-3.5 h-3.5 text-primary mt-0.5 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Harga dihitung otomatis sesuai jumlah peserta saat ini. Semakin banyak peserta yang
                                bergabung, harga final saat Price Lock akan semakin murah.</span>
                        </p>
                    </div>

                    <!-- Card 3: Data Pemesan (Kontak Utama) -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-bold text-ink-heading">Data Pemesan (Kontak Utama)</h2>
                            <span
                                class="text-[10px] text-muted bg-gray-100 px-2 py-0.5 rounded-full font-medium">Koordinator</span>
                        </div>
                        <div class="space-y-3.5 text-xs">
                            <div>
                                <label class="block text-muted font-medium mb-1">Nama Lengkap Pemesan <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="customer_name" id="input_customer_name"
                                    value="{{ old('customer_name') }}" required minlength="3" maxlength="150"
                                    class="w-full px-3.5 py-2.5 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-slate-800 font-outfit text-sm font-normal transition placeholder:text-slate-400"
                                    placeholder="Nama sesuai KTP">
                                @error('customer_name')
                                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block text-muted font-medium mb-1">Nomor WhatsApp Aktif <span
                                            class="text-rose-500">*</span></label>
                                    <input type="tel" name="customer_phone" id="input_customer_phone"
                                        value="{{ old('customer_phone') }}" required minlength="9" maxlength="25"
                                        class="w-full px-3.5 py-2.5 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-slate-800 font-outfit text-sm font-normal transition placeholder:text-slate-400"
                                        placeholder="08123456789">
                                    @error('customer_phone')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-muted font-medium mb-1">NIK Pemesan (16 Digit) <span
                                            class="text-rose-500">*</span></label>
                                    <input type="text" name="customer_nik" id="input_customer_nik"
                                        value="{{ old('customer_nik') }}" required minlength="16" maxlength="16"
                                        pattern="[0-9]{16}"
                                        class="w-full px-3.5 py-2.5 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-slate-800 font-outfit text-sm font-normal transition placeholder:text-slate-400"
                                        placeholder="16 digit angka KTP">
                                    @error('customer_nik')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-muted font-medium mb-1">Email <span
                                        class="text-rose-500">*</span></label>
                                <input type="email" name="customer_email" id="input_customer_email"
                                    value="{{ old('customer_email') }}" required maxlength="150"
                                    class="w-full sm:w-1/2 px-3.5 py-2.5 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-slate-800 font-outfit text-sm font-normal transition placeholder:text-slate-400"
                                    placeholder="alamat@email.com">
                                @error('customer_email')
                                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Detail Peserta (Diisi Manual per Tiket) -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 mb-4">
                            <div>
                                <h2 class="text-sm font-bold text-ink-heading">Data Detail Peserta
                                    ({{ $booking->pax_count }} Orang)</h2>
                                <p class="text-[11px] text-muted">Wajib mengisi data identitas seluruh anggota pendaki
                                    untuk asuransi dan izin simaksi.</p>
                            </div>
                            <button type="button" onclick="copyPemesanToKetua()"
                                class="inline-flex items-center text-xs text-primary hover:underline font-semibold cursor-pointer shrink-0 self-start sm:self-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                </svg>
                                Salin Pemesan ke Ketua
                            </button>
                        </div>

                        <div class="space-y-2.5 text-xs">
                            @if ($booking->pax_count == 1)
                                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 space-y-3">
                                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center font-bold text-[10px]">1</span>
                                            <span class="font-bold text-ink-heading">Ketua (Peserta Utama)</span>
                                        </div>
                                        <span
                                            class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Ketua (Leader)
                                        </span>
                                    </div>
                                    <input type="hidden" name="participants[0][is_leader]" value="1">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-slate-600 font-medium mb-1">
                                                Nama Lengkap Ketua <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" name="participants[0][full_name]"
                                                id="participant_name_0" value="{{ old('participants.0.full_name') }}"
                                                required minlength="3" maxlength="150"
                                                class="w-full px-3.5 py-2.5 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-slate-800 font-outfit text-sm font-normal transition placeholder:text-slate-400"
                                                placeholder="Nama lengkap sesuai KTP">
                                            @error('participants.0.full_name')
                                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-slate-600 font-medium mb-1">
                                                NIK KTP (16 Digit) <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" name="participants[0][nik]" id="participant_nik_0"
                                                value="{{ old('participants.0.nik') }}" required minlength="16"
                                                maxlength="16" pattern="[0-9]{16}"
                                                class="w-full px-3.5 py-2.5 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-slate-800 font-outfit text-sm font-normal transition placeholder:text-slate-400"
                                                placeholder="16 digit NIK">
                                            @error('participants.0.nik')
                                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Dropdown Accordion List Saat Peserta > 1 (Sesuai Referensi payment_open_trip_1.html) --}}
                                @for ($i = 0; $i < $booking->pax_count; $i++)
                                    @php
                                        $isLeader = $i === 0;
                                        $hasError =
                                            $errors->has("participants.{$i}.full_name") ||
                                            $errors->has("participants.{$i}.nik");
                                        $isOpen = $isLeader || $hasError;
                                        $itemTitle = $isLeader ? 'Ketua' : 'Anggota ' . $i;
                                    @endphp
                                    <div class="border border-[#ECEAE4] rounded-xl overflow-hidden shadow-2xs">
                                        <button type="button"
                                            class="w-full flex items-center justify-between px-3.5 sm:px-4 py-3 {{ $isOpen ? 'bg-[#F9F8F6]' : 'bg-white hover:bg-neutral-50' }} text-xs font-semibold text-neutral-800 text-left transition-colors cursor-pointer"
                                            onclick="toggleAccordion('content-peserta-{{ $i }}', 'icon-peserta-{{ $i }}')">
                                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2.5">
                                                <span
                                                    class="w-5 h-5 rounded-full {{ $isLeader ? 'bg-primary text-white' : 'bg-slate-200 text-slate-700' }} flex items-center justify-center font-bold text-[10px] shrink-0">
                                                    {{ $i + 1 }}
                                                </span>
                                                <span class="font-bold text-ink-heading">{{ $itemTitle }}</span>
                                                <span
                                                    class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isLeader ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                                    {{ $isLeader ? 'Ketua (Leader)' : 'Anggota' }}
                                                </span>
                                            </div>
                                            <svg id="icon-peserta-{{ $i }}"
                                                class="w-4 h-4 text-neutral-500 transform transition-transform duration-200 shrink-0 ml-2 {{ $isOpen ? 'rotate-180' : '' }}"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <div id="content-peserta-{{ $i }}"
                                            class="{{ $isOpen ? '' : 'hidden' }} p-4 space-y-3 text-xs bg-white border-t border-[#ECEAE4]">
                                            <input type="hidden" name="participants[{{ $i }}][is_leader]"
                                                value="{{ $isLeader ? '1' : '0' }}">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-slate-600 font-medium mb-1">
                                                        Nama {{ $itemTitle }} <span class="text-rose-500">*</span>
                                                    </label>
                                                    <input type="text"
                                                        name="participants[{{ $i }}][full_name]"
                                                        id="participant_name_{{ $i }}"
                                                        value="{{ old("participants.{$i}.full_name") }}" required
                                                        minlength="3" maxlength="150"
                                                        class="w-full px-3.5 py-2.5 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-slate-800 font-outfit text-sm font-normal transition placeholder:text-slate-400"
                                                        placeholder="Nama lengkap sesuai KTP">
                                                    @error("participants.{$i}.full_name")
                                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-slate-600 font-medium mb-1">
                                                        NIK (16 Digit) <span class="text-rose-500">*</span>
                                                    </label>
                                                    <input type="text"
                                                        name="participants[{{ $i }}][nik]"
                                                        id="participant_nik_{{ $i }}"
                                                        value="{{ old("participants.{$i}.nik") }}" required
                                                        minlength="16" maxlength="16" pattern="[0-9]{16}"
                                                        class="w-full px-3.5 py-2.5 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-slate-800 font-outfit text-sm font-normal transition placeholder:text-slate-400"
                                                        placeholder="16 digit NIK">
                                                    @error("participants.{$i}.nik")
                                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                        </div>
                    </div>

                    <!-- Card 5: Pilih Metode Pembayaran -->
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
                                                loading="lazy" decoding="async"
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
                                                loading="lazy" decoding="async"
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
                                                loading="lazy" decoding="async"
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
                                                loading="lazy" decoding="async"
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
                                                loading="lazy" decoding="async"
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

                </div>

                <!-- RIGHT COLUMN: Sticky Payment Action -->
                <aside class="lg:col-span-5 w-full">
                    <div
                        class="sticky top-24 z-20 bg-white border border-hairline rounded-2xl p-5 sm:p-6 shadow-sm space-y-5">
                        <div class="flex items-center gap-2 pb-3 border-b border-hairline">
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></div>
                            <h3 class="text-xs font-bold text-ink-heading uppercase tracking-wider">Menunggu Pembayaran
                                DP</h3>
                        </div>

                        <div class="space-y-2.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-muted">Jumlah Tiket</span>
                                <span class="font-bold text-ink-heading">{{ $booking->pax_count }} Orang</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-muted">Booking Fee (DP) per Orang</span>
                                <span class="font-semibold text-body-strong">Rp
                                    {{ number_format($booking->booking_fee_per_pax, 0, ',', '.') }}</span>
                            </div>
                            <div class="pt-2.5 border-t border-hairline flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-ink-heading block">Total Booking Fee
                                        Wajib</span>
                                    <span class="text-[10px] text-muted">Untuk mengamankan slot batch</span>
                                </div>
                                <span class="text-base font-extrabold text-primary">Rp
                                    {{ number_format($booking->total_booking_fee, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Checkbox Persetujuan Syarat & Ketentuan -->
                        <label class="flex items-start gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" required checked
                                class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary focus:ring-offset-0 accent-primary cursor-pointer mt-0.5 transition-colors">
                            <span class="text-[11px] text-muted leading-tight">Saya menyetujui <a href="#"
                                    class="text-primary font-medium underline hover:text-primary-hover">Syarat
                                    &amp; Ketentuan</a> serta kebijakan ekspedisi MiddleTrip.</span>
                        </label>

                        <button type="submit" id="pay-button"
                            class="w-full bg-primary hover:bg-primary-hover active:scale-[0.99] text-white py-3.5 px-4 rounded-xl font-bold text-xs sm:text-sm shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <svg id="pay-button-spinner" class="hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span id="pay-button-text">Bayar Booking Fee Sekarang (Rp
                                {{ number_format($booking->total_booking_fee, 0, ',', '.') }})</span>
                        </button>

                        <!-- Tombol Batalkan Pesanan (Sebelum Bayar) -->
                        <button type="button" onclick="confirmCancelBooking()"
                            class="w-full bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 py-2.5 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Batalkan Pesanan Ini</span>
                        </button>
                    </div>
                </aside>
            </div>
        </form>
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

        function copyPemesanToKetua() {
            const custName = document.getElementById('input_customer_name')?.value;
            const custNik = document.getElementById('input_customer_nik')?.value;
            if (custName && document.getElementById('participant_name_0')) {
                document.getElementById('participant_name_0').value = custName;
            }
            if (custNik && document.getElementById('participant_nik_0')) {
                document.getElementById('participant_nik_0').value = custNik;
            }
            // Pastikan accordion ketua terbuka saat tombol salin ditekan
            const ketuaContent = document.getElementById('content-peserta-0');
            const ketuaIcon = document.getElementById('icon-peserta-0');
            if (ketuaContent && ketuaContent.classList.contains('hidden')) {
                ketuaContent.classList.remove('hidden');
                if (ketuaIcon) ketuaIcon.classList.add('rotate-180');
            }
        }

        function confirmCancelBooking() {
            if (typeof Swal === 'undefined') {
                if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                    submitCancel();
                }
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Batalkan Reservasi?',
                text: 'Apakah Anda yakin ingin membatalkan pesanan #{{ $booking->booking_code }} ini? Kuota Anda akan dilepaskan kembali.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan Pesanan',
                cancelButtonText: 'Kembali',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'rounded-xl font-bold text-xs px-4 py-2.5',
                    cancelButton: 'rounded-xl font-medium text-xs px-4 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    submitCancel();
                }
            });

            function submitCancel() {
                const cancelForm = document.createElement('form');
                cancelForm.method = 'POST';
                cancelForm.action = "{{ route('checkout.cancel', $booking->booking_code) }}";
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = "{{ csrf_token() }}";
                cancelForm.appendChild(csrf);
                document.body.appendChild(cancelForm);
                cancelForm.submit();
            }
        }

        // Handler Midtrans Snap Popup dengan Alert DP Non-Refundable
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('payment-form');
            const payBtn = document.getElementById('pay-button');
            const payBtnText = document.getElementById('pay-button-text');
            const payBtnSpinner = document.getElementById('pay-button-spinner');

            if (!form || !payBtn) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                const dpAmount = "Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }}";

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ketentuan Uang Muka (DP)',
                        html: `<div class="text-left text-xs space-y-2.5 text-slate-600">
                            <p>Anda akan melakukan pembayaran uang muka (DP) sebesar <b class="text-slate-900">${dpAmount}</b>.</p>
                            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-[11px] leading-relaxed">
                                <b>⚠️ Pemberitahuan Penting:</b><br>
                                Uang muka (DP Booking Fee) yang telah dibayarkan <b>bersifat non-refundable (tidak dapat dikembalikan/hangus)</b> apabila pesanan dibatalkan secara sepihak oleh pendaki.
                            </div>
                            <p class="text-[11px] text-slate-500">Apakah data manifes dan jadwal pendakian Anda sudah benar?</p>
                        </div>`,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Saya Paham & Bayar',
                        cancelButtonText: 'Periksa Kembali',
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#64748b',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'rounded-xl font-bold text-xs px-4 py-2.5',
                            cancelButton: 'rounded-xl font-medium text-xs px-4 py-2.5'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            processPayment();
                        }
                    });
                } else {
                    if (confirm(
                            `Perhatian: DP ${dpAmount} bersifat non-refundable (tidak dapat dikembalikan setelah dibayar). Lanjutkan pembayaran?`
                        )) {
                        processPayment();
                    }
                }
            });

            async function processPayment() {
                payBtn.disabled = true;
                payBtn.classList.add('opacity-75', 'cursor-not-allowed');
                if (payBtnSpinner) payBtnSpinner.classList.remove('hidden');
                if (payBtnText) payBtnText.textContent = 'Menyiapkan Pembayaran...';

                try {
                    const formData = new FormData(form);
                    const response = await fetch(
                        "{{ route('checkout.pay_dp', $booking->booking_code) }}", {
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
                                'Gagal memuat modul pembayaran Midtrans. Mengalihkan ke halaman pembayaran...'
                            );
                            window.location.href = data.redirect_url;
                            return;
                        }

                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.href =
                                    "{{ route('checkout.status', $booking->booking_code) }}";
                            },
                            onPending: function(result) {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Menunggu Pembayaran',
                                        text: 'Tagihan pembayaran Booking Fee telah dibuat. Silakan selesaikan pembayaran sesuai petunjuk yang diberikan.',
                                        confirmButtonColor: '#10b981'
                                    });
                                } else {
                                    alert('Tagihan pembayaran Booking Fee telah dibuat.');
                                }
                                resetPayButton();
                            },
                            onError: function(result) {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Pembayaran Gagal',
                                        text: 'Pembayaran gagal atau dibatalkan. Silakan coba kembali.',
                                        confirmButtonColor: '#ef4444'
                                    });
                                } else {
                                    alert(
                                        'Pembayaran gagal atau dibatalkan. Silakan coba kembali.'
                                    );
                                }
                                resetPayButton();
                            },
                            onClose: function() {
                                resetPayButton();
                            }
                        });
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message || 'Gagal memproses tiket pembayaran.',
                                confirmButtonColor: '#ef4444'
                            });
                        } else {
                            alert(data.message || 'Gagal memproses tiket pembayaran.');
                        }
                        resetPayButton();
                    }
                } catch (err) {
                    console.error(err);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan jaringan atau server. Silakan coba kembali.',
                            confirmButtonColor: '#ef4444'
                        });
                    } else {
                        alert('Terjadi kesalahan jaringan atau server. Silakan coba kembali.');
                    }
                    resetPayButton();
                }
            }

            function resetPayButton() {
                payBtn.disabled = false;
                payBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                if (payBtnSpinner) payBtnSpinner.classList.add('hidden');
                if (payBtnText) payBtnText.textContent =
                    'Bayar Booking Fee Sekarang (Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }})';
            }
        });
    </script>
