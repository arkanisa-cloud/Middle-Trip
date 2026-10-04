<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Private Trip - MiddleTrip</title>
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
            <a href="{{ route('ekspedisi.show', $booking->mountain?->slug ?? ($booking->expedition?->mountain?->slug ?? $booking->route?->mountain?->slug)) }}"
                class="inline-flex items-center text-xs text-muted hover:text-ink transition">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Detail Expedition
            </a>
        </div>

        <!-- Stepper (Private Trip: Data -> Bayar Langsung 100% -> Sukses) -->
        <div class="w-full max-w-xs sm:max-w-sm mx-auto mb-6 sm:mb-8 px-2">
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

                <div class="w-10 sm:w-16 md:w-20 h-[2px] bg-primary -mt-3.5 mx-1 sm:mx-2"></div>

                <!-- Step 2: PEMBAYARAN LANGSUNG 100% (Active) -->
                <div class="flex flex-col items-center">
                    <div
                        class="w-5 h-5 rounded-full bg-primary ring-2 ring-primary/20 flex items-center justify-center text-white shrink-0">
                        <span class="text-[10px] font-bold">2</span>
                    </div>
                    <span
                        class="text-[8px] sm:text-[9px] uppercase tracking-wider text-primary font-bold mt-1 text-center">Bayar
                        100%</span>
                </div>

                <div class="w-10 sm:w-16 md:w-20 h-[2px] border-t-2 border-dotted border-gray-300 -mt-3.5 mx-1 sm:mx-2">
                </div>

                <!-- Step 3: SELESAI -->
                <div class="flex flex-col items-center">
                    <div
                        class="w-5 h-5 rounded-full bg-neutral-200 flex items-center justify-center text-neutral-400 shrink-0">
                        <span class="text-[10px] font-bold">3</span>
                    </div>
                    <span
                        class="text-[8px] sm:text-[9px] uppercase tracking-wider text-neutral-400 font-bold mt-1 text-center">Selesai</span>
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

        <form action="{{ route('checkout.pay_private', $booking->booking_code) }}" method="POST" id="payment-form">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 items-start">

                <!-- Left Column (7 cols) -->
                <div class="lg:col-span-7 space-y-4">

                    <!-- Card: Banner Private Trip -->
                    <div class="relative overflow-hidden bg-white rounded-2xl border border-hairline shadow-sm">
                        <div
                            class="relative bg-gradient-to-b from-primary-50/60 via-surface-card to-white p-5 sm:p-6 pb-5">
                            <svg class="absolute right-0 bottom-0 w-72 h-36 opacity-10 pointer-events-none text-primary"
                                viewBox="0 0 400 200" fill="currentColor">
                                <polygon points="50,200 170,60 220,130 300,30 420,200" />
                            </svg>

                            <div class="mb-2 flex items-center gap-2">
                                <span
                                    class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-primary text-white tracking-wide uppercase">
                                    PRIVATE TRIP
                                </span>
                                <span
                                    class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 tracking-wide">
                                    Tanpa DP (Langsung Lunas 100%)
                                </span>
                                <span
                                    class="text-[11px] font-mono font-medium text-muted">#{{ $booking->booking_code }}</span>
                            </div>

                            <h1 class="text-xl sm:text-2xl font-extrabold text-ink-heading tracking-tight">
                                {{ $booking->mountain?->name ?? ($booking->expedition?->mountain?->name ?? $booking->route?->mountain?->name) }}
                            </h1>
                            <p class="text-xs text-muted font-medium mt-0.5 mb-4">
                                {{ $booking->mountain?->duration_days ?? ($booking->expedition?->mountain?->duration_days ?? 2) }}D{{ $booking->mountain?->duration_nights ?? ($booking->expedition?->mountain?->duration_nights ?? 1) }}N
                                • Keberangkatan:
                                {{ \Carbon\Carbon::parse($booking->departure_date ?? $booking->expedition?->departure_date)->translatedFormat('d F Y') }}
                            </p>

                        </div>
                    </div>

                    <!-- Card: Informasi Trip -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h2 class="text-sm font-bold text-ink-heading">Informasi Trip</h2>
                        </div>

                        <div class="grid grid-cols-2 gap-y-3.5 text-xs">
                            <div>
                                <p class="text-muted text-[11px] font-medium">Destinasi</p>
                                <p class="text-ink font-semibold mt-0.5">
                                    {{ $booking->mountain?->name ?? ($booking->expedition?->mountain?->name ?? $booking->route?->mountain?->name) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-muted text-[11px] font-medium">Rute</p>
                                <p class="text-ink font-semibold mt-0.5">Via {{ $booking->route->name ?? 'Standar' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-muted text-[11px] font-medium">Meeting Point</p>
                                <p class="text-ink font-semibold mt-0.5">
                                    {{ $booking->meetingPoint->name ?? 'Basecamp Resmi' }}</p>
                            </div>
                            <div>
                                <p class="text-muted text-[11px] font-medium">Keberangkatan</p>
                                <p class="text-ink font-semibold mt-0.5">
                                    {{ \Carbon\Carbon::parse($booking->departure_date ?? $booking->expedition?->departure_date)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Data Pemesan (Kontak Utama) -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <div class="text-primary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h2 class="text-sm font-bold text-ink-heading">Data Pemesan (Koordinator)</h2>
                            </div>
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

                    <!-- Card: Data Seluruh Peserta Rombongan (Diisi Manual per Tiket) -->
                    <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 mb-4">
                            <div class="flex items-start sm:items-center gap-2">
                                <div class="text-primary mt-0.5 sm:mt-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-sm font-bold text-ink-heading">Daftar Anggota Rombongan
                                        ({{ $booking->pax_count }} Orang)</h2>
                                    <p class="text-[11px] text-muted">Seluruh peserta wajib melengkapi identitas resmi
                                        untuk penerbitan izin simaksi pendakian.</p>
                                </div>
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

                    <!-- Card 5: Pilih Metode Pembayaran (Dropdown Accordion Sesuai payment_open_trip_1.html) -->
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
                                    onclick="toggleAccordion('content-va-pvt', 'icon-va-pvt')">
                                    <span class="font-bold">Virtual Account (Transfer Bank)</span>
                                    <svg id="icon-va-pvt"
                                        class="w-4 h-4 text-neutral-500 transform transition-transform duration-200 rotate-180"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div id="content-va-pvt"
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
                                    onclick="toggleAccordion('content-ewallet-pvt', 'icon-ewallet-pvt')">
                                    <span class="font-bold">E-Wallet &amp; QRIS</span>
                                    <svg id="icon-ewallet-pvt"
                                        class="w-4 h-4 text-neutral-500 transform transition-transform duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="content-ewallet-pvt"
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
                                    onclick="toggleAccordion('content-cc-pvt', 'icon-cc-pvt')">
                                    <span class="font-bold">Credit / Debit Card</span>
                                    <svg id="icon-cc-pvt"
                                        class="w-4 h-4 text-neutral-500 transform transition-transform duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="content-cc-pvt"
                                    class="hidden p-3 text-xs bg-white border-t border-[#ECEAE4]">
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
                                    onclick="toggleAccordion('content-paylater-pvt', 'icon-paylater-pvt')">
                                    <span class="font-bold">Pay Later</span>
                                    <svg id="icon-paylater-pvt"
                                        class="w-4 h-4 text-neutral-500 transform transition-transform duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="content-paylater-pvt"
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

                <!-- Right Column (5 cols) -->
                <div class="lg:col-span-5">
                    <div class="sticky top-20">
                        <div class="bg-white rounded-2xl border border-hairline p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-sm font-bold text-ink-heading">Pembayaran Penuh (100%)</h2>
                                <span
                                    class="text-[10px] font-bold text-primary bg-primary-50 px-2 py-0.5 rounded-full">Private
                                    Trip</span>
                            </div>

                            <!-- Price Breakdown -->
                            <div class="space-y-2.5 text-xs pb-4">
                                <div class="flex justify-between text-muted">
                                    <span>Tiket Trip ({{ $booking->pax_count }} pax @ Rp
                                        {{ number_format($booking->locked_price_per_pax ?? $booking->grand_total / max(1, $booking->pax_count), 0, ',', '.') }})</span>
                                    <span class="font-semibold text-ink">Rp
                                        {{ number_format(($booking->locked_price_per_pax ?? $booking->grand_total / max(1, $booking->pax_count)) * $booking->pax_count, 0, ',', '.') }}</span>
                                </div>

                                @if (($booking->shuttle_fee_total ?? 0) > 0)
                                    <div class="flex justify-between text-muted">
                                        <span>Shuttle Meeting Point</span>
                                        <span class="font-semibold text-ink">Rp
                                            {{ number_format($booking->shuttle_fee_total, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                @if (($booking->addons_fee_total ?? 0) > 0)
                                    <div class="flex justify-between text-muted">
                                        <span>Sewa Alat &amp; Add-on</span>
                                        <span class="font-semibold text-ink">Rp
                                            {{ number_format($booking->addons_fee_total, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                <div
                                    class="flex justify-between text-sm font-bold text-ink-heading pt-2 border-t border-hairline">
                                    <span>Total Pembayaran Langsung</span>
                                    <span class="text-primary font-extrabold text-base">Rp
                                        {{ number_format($booking->grand_total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Checkbox Persetujuan Syarat & Ketentuan -->
                            <label class="flex items-start gap-2.5 pt-1 cursor-pointer select-none">
                                <input type="checkbox" required checked
                                    class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary focus:ring-offset-0 accent-primary cursor-pointer mt-0.5 transition-colors">
                                <span class="text-[11px] text-muted leading-tight">Saya menyetujui <a href="#"
                                        class="text-primary font-medium underline hover:text-primary-hover">Syarat
                                        &amp; Ketentuan</a> serta kebijakan ekspedisi MiddleTrip.</span>
                            </label>
                        </div>

                        <button type="submit" id="pay-button"
                            class="w-full mt-4 bg-primary hover:bg-primary-hover active:scale-[0.99] text-white py-3.5 px-4 rounded-xl font-bold text-xs sm:text-sm shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <svg id="pay-button-spinner" class="hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span id="pay-button-text">Bayar Penuh Sekarang (Rp
                                {{ number_format($booking->grand_total, 0, ',', '.') }})</span>
                        </button>

                        <!-- Tombol Batalkan Pesanan Private Trip -->
                        <button type="button" onclick="confirmCancelBooking()"
                            class="w-full mt-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 py-2.5 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Batalkan Pesanan Ini</span>
                        </button>

                        <p class="text-[10px] text-center text-muted mt-4 leading-relaxed">
                            Setelah pembayaran selesai, slot private trip Anda akan langsung berstatus
                            <strong>Lunas</strong> dan Anda akan mendapatkan tautan WhatsApp grup koordinasi
                            rombongan.
                        </p>
                    </div>
                </div>
            </div>

            </div>
        </form>
    </main>

    <script>
        function confirmCancelBooking() {
            if (typeof Swal === 'undefined') {
                if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                    submitCancel();
                }
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Batalkan Reservasi Private Trip?',
                text: 'Apakah Anda yakin ingin membatalkan pesanan #{{ $booking->booking_code }} ini?',
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
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('payment-form');
            const payBtn = document.getElementById('pay-button');
            const payBtnText = document.getElementById('pay-button-text');
            const payBtnSpinner = document.getElementById('pay-button-spinner');

            if (!form || !payBtn) return;

            form.addEventListener('submit', async function(e) {
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
                    const response = await fetch(
                        "{{ route('checkout.pay_private', $booking->booking_code) }}", {
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
                                    "{{ route('checkout.success', $booking->booking_code) }}";
                            },
                            onPending: function(result) {
                                alert(
                                    'Tagihan pembayaran telah dibuat. Silakan selesaikan pembayaran sesuai petunjuk yang diberikan.'
                                );
                                resetPayButton();
                            },
                            onError: function(result) {
                                alert(
                                    'Pembayaran gagal atau dibatalkan. Silakan coba kembali.'
                                );
                                resetPayButton();
                            },
                            onClose: function() {
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
                if (payBtnText) payBtnText.textContent =
                    'Bayar Penuh Sekarang (Rp {{ number_format($booking->grand_total, 0, ',', '.') }})';
            }
        });
    </script>

    <!-- Footer -->
    <footer class="w-full border-t border-hairline mt-12 py-5 text-xs text-muted">
        <div
            class="max-w-6xl mx-auto px-4 sm:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
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
