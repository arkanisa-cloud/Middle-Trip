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
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
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

        <form action="{{ route('checkout.pay_dp', $booking->booking_code) }}" method="POST" id="payment-form">
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
                                <p class="text-body-strong font-semibold mt-0.5">{{ ($booking->departure_date ?? $booking->expedition->departure_date)->format('d M Y') }}</p>
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

                    <!-- Card 3: Data Pemesan (Kontak Utama) -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-bold text-ink-heading">Data Pemesan (Kontak Utama)</h2>
                            <span class="text-[10px] text-muted bg-gray-100 px-2 py-0.5 rounded-full font-medium">Koordinator</span>
                        </div>
                        <div class="space-y-3.5 text-xs">
                            <div>
                                <label class="block text-muted font-medium mb-1">Nama Lengkap Pemesan <span class="text-rose-500">*</span></label>
                                <input type="text" name="customer_name" id="input_customer_name"
                                    value="{{ old('customer_name', $booking->customer_name) }}"
                                    required minlength="3" maxlength="150"
                                    class="w-full px-3 py-2 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-body-strong font-medium transition"
                                    placeholder="Nama sesuai KTP">
                                @error('customer_name')
                                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block text-muted font-medium mb-1">Nomor WhatsApp Aktif <span class="text-rose-500">*</span></label>
                                    <input type="tel" name="customer_phone" id="input_customer_phone"
                                        value="{{ old('customer_phone', $booking->customer_phone) }}"
                                        required minlength="9" maxlength="25"
                                        class="w-full px-3 py-2 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-body-strong font-medium transition"
                                        placeholder="Contoh: 08123456789">
                                    @error('customer_phone')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-muted font-medium mb-1">NIK Pemesan (16 Digit) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="customer_nik" id="input_customer_nik"
                                        value="{{ old('customer_nik', $booking->customer_nik) }}"
                                        required minlength="16" maxlength="16" pattern="[0-9]{16}"
                                        class="w-full px-3 py-2 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-body-strong font-medium transition"
                                        placeholder="16 digit angka KTP">
                                    @error('customer_nik')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-muted font-medium mb-1">Email <span class="text-rose-500">*</span></label>
                                <input type="email" name="customer_email" id="input_customer_email"
                                    value="{{ old('customer_email', $booking->customer_email) }}"
                                    required maxlength="150"
                                    class="w-full sm:w-1/2 px-3 py-2 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-body-strong font-medium transition"
                                    placeholder="alamat@email.com">
                                @error('customer_email')
                                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Detail Peserta (Diisi Manual per Tiket) -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-sm font-bold text-ink-heading">Data Detail Peserta ({{ $booking->pax_count }} Orang)</h2>
                                <p class="text-[11px] text-muted">Wajib mengisi data identitas seluruh anggota pendaki untuk asuransi dan izin simaksi.</p>
                            </div>
                            <button type="button" onclick="copyPemesanToKetua()" class="text-[11px] text-primary hover:underline font-semibold cursor-pointer">
                                Salin Pemesan ke Ketua
                            </button>
                        </div>

                        <div class="space-y-2.5 text-xs">
                            @if($booking->pax_count == 1)
                                @php
                                    $existingPart = $booking->participants[0] ?? null;
                                    $defaultName = old('participants.0.full_name', $existingPart?->full_name ?? $booking->customer_name);
                                    $defaultNik = old('participants.0.nik', $existingPart?->nik ?? $booking->customer_nik);
                                @endphp
                                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 space-y-3">
                                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center font-bold text-[10px]">1</span>
                                            <span class="font-bold text-ink-heading">Ketua (Peserta Utama)</span>
                                        </div>
                                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
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
                                                id="participant_name_0"
                                                value="{{ $defaultName }}"
                                                required minlength="3" maxlength="150"
                                                class="w-full px-3 py-2 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-body-strong font-medium transition"
                                                placeholder="Nama lengkap sesuai KTP">
                                            @error('participants.0.full_name')
                                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-slate-600 font-medium mb-1">
                                                NIK KTP (16 Digit) <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" name="participants[0][nik]"
                                                id="participant_nik_0"
                                                value="{{ $defaultNik }}"
                                                required minlength="16" maxlength="16" pattern="[0-9]{16}"
                                                class="w-full px-3 py-2 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-body-strong font-medium transition"
                                                placeholder="16 digit NIK">
                                            @error('participants.0.nik')
                                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Dropdown Accordion List Saat Peserta > 1 (Sesuai Referensi payment_open_trip_1.html) --}}
                                @for($i = 0; $i < $booking->pax_count; $i++)
                                    @php
                                        $existingPart = $booking->participants[$i] ?? null;
                                        $isLeader = ($i === 0);
                                        $hasError = $errors->has("participants.{$i}.full_name") || $errors->has("participants.{$i}.nik");
                                        $isOpen = $isLeader || $hasError;
                                        $defaultName = old("participants.{$i}.full_name", $existingPart?->full_name ?? ($isLeader ? $booking->customer_name : ''));
                                        $defaultNik = old("participants.{$i}.nik", $existingPart?->nik ?? ($isLeader ? $booking->customer_nik : ''));
                                        $itemTitle = $isLeader ? 'Ketua' : 'Anggota ' . $i;
                                    @endphp
                                    <div class="border border-[#ECEAE4] rounded-xl overflow-hidden shadow-2xs">
                                        <button type="button"
                                            class="w-full flex items-center justify-between px-4 py-3 {{ $isOpen ? 'bg-[#F9F8F6]' : 'bg-white hover:bg-neutral-50' }} text-xs font-semibold text-neutral-800 text-left transition-colors cursor-pointer"
                                            onclick="toggleAccordion('content-peserta-{{ $i }}', 'icon-peserta-{{ $i }}')">
                                            <div class="flex items-center gap-2.5">
                                                <span class="w-5 h-5 rounded-full {{ $isLeader ? 'bg-primary text-white' : 'bg-slate-200 text-slate-700' }} flex items-center justify-center font-bold text-[10px]">
                                                    {{ $i + 1 }}
                                                </span>
                                                <span class="font-bold text-ink-heading">{{ $itemTitle }}</span>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isLeader ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                                    {{ $isLeader ? 'Ketua (Leader)' : 'Anggota' }}
                                                </span>
                                            </div>
                                            <svg id="icon-peserta-{{ $i }}" class="w-4 h-4 text-neutral-500 transform transition-transform duration-200 {{ $isOpen ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>

                                        <div id="content-peserta-{{ $i }}" class="{{ $isOpen ? '' : 'hidden' }} p-4 space-y-3 text-xs bg-white border-t border-[#ECEAE4]">
                                            <input type="hidden" name="participants[{{ $i }}][is_leader]" value="{{ $isLeader ? '1' : '0' }}">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-slate-600 font-medium mb-1">
                                                        Nama {{ $itemTitle }} <span class="text-rose-500">*</span>
                                                    </label>
                                                    <input type="text" name="participants[{{ $i }}][full_name]"
                                                        id="participant_name_{{ $i }}"
                                                        value="{{ $defaultName }}"
                                                        required minlength="3" maxlength="150"
                                                        class="w-full px-3 py-2 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-body-strong font-medium transition"
                                                        placeholder="Nama lengkap sesuai KTP">
                                                    @error("participants.{$i}.full_name")
                                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-slate-600 font-medium mb-1">
                                                        NIK (16 Digit) <span class="text-rose-500">*</span>
                                                    </label>
                                                    <input type="text" name="participants[{{ $i }}][nik]"
                                                        id="participant_nik_{{ $i }}"
                                                        value="{{ $defaultNik }}"
                                                        required minlength="16" maxlength="16" pattern="[0-9]{16}"
                                                        class="w-full px-3 py-2 border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary rounded-lg bg-white text-body-strong font-medium transition"
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

                        <!-- Informasi Saluran Pembayaran Resmi Midtrans -->
                        <div class="space-y-3 pt-3 border-t border-hairline text-xs">
                            <div class="flex items-center justify-between">
                                <label class="block font-bold text-ink-heading">Metode Pembayaran</label>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Midtrans Verified</span>
                            </div>

                            <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-xl space-y-2.5">
                                <p class="text-[11px] text-muted leading-relaxed">
                                    Didukung oleh saluran pembayaran resmi <strong>Midtrans Sandbox</strong>:
                                </p>
                                <div class="flex flex-wrap gap-1.5 text-[10px] font-semibold text-slate-700">
                                    <span class="bg-white px-2 py-1 rounded border border-slate-200">QRIS (GoPay, OVO, Dana)</span>
                                    <span class="bg-white px-2 py-1 rounded border border-slate-200">BCA VA</span>
                                    <span class="bg-white px-2 py-1 rounded border border-slate-200">Mandiri Bill</span>
                                    <span class="bg-white px-2 py-1 rounded border border-slate-200">BNI &amp; BRI VA</span>
                                    <span class="bg-white px-2 py-1 rounded border border-slate-200">Kartu Kredit / Debit</span>
                                </div>
                                <p class="text-[10px] text-muted-soft italic">
                                    *Pilihan metode pembayaran lengkap akan langsung tampil di jendela aman Midtrans.
                                </p>
                            </div>

                            <!-- Checkbox Persetujuan Syarat & Ketentuan -->
                            <label class="flex items-start gap-2.5 pt-1 cursor-pointer select-none">
                                <input type="checkbox" required checked class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary focus:ring-offset-0 accent-primary cursor-pointer mt-0.5 transition-colors">
                                <span class="text-[11px] text-muted leading-tight">Saya menyetujui <a href="#" class="text-primary font-medium underline hover:text-primary-hover">Syarat &amp; Ketentuan</a> serta kebijakan ekspedisi MiddleTrip.</span>
                            </label>
                        </div>

                        <button type="submit" id="pay-button" class="w-full bg-primary hover:bg-primary-hover active:scale-[0.99] text-white py-3.5 px-4 rounded-xl font-bold text-xs sm:text-sm shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <svg id="pay-button-spinner" class="hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="pay-button-text">Bayar Booking Fee Sekarang (Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }})</span>
                        </button>
                    </div>
                </div>

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

        // Handler Midtrans Snap Popup
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('payment-form');
            const payBtn = document.getElementById('pay-button');
            const payBtnText = document.getElementById('pay-button-text');
            const payBtnSpinner = document.getElementById('pay-button-spinner');

            if (!form || !payBtn) return;

            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                payBtn.disabled = true;
                payBtn.classList.add('opacity-75', 'cursor-not-allowed');
                if (payBtnSpinner) payBtnSpinner.classList.remove('hidden');
                if (payBtnText) payBtnText.textContent = 'Menyiapkan Pembayaran...';

                try {
                    const formData = new FormData(form);
                    const response = await fetch("{{ route('checkout.pay_dp', $booking->booking_code) }}", {
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
                            alert('Gagal memuat modul pembayaran Midtrans. Mengalihkan ke halaman pembayaran...');
                            window.location.href = data.redirect_url;
                            return;
                        }

                        window.snap.pay(data.snap_token, {
                            onSuccess: function (result) {
                                window.location.href = "{{ route('checkout.status', $booking->booking_code) }}";
                            },
                            onPending: function (result) {
                                window.location.href = "{{ route('checkout.status', $booking->booking_code) }}";
                            },
                            onError: function (result) {
                                alert('Pembayaran gagal atau dibatalkan. Silakan coba kembali.');
                                resetPayButton();
                            },
                            onClose: function () {
                                resetPayButton();
                            }
                        });
                    } else {
                        alert(data.message || 'Gagal memproses tiket pembayaran.');
                        resetPayButton();
                    }
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan jaringan atau server. Silakan coba kembali.');
                    resetPayButton();
                }
            });

            function resetPayButton() {
                payBtn.disabled = false;
                payBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                if (payBtnSpinner) payBtnSpinner.classList.add('hidden');
                if (payBtnText) payBtnText.textContent = 'Bayar Booking Fee Sekarang (Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }})';
            }
        });
    </script>
</body>
</html>
