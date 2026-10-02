@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Ringkasan Operasional')
@section('header_subtitle', 'Selamat datang di Panel Kontrol MiddleTrip Expedition')

@section('content')
<div class="space-y-8">
    
    <!-- Top Greeting Banner & Quick Actions -->
    <div class="bg-surface-card border border-hairline rounded-3xl p-6 md:p-8 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 shadow-xs relative overflow-hidden">
        <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold font-outfit uppercase tracking-wider bg-primary-subtle text-primary mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                MiddleTrip Operations
            </span>
            <h2 class="text-2xl md:text-3xl font-extrabold font-outfit text-ink-heading tracking-tight">
                Halo, {{ Auth::user()->name }}! 👋
            </h2>
            <p class="text-sm text-body mt-1 max-w-xl">
                Pantau kuota ekspedisi, jadwal penutupan pendaftaran (*Price Lock*), verifikasi manifes SIMAKSI, dan alur pembayaran dalam satu dasbor terpadu.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 relative z-10 w-full lg:w-auto">
            <a href="{{ route('admin.mountains.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-primary text-white text-xs font-bold font-outfit hover:bg-primary-hover transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Gunung</span>
            </a>
            <a href="{{ route('admin.expeditions.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-surface-forest text-white text-xs font-bold font-outfit hover:bg-surface-forest-card border border-surface-forest-border transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Buat Batch Trip</span>
            </a>
        </div>
    </div>

    <!-- 4 Key Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Metric 1: Total Mountains -->
        <x-admin.stat-card 
            title="Total Gunung" 
            :value="$metrics['total_mountains'] . ' Destinasi'" 
            subtitle="Master data gunung aktif"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8M3 21h18"/></svg>'
        />

        <!-- Metric 2: Active Expeditions -->
        <x-admin.stat-card 
            title="Batch Ekspedisi Aktif" 
            :value="$metrics['active_expeditions'] . ' Jadwal'" 
            subtitle="Open & Private Trip dibuka"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'
        />

        <!-- Metric 3: Total Bookings -->
        <x-admin.stat-card 
            title="Total Reservasi" 
            :value="$metrics['total_bookings'] . ' Pesanan'" 
            subtitle="Akumulasi seluruh booking"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'
        />

        <!-- Metric 4: Pending Settlement -->
        <x-admin.stat-card 
            title="Menunggu Pelunasan" 
            :value="$metrics['pending_settlement'] . ' Pesanan'" 
            subtitle="Fase Price Lock H-X"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        />
    </div>

    <!-- Main Grid: Recent Bookings & Upcoming Price Locks -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Recent Bookings Table (Span 2) -->
        <div class="lg:col-span-2 bg-surface-card border border-hairline rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-extrabold font-outfit text-ink-heading">Reservasi Terbaru</h3>
                        <p class="text-xs text-muted">Aktivitas booking dan status pembayaran terkini</p>
                    </div>
                    <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-primary hover:text-primary-hover inline-flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                @if($recentBookings->isEmpty())
                    <div class="py-12 text-center text-muted">
                        <svg class="w-12 h-12 mx-auto text-muted-soft mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm font-medium">Belum ada data reservasi masuk.</p>
                    </div>
                @else
                    <div class="overflow-x-auto -mx-6 px-6">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-canvas border-y border-hairline text-muted font-bold font-outfit uppercase tracking-wider text-[11px]">
                                <tr>
                                    <th class="py-3 px-4">Kode Booking</th>
                                    <th class="py-3 px-4">Pemesan</th>
                                    <th class="py-3 px-4">Destinasi</th>
                                    <th class="py-3 px-4">Pax</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-hairline">
                                @foreach($recentBookings as $b)
                                    <tr class="hover:bg-canvas/50 transition-colors">
                                        <td class="py-3 px-4 font-mono font-semibold text-ink-heading">
                                            {{ $b->booking_code }}
                                        </td>
                                        <td class="py-3 px-4 font-medium text-ink-heading">
                                            {{ $b->customer_name }}
                                            <span class="block text-[11px] text-muted">{{ $b->customer_phone }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="font-medium text-ink-heading">{{ $b->expedition->mountain->name ?? '-' }}</span>
                                            <span class="block text-[11px] text-muted">{{ $b->route->name ?? '-' }}</span>
                                        </td>
                                        <td class="py-3 px-4 font-bold text-ink-heading">
                                            {{ $b->pax_count }} Org
                                        </td>
                                        <td class="py-3 px-4">
                                            <x-admin.status-badge :status="$b->status" />
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <a href="{{ route('admin.bookings.show', $b->id) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1 rounded-full border border-hairline text-[11px] font-semibold text-ink hover:bg-canvas hover:border-primary hover:text-primary transition-colors">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Upcoming Price Lock Batches (Span 1) -->
        <div class="bg-surface-card border border-hairline rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-extrabold font-outfit text-ink-heading">Jadwal Price Lock</h3>
                        <p class="text-xs text-muted">Batch mendekati batas penguncian harga</p>
                    </div>
                    <a href="{{ route('admin.expeditions.index') }}" class="text-xs font-semibold text-primary hover:text-primary-hover">
                        Lihat Batch
                    </a>
                </div>

                @if($upcomingPriceLocks->isEmpty())
                    <div class="py-12 text-center text-muted">
                        <svg class="w-10 h-10 mx-auto text-muted-soft mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs">Tidak ada batch yang mendekati Price Lock.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingPriceLocks as $exp)
                            @php
                                $percent = $exp->quota_max > 0 ? min(100, round(($exp->quota_booked / $exp->quota_max) * 100)) : 0;
                            @endphp
                            <div class="p-3.5 rounded-2xl bg-canvas border border-hairline hover:border-muted-soft transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-xs text-ink-heading">
                                        {{ $exp->mountain->name ?? 'Gunung' }}
                                    </span>
                                    <span class="text-[11px] font-semibold text-primary">
                                        {{ \Carbon\Carbon::parse($exp->departure_date)->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-muted mb-1.5">
                                    <span>{{ $exp->route->name ?? 'Jalur' }} ({{ ucfirst($exp->type) }})</span>
                                    <span class="font-bold text-ink">{{ $exp->quota_booked }} / {{ $exp->quota_max }} Kursi</span>
                                </div>
                                <div class="w-full bg-hairline rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-primary h-1.5 rounded-full transition-all duration-300" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-6 pt-4 border-t border-hairline">
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200/80 text-[11px] text-amber-900 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Price Lock otomatis berjalan setiap pukul 00:01 WIB pada H-X hari keberangkatan untuk menentukan tarif final.</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
