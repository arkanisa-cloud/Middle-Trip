@extends('layouts.admin')

@section('title', 'Detail Reservasi — ' . $booking->booking_code)
@section('header_title', 'Detail Reservasi & Manifes SIMAKSI')
@section('header_subtitle', 'Kode Pesanan: ' . $booking->booking_code)

@section('content')
<div class="space-y-6">

    <!-- Page Header with Back Action and Status Update -->
    <x-admin.page-header 
        :title="'Booking #' . $booking->booking_code"
        :subtitle="'Dibuat pada ' . $booking->created_at->format('d F Y, H:i') . ' WIB • Status: ' . strtoupper($booking->status)"
        :backUrl="route('admin.bookings.index')"
        backLabel="Kembali ke Daftar Booking"
    >
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2.5">
                <x-admin.status-badge :status="$booking->status" />

                <!-- Cetak PDF / Invoice Button -->
                <a href="{{ route('bookings.print', $booking->booking_code) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full bg-white border border-hairline hover:bg-slate-50 text-xs font-bold text-ink shadow-xs transition-colors">
                    <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak E-Tiket / PDF</span>
                </a>

                <!-- Manual Status Update Form (UI Kit Dropdown Varian 3 / Status Dot) -->
                @php
                    $statusOptions = [
                        'open' => ['label' => 'Open (Belum DP)', 'dot' => 'bg-amber-500', 'text' => 'text-amber-700'],
                        'reserved' => ['label' => 'Reserved (DP Lunas)', 'dot' => 'bg-primary', 'text' => 'text-primary'],
                        'price_locked' => ['label' => 'Price Locked', 'dot' => 'bg-indigo-600', 'text' => 'text-indigo-700'],
                        'paid' => ['label' => 'Lunas (Paid)', 'dot' => 'bg-emerald-600', 'text' => 'text-emerald-700'],
                        'expired' => ['label' => 'Expired', 'dot' => 'bg-gray-400', 'text' => 'text-gray-600'],
                        'cancelled' => ['label' => 'Dibatalkan', 'dot' => 'bg-rose-500', 'text' => 'text-rose-700'],
                    ];
                    $currentStatusData = $statusOptions[$booking->status] ?? ['label' => ucfirst($booking->status), 'dot' => 'bg-gray-400', 'text' => 'text-ink'];
                @endphp

                <form method="POST" action="{{ route('admin.bookings.update_status', $booking->id) }}" 
                      data-confirm="Apakah Anda yakin ingin memperbarui status pemesanan #{{ $booking->booking_code }}?"
                      data-title="Perbarui Status Booking"
                      data-confirm-text="Ya, Update Status"
                      class="confirm-action flex items-center gap-2">
                    @csrf
                    @method('PATCH')

                    <div x-data="{ 
                        open: false, 
                        status: '{{ $booking->status }}', 
                        statusLabel: '{{ $currentStatusData['label'] }}',
                        statusDot: '{{ $currentStatusData['dot'] }}'
                    }" @click.outside="open = false" class="relative">
                        <input type="hidden" name="status" :value="status">
                        
                        <button type="button" @click="open = !open"
                                class="bg-surface-card hover:bg-gray-50 text-xs rounded-full border border-hairline py-2 px-3.5 text-ink flex items-center gap-2 font-bold shadow-xs transition select-none cursor-pointer">
                            <span class="w-2 h-2 rounded-full shrink-0" :class="statusDot"></span>
                            <span x-text="statusLabel" class="block truncate max-w-[150px]"></span>
                            <svg class="w-3.5 h-3.5 text-muted transition-transform duration-200 shrink-0" 
                                 :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-56 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-50 text-xs">
                            <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider border-b border-hairline/60 mb-1">
                                Status Reservasi
                            </div>
                            @foreach ($statusOptions as $val => $opt)
                                <button type="button" 
                                        @click="status = '{{ $val }}'; statusLabel = '{{ $opt['label'] }}'; statusDot = '{{ $opt['dot'] }}'; open = false;"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                        :class="status === '{{ $val }}' ? 'bg-primary-subtle/40 font-bold' : ''">
                                    <span class="{{ $opt['text'] }}">{{ $opt['label'] }}</span>
                                    <span class="w-2 h-2 rounded-full {{ $opt['dot'] }}"></span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="px-4 py-2 rounded-full bg-surface-forest text-white text-xs font-bold hover:bg-surface-forest-card transition-colors shrink-0 shadow-xs">
                        Update
                    </button>
                </form>
            </div>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Main Grid: Left Details & Right Financial Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Customer & SIMAKSI Manifest (Span 2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- 1. Data Pemesan / Ketua -->
            <div class="bg-surface-card border border-hairline rounded-3xl p-6 shadow-xs space-y-4">
                <div class="border-b border-hairline pb-3 flex items-center justify-between">
                    <h3 class="text-base font-extrabold font-outfit text-ink-heading">Data Kontak Pemesan</h3>
                    <span class="text-xs font-bold uppercase tracking-wider font-outfit text-primary">{{ $booking->trip_type }} TRIP</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-muted block text-[11px]">Nama Lengkap</span>
                        <span class="font-bold text-ink-heading text-sm">{{ $booking->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-muted block text-[11px]">Nomor WhatsApp</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->customer_phone) }}" target="_blank" 
                           class="font-bold text-emerald-700 hover:underline inline-flex items-center gap-1">
                            <span>{{ $booking->customer_phone }}</span>
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12c0 2.17.7 4.19 1.9 5.86L2.6 21.4l3.65-1.2A9.94 9.94 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
                        </a>
                    </div>
                    <div>
                        <span class="text-muted block text-[11px]">Alamat Email</span>
                        <span class="font-medium text-ink">{{ $booking->customer_email }}</span>
                    </div>
                    <div>
                        <span class="text-muted block text-[11px]">NIK KTP Pemesan</span>
                        <span class="font-mono font-bold text-ink">{{ $booking->customer_nik }}</span>
                    </div>
                    @if($booking->notes)
                        <div class="sm:col-span-2 bg-canvas p-3 rounded-xl border border-hairline">
                            <span class="text-muted block text-[11px] font-bold">Catatan Khusus dari Pemesan:</span>
                            <p class="text-ink mt-0.5">{{ $booking->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. Manifes Peserta Resmi (SIMAKSI) -->
            <div class="bg-surface-card border border-hairline rounded-3xl p-6 shadow-xs space-y-4">
                <div class="border-b border-hairline pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold font-outfit text-ink-heading">Manifes Peserta Pendakian (SIMAKSI)</h3>
                        <p class="text-xs text-muted">Daftar identitas wajib untuk registrasi perizinan Balai Taman Nasional dan Asuransi</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold font-outfit bg-primary-subtle text-primary">
                        {{ $booking->participants->count() }} Orang
                    </span>
                </div>

                @if($booking->participants->isEmpty())
                    <div class="p-6 text-center text-muted text-xs">
                        Belum ada data anggota tim terdaftar.
                    </div>
                @else
                    <div class="overflow-x-auto -mx-6 px-6">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-canvas border-y border-hairline text-muted font-semibold font-outfit text-xs">
                                <tr>
                                    <th class="py-3 px-4">No</th>
                                    <th class="py-3 px-4">Nama Lengkap (KTP)</th>
                                    <th class="py-3 px-4">Nomor Induk Kependudukan (NIK)</th>
                                    <th class="py-3 px-4">Gender</th>
                                    <th class="py-3 px-4">Peran</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-hairline">
                                @foreach($booking->participants as $index => $part)
                                    <tr class="hover:bg-canvas/50">
                                        <td class="py-3 px-4 font-bold text-muted">{{ $index + 1 }}</td>
                                        <td class="py-3 px-4 font-bold text-ink-heading">{{ $part->full_name }}</td>
                                        <td class="py-3 px-4 font-mono font-semibold text-ink">{{ $part->nik }}</td>
                                        <td class="py-3 px-4 text-muted">{{ $part->gender == 'L' ? 'Laki-laki' : ($part->gender == 'P' ? 'Perempuan' : '-') }}</td>
                                        <td class="py-3 px-4">
                                            @if($part->is_leader)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold font-outfit bg-amber-50 text-amber-800 border border-amber-200">
                                                    Ketua Rombongan
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium font-outfit bg-canvas text-muted border border-hairline">
                                                    Anggota
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- 3. Titik Penjemputan Shuttle & Addons Sewa -->
            <div class="bg-surface-card border border-hairline rounded-3xl p-6 shadow-xs space-y-4">
                <h3 class="text-base font-extrabold font-outfit text-ink-heading border-b border-hairline pb-3">Layanan Tambahan & Shuttle</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <!-- Shuttle Box -->
                    <div class="p-4 rounded-2xl bg-canvas border border-hairline">
                        <span class="text-muted block text-[11px] font-bold font-outfit uppercase tracking-wider mb-1">Titik Kumpul Shuttle</span>
                        @if($booking->meetingPoint)
                            <div class="font-bold text-sm text-ink-heading">{{ $booking->meetingPoint->name }}</div>
                            <div class="text-[11px] text-muted capitalize">{{ $booking->meetingPoint->location_type }}</div>
                            <div class="text-[11px] font-semibold text-emerald-700 mt-1">
                                + Rp {{ number_format($booking->shuttle_fee_total, 0, ',', '.') }} ({{ $booking->pax_count }} pax)
                            </div>
                        @else
                            <div class="text-muted italic">Langsung di Basecamp (Gratis)</div>
                        @endif
                    </div>

                    <!-- Add-ons Box -->
                    <div class="p-4 rounded-2xl bg-canvas border border-hairline">
                        <span class="text-muted block text-[11px] font-bold font-outfit uppercase tracking-wider mb-1">Perlengkapan Sewa (Add-ons)</span>
                        @if($booking->addons->isEmpty())
                            <div class="text-muted italic">Tidak menyewa perlengkapan tambahan.</div>
                        @else
                            <ul class="space-y-1">
                                @foreach($booking->addons as $addon)
                                    <li class="flex items-center justify-between text-[11px]">
                                        <span class="font-semibold text-ink-heading">{{ $addon->name }} &times; {{ $addon->pivot->quantity }}</span>
                                        <span class="font-mono text-muted">Rp {{ number_format($addon->pivot->price * $addon->pivot->quantity, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="pt-2 mt-2 border-t border-hairline flex justify-between font-bold text-xs text-primary">
                                <span>Total Sewa Alat:</span>
                                <span>Rp {{ number_format($booking->addons_fee_total, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Financial Summary & Transaction Audit (Span 1) -->
        <div class="space-y-6">
            
            <!-- Financial Card -->
            <div class="bg-surface-card border border-hairline rounded-3xl p-6 shadow-xs space-y-4">
                <h3 class="text-base font-extrabold font-outfit text-ink-heading border-b border-hairline pb-3">Rincian Finansial Pesanan</h3>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-muted">DP Awal (Booking Fee)</span>
                        <span class="font-bold text-ink-heading">Rp {{ number_format($booking->total_booking_fee, 0, ',', '.') }}</span>
                    </div>

                    @if($booking->locked_price_per_pax)
                        <div class="flex items-center justify-between">
                            <span class="text-muted">Tarif Price Lock / Pax</span>
                            <span class="font-bold text-emerald-700">Rp {{ number_format($booking->locked_price_per_pax, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <span class="text-muted">Total Shuttle</span>
                        <span class="font-semibold text-ink">Rp {{ number_format($booking->shuttle_fee_total, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-muted">Total Sewa Alat</span>
                        <span class="font-semibold text-ink">Rp {{ number_format($booking->addons_fee_total, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-3 border-t border-hairline flex items-center justify-between text-sm">
                        <span class="font-extrabold font-outfit text-ink-heading">Grand Total</span>
                        <span class="font-extrabold text-primary font-outfit text-lg">
                            Rp {{ number_format($booking->grand_total, 0, ',', '.') }}
                        </span>
                    </div>

                    @if($booking->remaining_payment_total && $booking->status !== 'paid')
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 mt-2">
                            <span class="text-[11px] font-bold text-amber-900 block font-outfit">Sisa Pelunasan Wajib:</span>
                            <span class="text-lg font-black text-amber-800 font-outfit block">
                                Rp {{ number_format($booking->remaining_payment_total, 0, ',', '.') }}
                            </span>
                            @if($booking->payment_deadline)
                                <span class="text-[10px] text-amber-700 mt-1 block">
                                    Batas Pelunasan: {{ $booking->payment_deadline->format('d M Y, H:i') }} WIB
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Transaction Audit Trail -->
            <div class="bg-surface-card border border-hairline rounded-3xl p-6 shadow-xs space-y-4">
                <h3 class="text-base font-extrabold font-outfit text-ink-heading border-b border-hairline pb-3">Riwayat Transaksi Masuk</h3>

                @if($booking->paymentTransactions->isEmpty())
                    <p class="text-xs text-muted text-center py-4">Belum ada catatan transaksi pembayaran terkonfirmasi.</p>
                @else
                    <div class="space-y-3">
                        @foreach($booking->paymentTransactions as $tx)
                            <div class="p-3 rounded-2xl bg-canvas border border-hairline text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono font-bold text-[11px] text-ink-heading">{{ $tx->transaction_code }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $tx->status === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600' }}">
                                        {{ strtoupper($tx->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-muted">
                                    <span>{{ $tx->payment_method }} ({{ str_replace('_', ' ', $tx->payment_stage) }})</span>
                                    <span class="font-bold text-ink">Rp {{ number_format($tx->amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-[10px] text-muted-soft">
                                    {{ $tx->paid_at ? \Carbon\Carbon::parse($tx->paid_at)->format('d M Y, H:i') . ' WIB' : '-' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
