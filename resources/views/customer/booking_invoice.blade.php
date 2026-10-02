<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket & Bukti Reservasi #{{ $booking->booking_code }} - MiddleTrip</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                color: #000000 !important;
            }
            .print-container {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            .page-break {
                page-break-after: always;
            }
            @page {
                size: A4 portrait;
                margin: 12mm 15mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen py-6 sm:py-10 px-4 sm:px-6 flex flex-col items-center">

    <!-- Top Action Bar (No-Print) -->
    <div class="max-w-4xl w-full flex items-center justify-between mb-4 no-print">
        <a href="{{ url()->previous() ?: route('profile.edit') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-xs transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali</span>
        </a>

        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Printable E-Ticket / Invoice Card -->
    <div class="print-container max-w-4xl w-full bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10 space-y-8">
        
        <!-- Header: Kop Dokumen & Status Badge -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-700 text-white flex items-center justify-center font-black text-2xl font-outfit shadow-sm">
                    M
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black font-outfit text-slate-900 tracking-tight leading-none">MiddleTrip</h1>
                    <p class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider mt-1">E-Tiket &amp; Invoice Resmi Ekspedisi</p>
                    <p class="text-[10px] text-slate-400">Website: middletrip.id • CS WhatsApp: +62 812-3456-7890</p>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <div class="inline-block">
                    @php
                        $statusBadgeClass = match($booking->status) {
                            'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                            'reserved' => 'bg-amber-100 text-amber-900 border-amber-300',
                            'price_locked' => 'bg-blue-100 text-blue-800 border-blue-300',
                            'open' => 'bg-slate-100 text-slate-800 border-slate-300',
                            'cancelled', 'expired' => 'bg-rose-100 text-rose-800 border-rose-300',
                            default => 'bg-slate-100 text-slate-800 border-slate-300'
                        };
                        $statusLabel = match($booking->status) {
                            'paid' => 'LUNAS (100% PAID)',
                            'reserved' => 'TERKONFIRMASI (DP LUNAS)',
                            'price_locked' => 'HARGA TERKUNCI',
                            'open' => 'MENUNGGU PEMBAYARAN',
                            'cancelled' => 'DIBATALKAN',
                            'expired' => 'KEDALUWARSA',
                            default => strtoupper($booking->status)
                        };
                    @endphp
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-black tracking-wider uppercase border {{ $statusBadgeClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>
                <div class="mt-2 text-xs font-mono font-bold text-slate-900">
                    KODE: <span class="text-emerald-800 text-sm">#{{ $booking->booking_code }}</span>
                </div>
                <div class="text-[11px] text-slate-400">
                    Diterbitkan: {{ $booking->created_at->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </div>
        </div>

        <!-- Section 1: Informasi Ekspedisi & Rute -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs">
            <div>
                <span class="text-slate-400 block text-[11px] uppercase font-bold tracking-wider mb-0.5">Destinasi Gunung</span>
                <span class="font-extrabold text-slate-900 text-sm block">
                    {{ $booking->mountain?->name ?? $booking->expedition?->mountain?->name ?? $booking->route?->mountain?->name }}
                </span>
                <span class="text-[11px] text-slate-500">
                    {{ $booking->mountain?->elevation ?? $booking->expedition?->mountain?->elevation }} mdpl • {{ $booking->mountain?->province ?? $booking->expedition?->mountain?->province }}
                </span>
            </div>

            <div>
                <span class="text-slate-400 block text-[11px] uppercase font-bold tracking-wider mb-0.5">Jalur & Jenis Trip</span>
                <span class="font-bold text-slate-900 block">Via {{ $booking->route->name ?? 'Jalur Standar' }}</span>
                <span class="text-[11px] font-semibold text-emerald-700 uppercase">
                    {{ $booking->trip_type }} TRIP ({{ $booking->pax_count }} Orang)
                </span>
            </div>

            <div>
                <span class="text-slate-400 block text-[11px] uppercase font-bold tracking-wider mb-0.5">Jadwal Keberangkatan</span>
                <span class="font-bold text-slate-900 block">
                    {{ \Carbon\Carbon::parse($booking->departure_date)->translatedFormat('d F Y') }}
                </span>
                <span class="text-[11px] text-slate-500">
                    Pulang: {{ \Carbon\Carbon::parse($booking->return_date)->translatedFormat('d F Y') }}
                </span>
            </div>

            <div>
                <span class="text-slate-400 block text-[11px] uppercase font-bold tracking-wider mb-0.5">Titik Kumpul Shuttle</span>
                <span class="font-bold text-slate-900 block">{{ $booking->meetingPoint->name ?? 'Basecamp Resmi' }}</span>
                <span class="text-[11px] text-slate-500">
                    {{ $booking->meetingPoint ? 'Shuttle Terpadu' : 'Langsung di Basecamp' }}
                </span>
            </div>
        </div>

        <!-- Section 2: Data Pemesan (Koordinator) -->
        <div class="space-y-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-1.5">
                1. Data Kontak Pemesan / Koordinator
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 block text-[11px]">Nama Lengkap</span>
                    <span class="font-bold text-slate-900">{{ $booking->customer_name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Nomor WhatsApp</span>
                    <span class="font-bold text-slate-900">{{ $booking->customer_phone }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Alamat Email</span>
                    <span class="font-medium text-slate-700">{{ $booking->customer_email }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">NIK KTP Pemesan</span>
                    <span class="font-mono font-bold text-slate-900">{{ $booking->customer_nik }}</span>
                </div>
            </div>
        </div>

        <!-- Section 3: Manifes Peserta Resmi (SIMAKSI) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                    2. Manifes Peserta Resmi (Izin SIMAKSI & Asuransi)
                </h2>
                <span class="text-xs font-bold text-slate-600">{{ $booking->participants->count() }} Anggota Tim</span>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 text-[11px]">
                        <tr>
                            <th class="py-2.5 px-4 w-12 text-center">No</th>
                            <th class="py-2.5 px-4">Nama Lengkap Sesuai KTP</th>
                            <th class="py-2.5 px-4">Nomor Induk Kependudukan (NIK)</th>
                            <th class="py-2.5 px-4 text-center">Peran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($booking->participants as $idx => $participant)
                            <tr class="{{ $idx % 2 === 0 ? 'bg-white' : 'bg-slate-50/50' }}">
                                <td class="py-2.5 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="py-2.5 px-4 font-bold text-slate-900">{{ $participant->full_name }}</td>
                                <td class="py-2.5 px-4 font-mono font-semibold text-slate-700">{{ $participant->nik }}</td>
                                <td class="py-2.5 px-4 text-center">
                                    @if($participant->is_leader)
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            Ketua Tim
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600">
                                            Anggota
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 4: Rincian Biaya & Pembayaran -->
        <div class="space-y-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-1.5">
                3. Rincian Biaya & Administrasi
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start text-xs">
                <!-- Perlengkapan Tambahan & Shuttle -->
                <div class="space-y-2.5 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                    <span class="font-bold text-slate-900 block text-xs">Layanan Tambahan:</span>
                    <ul class="space-y-1.5 text-slate-600">
                        <li class="flex justify-between">
                            <span>Shuttle Penjemputan ({{ $booking->meetingPoint->name ?? 'Basecamp' }}):</span>
                            <span class="font-mono font-semibold text-slate-900">Rp {{ number_format($booking->shuttle_fee_total, 0, ',', '.') }}</span>
                        </li>
                        @if($booking->addons->isNotEmpty())
                            @foreach($booking->addons as $ad)
                                <li class="flex justify-between">
                                    <span>{{ $ad->name }} &times; {{ $ad->pivot->quantity }}:</span>
                                    <span class="font-mono font-semibold text-slate-900">Rp {{ number_format($ad->pivot->price * $ad->pivot->quantity, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="text-slate-400 italic">Tidak ada sewa perlengkapan tambahan.</li>
                        @endif
                    </ul>
                </div>

                <!-- Financial Balance Sheet -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    @if($booking->trip_type === 'private')
                        <div class="flex justify-between text-slate-600">
                            <span>Paket Private ({{ $booking->pax_count }} Pax):</span>
                            <span class="font-mono font-bold text-slate-900">Rp {{ number_format($booking->locked_price_per_pax * $booking->pax_count, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <div class="flex justify-between text-slate-600">
                            <span>Uang Muka (DP Booking Fee):</span>
                            <span class="font-mono font-bold text-slate-900">Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }}</span>
                        </div>
                        @if($booking->locked_price_per_pax)
                            <div class="flex justify-between text-emerald-800 font-medium">
                                <span>Tarif Price Lock Akhir:</span>
                                <span class="font-mono font-bold">Rp {{ number_format($booking->locked_price_per_pax * $booking->pax_count, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    @endif

                    <div class="flex justify-between text-slate-600">
                        <span>Total Layanan Shuttle & Sewa:</span>
                        <span class="font-mono font-bold text-slate-900">Rp {{ number_format($booking->shuttle_fee_total + $booking->addons_fee_total, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-2 border-t border-slate-200 flex justify-between text-sm font-extrabold text-slate-900">
                        <span>Grand Total:</span>
                        <span class="text-emerald-700 font-mono text-base">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</span>
                    </div>

                    @if($booking->remaining_payment_total && $booking->status !== 'paid')
                        <div class="p-2.5 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 font-bold flex justify-between items-center text-xs mt-2">
                            <span>Sisa Pelunasan Wajib:</span>
                            <span class="text-amber-800 font-mono text-sm">Rp {{ number_format($booking->remaining_payment_total, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section 5: Catatan SOP & Petunjuk Lapangan -->
        <div class="pt-4 border-t border-slate-200 text-[11px] text-slate-500 space-y-1.5 leading-relaxed">
            <h3 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Ketentuan &amp; SOP Lapangan MiddleTrip:</h3>
            <ol class="list-decimal list-inside space-y-1">
                <li>Tunjukkan dokumen e-tiket ini (fisik/digital) bersama <b>KTP Asli</b> saat registrasi ulang di titik kumpul shuttle atau basecamp.</li>
                <li>Seluruh peserta wajib dalam kondisi sehat jasmani dan membawa perlengkapan standar pendakian (sepatu gunung, jaket hangat, headlamp, jas hujan).</li>
                <li>Uang muka (DP) bersifat non-refundable apabila terjadi pembatalan sepihak dari pihak pendaki.</li>
                <li>Koordinasi grup dan instruksi logistik lapangan dapat diakses melalui WhatsApp koordinator MiddleTrip.</li>
            </ol>
        </div>

        <!-- Document Footer & Barcode Simulation -->
        <div class="border-t border-slate-200 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-[10px] text-slate-400 text-center sm:text-left">
                <p>Dokumen ini adalah bukti reservasi elektronik sah yang diterbitkan oleh sistem otomatis MiddleTrip.</p>
                <p>Hak Cipta &copy; {{ date('Y') }} MiddleTrip Expedition Platform. All rights reserved.</p>
            </div>

            <!-- Simulated Barcode -->
            <div class="flex flex-col items-center sm:items-end">
                <div class="font-mono text-[9px] text-slate-400 tracking-widest uppercase">E-TICKET SECURE PASS</div>
                <div class="h-8 flex items-center gap-[2px] py-1">
                    @for($i = 0; $i < 38; $i++)
                        <div class="h-full bg-slate-800" style="width: {{ ($i % 3 == 0) ? '3px' : (($i % 2 == 0) ? '1.5px' : '1px') }};"></div>
                    @endfor
                </div>
                <div class="font-mono text-[10px] font-bold text-slate-700 tracking-widest">{{ $booking->booking_code }}</div>
            </div>
        </div>

    </div>

</body>
</html>
