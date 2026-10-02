@extends('layouts.admin')

@section('title', 'Manajemen Reservasi & SIMAKSI')
@section('header_title', 'Manajemen Reservasi & Manifes SIMAKSI')
@section('header_subtitle', 'Pantau data pendaftaran, kelengkapan identitas peserta untuk izin SIMAKSI, dan verifikasi status pembayaran')

@section('content')
<div class="space-y-6">

    <!-- Page Header Banner -->
    <x-admin.page-header 
        title="Manajemen Reservasi & SIMAKSI" 
        subtitle="Pantau data pendaftaran, kelengkapan identitas peserta untuk izin SIMAKSI, dan verifikasi status pembayaran."
    />

    <!-- Status Filter Tabs & Search Bar -->
    <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
        <!-- Status Pills with Counts -->
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1">
            <a href="{{ route('admin.bookings.index') }}" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors {{ empty($status) ? 'bg-primary text-white shadow-xs' : 'bg-surface-card border border-hairline text-ink hover:bg-canvas' }}">
                Semua ({{ $statusCounts['all'] }})
            </a>
            <a href="{{ route('admin.bookings.index', ['status' => 'open']) }}" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors {{ $status === 'open' ? 'bg-primary text-white shadow-xs' : 'bg-surface-card border border-hairline text-ink hover:bg-canvas' }}">
                Menunggu DP ({{ $statusCounts['open'] }})
            </a>
            <a href="{{ route('admin.bookings.index', ['status' => 'reserved']) }}" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors {{ $status === 'reserved' ? 'bg-primary text-white shadow-xs' : 'bg-surface-card border border-hairline text-ink hover:bg-canvas' }}">
                DP Terbayar ({{ $statusCounts['reserved'] }})
            </a>
            <a href="{{ route('admin.bookings.index', ['status' => 'price_locked']) }}" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors {{ $status === 'price_locked' ? 'bg-primary text-white shadow-xs' : 'bg-surface-card border border-hairline text-ink hover:bg-canvas' }}">
                Menunggu Pelunasan ({{ $statusCounts['price_locked'] }})
            </a>
            <a href="{{ route('admin.bookings.index', ['status' => 'paid']) }}" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors {{ $status === 'paid' ? 'bg-primary text-white shadow-xs' : 'bg-surface-card border border-hairline text-ink hover:bg-canvas' }}">
                Lunas ({{ $statusCounts['paid'] }})
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="relative max-w-sm w-full">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input type="text" 
                   name="search" 
                   value="{{ $search ?? '' }}" 
                   placeholder="Cari kode booking, nama, NIK, HP..." 
                   class="w-full pl-10 pr-4 py-2 text-xs rounded-full border border-hairline bg-surface-card text-ink placeholder-muted focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
            <svg class="w-4 h-4 text-muted absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </form>
    </div>

    <!-- Bookings Table Card -->
    <div class="bg-surface-card border border-hairline rounded-3xl shadow-xs overflow-hidden">
        @if($bookings->isEmpty())
            <div class="py-16 text-center text-muted">
                <svg class="w-12 h-12 mx-auto text-muted-soft mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-semibold text-ink-heading">Tidak ada data reservasi ditemukan.</p>
                <p class="text-xs text-muted mt-1">Coba gunakan filter lain atau kata kunci pencarian yang berbeda.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-canvas border-b border-hairline text-muted font-semibold font-outfit text-xs">
                        <tr>
                            <th class="py-3.5 px-5">Kode Booking</th>
                            <th class="py-3.5 px-5">Pemesan / Kontak</th>
                            <th class="py-3.5 px-5">Destinasi & Jadwal</th>
                            <th class="py-3.5 px-5">Peserta</th>
                            <th class="py-3.5 px-5">DP / Tagihan</th>
                            <th class="py-3.5 px-5">Status</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline">
                        @foreach($bookings as $b)
                            <tr class="hover:bg-canvas/50 transition-colors">
                                <!-- Booking Code -->
                                <td class="py-4 px-5">
                                    <span class="font-mono font-bold text-xs text-ink-heading block">
                                        {{ $b->booking_code }}
                                    </span>
                                    <span class="text-[10px] text-muted">
                                        {{ $b->created_at->format('d M Y, H:i') }} WIB
                                    </span>
                                </td>

                                <!-- Customer Info -->
                                <td class="py-4 px-5">
                                    <span class="font-bold text-ink-heading block">{{ $b->customer_name }}</span>
                                    <span class="text-[11px] text-muted block">{{ $b->customer_phone }}</span>
                                    <span class="text-[10px] font-mono text-muted-soft block">NIK: {{ $b->customer_nik }}</span>
                                </td>

                                <!-- Mountain & Departure Date -->
                                <td class="py-4 px-5">
                                    <span class="font-bold text-ink-heading block">
                                        {{ $b->expedition->mountain->name ?? '-' }}
                                    </span>
                                    <span class="text-[11px] text-muted block">
                                        {{ $b->route->name ?? '-' }} • {{ \Carbon\Carbon::parse($b->departure_date ?? $b->expedition->departure_date ?? now())->format('d M Y') }}
                                    </span>
                                </td>

                                <!-- Pax Count & Trip Type -->
                                <td class="py-4 px-5">
                                    <span class="font-bold text-ink-heading block">{{ $b->pax_count }} Orang</span>
                                    <span class="text-[10px] uppercase font-bold text-primary">
                                        {{ $b->trip_type }} TRIP
                                    </span>
                                </td>

                                <!-- Pricing Breakdown -->
                                <td class="py-4 px-5">
                                    <div class="space-y-0.5">
                                        <div class="text-[11px] text-muted">
                                            DP: <span class="font-semibold text-ink">Rp {{ number_format($b->total_booking_fee, 0, ',', '.') }}</span>
                                        </div>
                                        @if($b->remaining_payment_total)
                                            <div class="text-[11px] text-amber-800 font-semibold">
                                                Sisa: Rp {{ number_format($b->remaining_payment_total, 0, ',', '.') }}
                                            </div>
                                        @else
                                            <div class="text-[11px] text-muted font-normal">
                                                Total: Rp {{ number_format($b->grand_total, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-5">
                                    <x-admin.status-badge :status="$b->status" />
                                </td>

                                <!-- Action Button -->
                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.bookings.show', $b->id) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-hairline text-xs font-bold text-ink hover:bg-canvas hover:border-primary hover:text-primary transition-colors shadow-2xs">
                                        <span>Detail & Manifes</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="p-4 border-t border-hairline">
                    {{ $bookings->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
