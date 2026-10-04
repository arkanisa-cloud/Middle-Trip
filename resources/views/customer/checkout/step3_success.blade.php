<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Lunas - MiddleTrip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-emerald-700 font-semibold">Transaksi Terverifikasi</span>
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

        <!-- Stepper (All 4 Steps Completed) -->
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

                <!-- Step 2: RESERVASI (Done) -->
                <div class="flex flex-col items-center">
                    <div
                        class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span
                        class="text-[8px] sm:text-[9px] uppercase tracking-wider text-emerald-600 font-bold mt-1 text-center">Reservasi</span>
                </div>

                <div class="w-6 sm:w-12 md:w-16 h-[2px] bg-emerald-500 -mt-3.5 mx-1 sm:mx-2"></div>

                <!-- Step 3: PRICE LOCK (Done) -->
                <div class="flex flex-col items-center">
                    <div
                        class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span
                        class="text-[8px] sm:text-[9px] uppercase tracking-wider text-emerald-600 font-bold mt-1 text-center">Price
                        Lock</span>
                </div>

                <div class="w-6 sm:w-12 md:w-16 h-[2px] bg-emerald-500 -mt-3.5 mx-1 sm:mx-2"></div>

                <!-- Step 4: PAY (Done) -->
                <div class="flex flex-col items-center">
                    <div
                        class="w-5 h-5 rounded-full bg-emerald-600 ring-2 ring-emerald-200 flex items-center justify-center text-white shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span
                        class="text-[8px] sm:text-[9px] uppercase tracking-wider text-emerald-700 font-bold mt-1 text-center">Pay</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">

            <!-- Left Column (7 cols) -->
            <div class="lg:col-span-7 space-y-4">

                <!-- Card: Banner Berhasil -->
                <div class="relative overflow-hidden bg-white rounded-2xl border border-hairline shadow-sm">
                    <div
                        class="relative bg-gradient-to-b from-emerald-50/70 via-emerald-50/30 to-white p-5 sm:p-6 pb-5">
                        <svg class="absolute right-0 bottom-0 w-72 h-36 opacity-10 pointer-events-none text-emerald-900"
                            viewBox="0 0 400 200" fill="currentColor">
                            <polygon points="50,200 170,60 220,130 300,30 420,200" />
                        </svg>

                        <div class="mb-2 flex items-center gap-2">
                            <span
                                class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $booking->trip_type === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-primary-50 text-primary' }} tracking-wide uppercase">
                                {{ $booking->trip_type }} TRIP
                            </span>
                            <span
                                class="text-[11px] font-mono font-medium text-muted">#{{ $booking->booking_code }}</span>
                        </div>

                        <h1 class="text-xl sm:text-2xl font-extrabold text-ink-heading tracking-tight">
                            {{ $booking->expedition->mountain->name }}
                        </h1>
                        <p class="text-xs text-muted font-medium mt-0.5 mb-4">
                            {{ $booking->expedition->mountain->duration_days }}D{{ $booking->expedition->mountain->duration_nights }}N
                            •
                            {{ \Carbon\Carbon::parse($booking->departure_date ?? $booking->expedition->departure_date)->translatedFormat('d F Y') }}
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
                                <span class="text-xs font-bold text-ink-heading">Pembayaran Lunas</span>
                            </div>
                            <p class="text-xs text-muted-soft leading-relaxed">
                                Seluruh administrasi tiket telah terbayar penuh. Slot keberangkatan Anda telah
                                terkonfirmasi resmi.
                            </p>
                        </div>
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
                            <p class="text-ink font-semibold mt-0.5">{{ $booking->expedition->mountain->name }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[11px] font-medium">Rute</p>
                            <p class="text-ink font-semibold mt-0.5">Via {{ $booking->route->name ?? 'Standar' }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[11px] font-medium">Meeting Point</p>
                            <p class="text-ink font-semibold mt-0.5">
                                {{ $booking->meetingPoint->name ?? 'Basecamp Resmi' }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[11px] font-medium">Keberangkatan</p>
                            <p class="text-ink font-semibold mt-0.5">
                                {{ \Carbon\Carbon::parse($booking->departure_date ?? $booking->expedition->departure_date)->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card: Data Pemesan -->
                <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="text-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
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

                <!-- Card: Data Peserta -->
                <div class="bg-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h2 class="text-sm font-bold text-ink-heading">Daftar Peserta ({{ $booking->pax_count }}
                                Pax)</h2>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        @foreach ($booking->participants as $index => $participant)
                            @php
                                $isOpen = $index === 0;
                                $itemTitle = $participant->is_leader ? 'Ketua' : 'Anggota ' . $index;
                            @endphp
                            <div class="border border-[#ECEAE4] rounded-xl overflow-hidden shadow-2xs">
                                <button type="button"
                                    class="w-full flex items-center justify-between px-3.5 sm:px-4 py-3 {{ $isOpen ? 'bg-[#F9F8F6]' : 'bg-white hover:bg-neutral-50' }} text-xs font-semibold text-neutral-800 text-left transition-colors cursor-pointer"
                                    onclick="toggleAccordion('content-peserta-{{ $index }}', 'icon-peserta-{{ $index }}')">
                                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2.5">
                                        <span
                                            class="w-5 h-5 rounded-full {{ $participant->is_leader ? 'bg-primary text-white' : 'bg-slate-200 text-slate-700' }} flex items-center justify-center font-bold text-[10px] shrink-0">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="font-bold text-ink-heading">{{ $participant->full_name }}</span>
                                        <span
                                            class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $participant->is_leader ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $participant->is_leader ? 'Ketua (Leader)' : 'Anggota' }}
                                        </span>
                                    </div>
                                    <svg id="icon-peserta-{{ $index }}"
                                        class="w-4 h-4 text-neutral-500 transform transition-transform duration-200 shrink-0 ml-2 {{ $isOpen ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="content-peserta-{{ $index }}"
                                    class="{{ $isOpen ? '' : 'hidden' }} p-4 space-y-3 text-xs bg-white border-t border-[#ECEAE4]">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-slate-600 font-medium mb-1">Nama Lengkap
                                                {{ $itemTitle }}</label>
                                            <input type="text" value="{{ $participant->full_name }}" readonly
                                                class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-body-strong font-medium">
                                        </div>
                                        <div>
                                            <label class="block text-slate-600 font-medium mb-1">NIK (SIMAKSI &
                                                Asuransi)</label>
                                            <input type="text" value="{{ $participant->nik }}" readonly
                                                class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-body-strong font-medium font-mono">
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-1.5 text-[11px] text-emerald-700 font-semibold pt-0.5">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Data SIMAKSI Terverifikasi & Asuransi Aktif</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right Column (5 cols) -->
            <div class="lg:col-span-5">
                <div class="sticky top-6 space-y-4">
                    <div class="bg-white rounded-2xl border border-hairline p-6 shadow-sm">
                        <h2 class="text-sm font-bold text-ink-heading mb-4">Status & Bukti Tiket</h2>

                        <!-- Success Box -->
                        <div
                            class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 mb-5">
                            <div
                                class="w-7 h-7 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-emerald-900 leading-tight">Pembayaran Lunas!</h3>
                                <p class="text-[11px] text-emerald-700 leading-snug mt-0.5">Persiapkan dirimu untuk
                                    berpetualang!</p>
                            </div>
                        </div>

                        <!-- CTA Action Buttons -->
                        <div class="space-y-2.5">
                            <!-- CTA WhatsApp Group -->
                            <a href="{{ $booking->expedition->whatsapp_group_url ?? 'https://chat.whatsapp.com/demo-middletrip' }}"
                                target="_blank" rel="noopener noreferrer"
                                class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white text-xs font-bold rounded-xl transition-all duration-200 flex items-center justify-center gap-2.5 shadow-sm hover:shadow-md text-center group">
                                <svg class="w-4 h-4 fill-current group-hover:scale-110 transition-transform duration-200"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                </svg>
                                <span>Gabung Grup WhatsApp Koordinasi</span>
                            </a>

                            <!-- Cetak E-Tiket / Invoice (PDF) -->
                            <a href="{{ route('bookings.print', $booking->booking_code) }}" target="_blank"
                                class="w-full py-3 px-4 bg-white hover:bg-emerald-50/80 text-ink-heading hover:text-emerald-800 border-2 border-slate-200 hover:border-emerald-500 text-xs font-extrabold rounded-xl transition-all duration-200 flex items-center justify-center gap-2.5 shadow-2xs hover:shadow-md active:scale-[0.99] text-center group">
                                <svg class="w-4 h-4 text-emerald-600 group-hover:text-emerald-700 group-hover:scale-110 transition-all duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                <span>Cetak E-Tiket / Invoice (PDF)</span>
                            </a>
                        </div>

                        <div class="mt-5 pt-4 border-t border-hairline space-y-2 text-xs">
                            <div class="flex justify-between text-muted">
                                <span>Booking Fee (DP Terbayar)</span>
                                <span class="font-semibold text-ink">Rp
                                    {{ number_format($booking->total_booking_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-muted">
                                <span>Pelunasan (Settle Terbayar)</span>
                                <span class="font-semibold text-ink">Rp
                                    {{ number_format($booking->remaining_payment_total ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div
                                class="flex justify-between text-sm font-bold text-ink-heading pt-2 border-t border-hairline">
                                <span>Total Pembayaran</span>
                                <span class="text-primary font-extrabold">Rp
                                    {{ number_format($booking->total_booking_fee + ($booking->remaining_payment_total ?? 0), 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <p class="text-[10px] text-center text-muted mt-4 leading-relaxed">
                            Pantau terus pengumuman dan detail koordinasi keberangkatan di grup WhatsApp trip!
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </main>

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
    </script>
</body>

</html>
